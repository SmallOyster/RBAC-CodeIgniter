<?php
/**
 * @name 生蚝科技RBAC开发框架-C-用户
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-02-19
 * @version 2019-07-29
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public $sessPrefix;
	public $nowUserId;
	public $nowUserName;
	public $forgetPwdUserId;
	public $API_PATH;

	function __construct()
	{
		parent::__construct();
		$this->load->helper('string');

		$this->sessPrefix=$this->safe->getSessionPrefix();
		
		$this->API_PATH=$this->setting->get('apiPath');
		$this->nowUserId=$this->session->userdata($this->sessPrefix.'userId');
		$this->nowUserName=$this->session->userdata($this->sessPrefix.'userName');
	}
	
	
	public function toChangeRole()
	{
		$roleId=inputPost('roleId',0,1);
		$_SESSION[$this->sessPrefix.'roleId']=$roleId;
		
		returnAjaxData(200,'success');
	}


	public function updateProfile()
	{
		$query=$this->db->get_where('user',array('id'=>$this->nowUserId));
		$info=$query->first_row('array');
		$this->load->view('user/updateProfile',['info'=>$info]);
	}


	public function toUpdateProfile()
	{
		$nickName=inputPost('nickName',0,1);
		$phone=inputPost('phone',0,1);
		$email=inputPost('email',0,1);

		$this->db->set('nick_name',$nickName)
		         ->set('phone',$phone)
		         ->set('email',$email)
		         ->where('id',$this->nowUserId)
		         ->update('user');

		if($this->db->affected_rows()==1){
			returnAjaxData(200,'success');
		}else{
			returnAjaxData(1,'Failed to update');
		}
	}


	public function login()
	{
		$this->load->view('user/login');
	}


	public function toLogin()
	{
		$userName=inputPost('userName',0,1);
		$password=inputPost('password',0,1);

		$validate=$this->user->validateUser($password,0,$userName);

		if($validate==200){
			$userInfo=$this->user->getUserInfoByUserName($userName);
			$userId=$userInfo['id'];
			$nickName=$userInfo['nick_name'];
			$roleId=$userInfo['role_id'];
			$status=$userInfo['status'];
			
			if($status==0){
				returnAjaxData(1,'User forbidden');
			}elseif($status==2){
				returnAjaxData(3,'User not active');
			}
			
			// 获取角色名称
			$roleIds=explode(',',$roleId);
			$allRoleInfo=array();
			
			$this->db->where_in('id',$roleIds);
			$roleQuery=$this->db->get('role');

			if($roleQuery->num_rows()<1){
				returnAjaxData(2,'Role info not found');
			}else{
				$roleList=$roleQuery->result_array();
				
				foreach($roleList as $roleInfo){
					$allRoleInfo[$roleInfo['id']]=$roleInfo['name'];
				}
			}
			
			// 将用户信息存入session
			$this->session->set_userdata($this->sessPrefix.'userId',$userId);
			$this->session->set_userdata($this->sessPrefix.'nickName',$nickName);
			$this->session->set_userdata($this->sessPrefix.'userName',$userName);
			$this->session->set_userdata($this->sessPrefix.'roleId',$roleIds[0]);
			$this->session->set_userdata($this->sessPrefix.'roleName',reset($allRoleInfo));
			$this->session->set_userdata($this->sessPrefix.'allRoleInfo',$allRoleInfo);

			$this->db->set('last_login',date('Y-m-d H:i:s'))
			         ->where('id',$userId)
			         ->update('user');

			$this->load->helper('JWT');
			$jwtToken=jwt_helper::create(['userId'=>$userId,'allRoleInfo'=>$allRoleInfo]);
			
			returnAjaxData(200,'success',['allRoleInfo'=>json_encode($allRoleInfo),'jwtToken'=>$jwtToken]);
		}elseif($validate==-1){
			returnAjaxData(1,"User forbidden");
		}else{
			returnAjaxData(403,"Invaild password");
		}
	}


	public function send()
	{
		$emailToken=md5(random_string('alnum',16).session_id());
		$emailURL=base_url('user/reg/verify/').$emailToken;
		$message='Email注册验证 / '.$this->setting->get("systemName").'<hr>';
		$message.='尊敬的小生蚝用户，感谢您注册'.$this->setting->get("systemName").'！为验证您的身份，请点击下方链接以完成注册激活：<br>';
		$message.='<a href="'.$emailURL.'">'.$emailURL.'</a><br><br>';
		$message.='此链接15分钟内有效，请尽快激活哦！若无法点击，请复制至浏览器访问。<br><br>';
		$message.='如您本人没有申请过注册本系统，请忽略本邮件。此邮件由系统自动发送，请勿回复！谢谢！<br><br>';
		$message.='如有任何问题，请<a href="mailto:service@xshgzs.com">联系我们</a>';
		$message.='<hr>';
		$message.='<center>&copy; 生蚝科技 2014-'.date('Y').'</center>';
		
		$this->load->library('email');
		$this->email->from($this->config->item('smtp_user'),'生蚝科技');
		$this->email->to('571339406@qq.com');
		$this->email->subject('['.$this->setting->get("systemName").'] 注册邮箱认证');
		$this->email->message($message);
		$mailSend=$this->email->send();

		die(var_dump($this->email->print_debugger()));
	}
	

	public function logout()
	{
		session_destroy();

		$redirect=inputGet('redirect',1);
		$url=strlen($redirect)>=1?base_url('user/login?redirect=').$redirect:base_url('user/login');
		die(header('location:'.$url));
	}


	public function register()
	{
		$this->load->view('user/register');
	}
	
	
	public function toRegister()
	{
		$userName=inputPost('userName',0,1);
		$nickName=inputPost('nickName',0,1);
		$password=inputPost('password',0,1);
		$phone=inputPost('phone',0,1);
		$email=inputPost('email',0,1);
		
		// 检查用户名手机邮箱是否已存在
		$query1=$this->db->get_where('user',['user_name'=>$userName]);
		if($query1->num_rows()!=0){
			returnAjaxData(1,'Have userName');
		}
		$query2=$this->db->get_where('user',['phone'=>$phone]);
		if($query2->num_rows()!=0){
			returnAjaxData(2,'Have phone');
		}
		$query3=$this->db->get_where('user',['email'=>$email]);
		if($query3->num_rows()!=0){
			returnAjaxData(3,'Have email');
		}

		$salt=random_string('alnum');
		$hashPassword=sha1(md5($password).$salt);
		$roleQuery=$this->db->get_where('role',['is_default'=>1]);

		if($roleQuery->num_rows()!=1){
			returnAjaxData(4,'No original role info');
		}

		$roleList=$roleQuery->first_row('array');
		$roleId=$roleList['id'];
	
		$sql='INSERT INTO user(user_name,nick_name,password,salt,role_id,phone,email,status) VALUES (?,?,?,?,?,?,?,1)';
		$query=$this->db->query($sql,[$userName,$nickName,$hashPassword,$salt,$roleId,$phone,$email]);
		
		/*$emailToken=md5(random_string('alnum',16).session_id());
		$emailURL=base_url('user/reg/verify/').$emailToken;
		$message='Email注册验证 / '.$this->setting->get("systemName").'<hr>';
		$message.='尊敬的'.$userName.'用户，感谢您注册'.$this->setting->get("systemName").'！为验证您的身份，请点击下方链接以完成注册激活：<br>';
		$message.='<a href="'.$emailURL.'">'.$emailURL.'</a><br><br>';
		$message.='此链接15分钟内有效，请尽快激活哦！若无法点击，请复制至浏览器访问。<br><br>';
		$message.='如您本人没有申请过注册本系统，请忽略本邮件。此邮件由系统自动发送，请勿回复！谢谢！<br><br>';
		$message.='如有任何问题，请<a href="mailto:service@xshgzs.com">联系我们</a>';
		$message.='<hr>';
		$message.='<center>&copy; 生蚝科技 2014-'.date('Y').'</center>';
		
		$this->load->library('email');
		$this->email->from($this->config->item('smtp_user'),'生蚝科技');
		$this->email->to($email);
		$this->email->subject('['.$this->setting->get("systemName").'] 注册邮箱认证');
		$this->email->message($message);
		$mailSend=$this->email->send();
		
		if($this->db->affected_rows()==1 && $mailSend==true){
			$ip=$this->input->ip_address();
			$param=array('userName'=>$userName);
			$param=json_encode($param);
			$expireTime=time()+900;
			$sql2='INSERT INTO send_mail(email,token,param,expire_time,ip) VALUES (?,?,?,?,?)';
			$query2=$this->db->query($sql2,[$email,$emailToken,$param,$expireTime,$ip]);
			returnAjaxData(200,'success');*/
		if($this->db->affected_rows()==1){
			returnAjaxData(200,'success');
		}else if($this->db->affected_rows()==1){
			returnAjaxData(5001,'Success to register but failed to send active email');
		}else{
			returnAjaxData(5002,'Failed to register');
		}
	}
	
	
	/*public function verifyRegister($token)
	{
		$sql1='SELECT expire_time,param,status FROM send_mail WHERE token=?';
		$query1=$this->db->query($sql1,[$token]);
		
		if($query1->num_rows()!=1){
			die('<script>alert("激活链接无效！\n请通过正确途径激活邮箱！");window.location.href="'.base_url().'";</script>');
		}
		
		$list=$query1->result_array();
		$info=$list[0];
		$expire_time=$info['expire_time'];
		$param=$info['param'];
		$status=$info['status'];
		
		if($expire_time<=time()){
			die('<script>alert("激活链接已过期！");window.location.href="'.base_url().'";</script>');
		}
		if($status!=0){
			die('<script>alert("激活链接已被使用！");window.location.href="'.base_url().'";</script>');
		}
		
		$param=json_decode($param,TRUE);
		$userName=$param['userName'];
		
		$sql2='UPDATE user SET status=1 WHERE user_name=?';
		$query2=$this->db->query($sql2,[$userName]);
		
		if($this->db->affected_rows()==1){
			$sql3='UPDATE send_mail SET status=1 WHERE token=?';
			$query3=$this->db->query($sql3,[$token]);
			if($this->db->affected_rows()==1){
				die('<script>alert("激活成功！请重新登录！");window.location.href="'.base_url().'";</script>');
			}else{
				die('<script>alert("激活失败！请联系管理员！");window.location.href="'.base_url().'";</script>');
			}
		}else{
			die('<script>alert("用户激活失败！请联系管理员！");window.location.href="'.base_url().'";</script>');
		}
	}*/
	
	
	public function forgetPassword()
	{
		$this->load->view('user/forgetPassword');
	}
	
	
	public function forgetPasswordSendCode()
	{
		$email=inputPost('email',0,1);
		$sql='SELECT id,user_name,nick_name FROM user WHERE email=?';
		$query=$this->db->query($sql,[$email]);
		
		if($query->num_rows()!=1){
			returnAjaxData(404,"no User");
		}else{
			$list=$query->result_array();
			$id=$list[0]['id'];
			$userName=$list[0]['user_name'];
			$nickName=$list[0]['nick_name'];
		}
		
		$verifyCode=mt_rand(123456,987654);
		$expireTime=time()+600;
		$this->session->set_userdata($this->sessPrefix.'forgetPwd_mailInfo',array('email'=>$email,'verifyCode'=>$verifyCode,'expireTime'=>$expireTime));
		$this->session->set_userdata($this->sessPrefix.'forgetPwd_userId',$id);
		$this->session->set_userdata($this->sessPrefix.'forgetPwd_nickName',$nickName);
		$this->session->set_userdata($this->sessPrefix.'forgetPwd_userName',$userName);
		
		$message='Email验证 / '.$this->setting->get("systemName").'<hr>';
		$message.='尊敬的'.$nickName.'用户，您正在申请重置密码，请填写此验证码以继续重置密码：'.$verifyCode.'<br><br>';
		$message.='此验证码15分钟内有效，请尽快填写哦！<br><br>';
		$message.='如您本人没有申请过重置密码，请忽略本邮件。此邮件由系统自动发送，请勿回复！谢谢！<br><br>';
		$message.='如有任何问题，请<a href="mailto:service@xshgzs.com">联系我们</a>';
		$message.='<hr>';
		$message.='<center>&copy; 生蚝科技 2014-'.date('Y').'</center>';
		
		$this->load->library('email');

		$this->email->from($this->config->item('smtp_user'),'生蚝科技');
		$this->email->to($email);
		$this->email->subject('['.$this->setting->get("systemName").'] 重置密码邮箱认证');
		$this->email->message($message);

		$mailSend=$this->email->send();
		
		if($mailSend==TRUE){
			returnAjaxData(200,"success");
		}else{
			returnAjaxData(500,"failed to Send Email");
		}
	}
	
	
	public function forgetPasswordVerifyCode()
	{
		$email=inputPost('email',0,1);
		$verifyCode=inputPost('verifyCode',0,1);
		$info=$this->session->userdata($this->sessPrefix.'forgetPwd_mailInfo');
		
		if($email!=$info['email']){
			returnAjaxData(1,'invalid Mail');
		}elseif($verifyCode!=$info['verifyCode']){
			returnAjaxData(2,'invalid Code');
		}elseif($info['expireTime']<=time()){
			returnAjaxData(3,'expired');
		}else{
			$this->session->unset_userdata($this->sessPrefix.'forgetPwd_mailInfo');
			returnAjaxData(200,"success",base_url('user/resetPassword'));
		}
	}

	
	public function resetPassword()
	{
		if($this->session->userdata($this->sessPrefix.'forgetPwd_userId')<1 || $this->session->userdata($this->sessPrefix.'forgetPwd_mailInfo')!=NULL){
			die('<script>alert("非法访问！");window.location.href="'.base_url('user/forgetPassword').'";</script>');
		}else{
			$this->load->view('user/resetPwd');
		}		
	}
	
	
	public function toResetPassword()
	{
		$userId=$this->session->userdata($this->sessPrefix.'forgetPwd_userId');
		$userName=inputPost('userName',0,1);
		$password=inputPost('pwd',0,1);
		
		$salt=random_string('alnum');
		$hashPassword=sha1(md5($password).$salt);
		
		$nowTime=date('Y-m-d H:i:s');
		$sql='UPDATE user SET password=?,salt=?,update_time=? WHERE id=?';
		$query=$this->db->query($sql,[$hashPassword,$salt,$nowTime,$userId]);
		
		if($this->db->affected_rows()==1){
			returnAjaxData(200,'success');
		}else{
			returnAjaxData(1,'Failed to reset password');
		}
	}
}

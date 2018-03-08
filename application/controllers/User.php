<?php
/**
 * @name C-用户
 * @author SmallOysyer <master@xshgzs.com>
 * @since 2018-02-19
 * @version V1.0 2018-03-08
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public $allMenu;
	public $sessPrefix;
	public $nowUserID;
	public $nowUserName;
	
	function __construct()
	{
		parent::__construct();
		$this->load->helper('string');
		$this->load->library(array('Ajax'));

		$this->sessPrefix=$this->safe->getSessionPrefix();
		$roleID=$this->session->userdata($this->sessPrefix."roleID");
		$this->allMenu=$this->RBAC_model->getAllMenuByRole($roleID);
		
		$this->nowUserID=$this->session->userdata($this->sessPrefix.'userID');
		$this->nowUserName=$this->session->userdata($this->sessPrefix.'userName');
	}


	public function updateProfile()
	{
		$this->ajax->makeAjaxToken();

		$sql="SELECT * FROM user WHERE id=?";
		$query=$this->db->query($sql,[$this->nowUserID]);
		$list=$query->result_array();
		$info=$list[0];
		$this->load->view('user/updateProfile',["navData"=>$this->allMenu,'info'=>$info]);
	}


	public function toUpdateProfile()
	{
		$token=$this->input->post('token');
		$this->ajax->checkAjaxToken($token);

		$nickName=$this->input->post('nickName');
		$oldPwd=$this->input->post('oldPwd');
		$newPwd=$this->input->post('newPwd');
		$phone=$this->input->post('phone');
		$email=$this->input->post('email');

		$sql="UPDATE user SET nick_name=?,";
		$updateData=array();
		array_push($updateData,$nickName);

		if($oldPwd!=""){
			$validateOldPwd=$this->User_model->validateUser($this->nowUserID,"",$oldPwd);

			if($validateOldPwd=="200"){
				$salt=random_string('alnum');
				$hashSalt=md5($salt);
				$hashPwd=sha1($newPwd.$hashSalt);

				$sql.="password=?,salt=?,";
				array_push($updateData,$hashPwd,$salt);
			}elseif($validateOldPwd=="403"){
				$ret=$this->ajax->returnData("3","userForbidden");
				die($ret);
			}else{
				$ret=$this->ajax->returnData("4","invaildPwd");
				die($ret);
			}
		}

		$sql.="phone=?,email=? WHERE id=?";
		array_push($updateData,$phone,$email,$this->nowUserID);
		$query=$this->db->query($sql,$updateData);

		if($this->db->affected_rows()==1){
			$ret=$this->ajax->returnData("200","success");
			die($ret);
		}else{
			$ret=$this->ajax->returnData("0","updateFailed");
			die($ret);
		}
	}


	public function login()
	{
		$this->ajax->makeAjaxToken();

		$this->load->view('user/login');
	}


	public function toLogin()
	{
		$token=$this->input->post('token');
		$this->ajax->checkAjaxToken($token);

		$userName=$this->input->post('userName');
		$pwd=$this->input->post('pwd');

		$validate=$this->User_model->validateUser(0,$userName,$pwd);

		if($validate=="200"){
			$userInfo=$this->User_model->getUserInfoByUserName($userName);
			$userID=$userInfo['id'];
			$roleID=$userInfo['role_id'];
			
			// 获取角色名称
			$roleQuery=$this->db->query('SELECT name FROM role WHERE id=?',[$roleID]);
			if($roleQuery->num_rows()!=1){
				$ret=$this->ajax->returnData("2","noRoleInfo");
				die($ret);
			}
			
			$roleList=$roleQuery->result_array();
			$roleName=$roleList[0]['name'];
			
			// 将用户信息存入session
			$this->session->set_userdata($this->sessPrefix.'userID',$userID);
			$this->session->set_userdata($this->sessPrefix.'userName',$userName);
			$this->session->set_userdata($this->sessPrefix.'roleID',$roleID);
			$this->session->set_userdata($this->sessPrefix.'roleName',$roleName);
			
			$ret=$this->ajax->returnData("200","success");
			die($ret);
		}elseif($validate=="403"){
			$ret=$this->ajax->returnData("1","userForbidden");
			die($ret);
		}else{
			$ret=$this->ajax->returnData("0","invaildPwd");
			die($ret);
		}
	}
	
	public function logout()
	{
		session_destroy();
		header("Location:".site_url('user/login'));
	}
	
	
	public function register()
	{
		$this->ajax->makeAjaxToken();

		$this->load->view('user/reg');
	}
	
	
	public function toRegister()
	{
		$token=$this->input->post('token');
		$this->ajax->checkAjaxToken($token);
		
		$userName=$this->input->post('userName');
		$nickName=$this->input->post('nickName');
		$pwd=$this->input->post('pwd');
		$phone=$this->input->post('phone');
		$email=$this->input->post('email');

		// 检查用户名手机邮箱是否已存在
		$sql1="SELECT id FROM user WHERE user_name=?";
		$query1=$this->db->query($sql1,[$userName]);
		if($query1->num_rows()!=0){
			$ret=$this->ajax->returnData("1","haveUserName");
			die($ret);
		}
		$sql2="SELECT id FROM user WHERE phone=?";
		$query2=$this->db->query($sql2,[$phone]);
		if($query2->num_rows()!=0){
			$ret=$this->ajax->returnData("2","havePhone");
			die($ret);
		}
		$sql3="SELECT id FROM user WHERE email=?";
		$query3=$this->db->query($sql3,[$email]);
		if($query3->num_rows()!=0){
			$ret=$this->ajax->returnData("3","haveEmail");
			die($ret);
		}

		$salt=random_string('alnum');
		$hashSalt=md5($salt);
		$hashPwd=sha1($pwd.$hashSalt);

		$roleQuery=$this->db->query("SELECT id FROM role WHERE is_default=1");
		if($roleQuery->num_rows()!=1){
			$ret=$this->ajax->returnData("4","noRoleInfo");
			die($ret);
		}

		$roleList=$roleQuery->result_array();
		$roleID=$roleList[0]['id'];
	
		$sql="INSERT INTO user(user_name,nick_name,password,salt,role_id,phone,email,status) VALUES (?,?,?,?,?,?,?,0)";
		$query=$this->db->query($sql,[$userName,$nickName,$hashPwd,$salt,$roleID,$phone,$email]);
		
		$emailURL=site_url('user/register/verify/').md5(random_string('alnum',16).session_id());
		$message='Email注册验证 / '.$this->Setting_model->get("systemName").'<hr>';
		$message.='尊敬的'.$userName.'用户，感谢您注册'.$this->Setting_model->get("systemName").'！为验证您的身份，请点击下方链接以完成注册激活：<br>';
		$message.='<a href="'.$emailURL.'">'.$emailURL.'</a><br><br>';
		$message.='此链接15分钟内有效，请尽快激活哦！若无法点击，请复制至浏览器访问。<br><br>';
		$message.='如您本人没有申请过注册本系统，请忽略本邮件。此邮件由系统自动发送，请勿回复！谢谢！<br><br>';
		$message.='如有任何问题，请<a href="mailto:service@xshgzs.com">联系我们</a>';
		$message.='<hr>';
		$message.='<center>生蚝科技 &copy;2017-2018</center>';
		
		$this->load->library('email');

		$this->email->from($this->config->item('smtp_user').'@qq.com','生蚝科技');
		$this->email->to($email);
		$this->email->subject('['.$this->Setting_model->get("systemName").'] 注册邮箱认证');
		$this->email->message($message);

		$mailSend=$this->email->send();

		if($this->db->affected_rows()==1 && $mailSend==TRUE){
			$ip=$this->input->ip_address();
			$param=array('userName'=>$userName);
			$param=json_encode($param);
			$expireTime=time()+900;
			$sql2="INSERT INTO send_mail(email,token,param,expire_time,ip) VALUES (?,?,?,?,?)";
			$query2=$this->db->query($sql2,[$email,$token,$param,$expireTime,$ip]);
			$ret=$this->ajax->returnData("200","success");
			die($ret);
		}else{
			$ret=$this->ajax->returnData("0","regFailed");
			die($ret);
		}
	}
	
	
	public function verifyRegister($token)
	{
		$sql1="SELECT expire_time,param FROM send_mail WHERE token=?";
		$query1=$this->db->query($sql1,[$token]);
		
		if($query1->num_rows()!=1){
			die('<script>alert("激活链接无效！\n请通过正确途径激活邮箱！");window.location.href="'.site_url().'";</script>');
		}
		
		$list=$query1->result_array();
		$info=$list[0];
		$expire_time=$info['expire_time'];
		$param=$info['param'];
		
		if($expire_time>=time()){
			die('<script>alert("激活链接已过期！");window.location.href="'.site_url().'";</script>');
		}
		
		$param=json_decode($param,TRUE);
		$userName=$param['userName'];
		
		$sql2="UPDATE user SET status=1 WHERE user_name=?";
		$query2=$this->db->query($sql2,[$userName]);
		
		if($this->db->affected_rows()==1){
			die('<script>alert("激活成功！请重新登录！");window.location.href="'.site_url().'";</script>');
		}else{
			die('<script>alert("激活失败！请联系管理员！");window.location.href="'.site_url().'";</script>');
		}
	}
	
	
	public function forgetPassword()
	{
		$this->ajax->makeAjaxToken();

		$this->load->view('user/forgetPwd');
	}
	
	
	public function toForgetPassword()
	{
		$token=$this->input->post('token');
		$this->ajax->checkAjaxToken($token);
		
		$userName=$this->input->post('userName');
		$pwd=$this->input->post('pwd');
		$phone=$this->input->post('phone');
		$email=$this->input->post('email');
		
		$userInfo_sql="SELECT id FROM user WHERE user_name=? AND email=? AND phone=?";
		$userInfo_query=$this->db->query($userInfo_sql,[$userName,$email,$phone]);
		
		if($userInfo_query->num_rows()==1){
			$userInfo_list=$userInfo_query->result_array();
			$userInfo=$userInfo_list[0];
			$userID=$userInfo['id'];
		}else{
			$ret=$this->ajax->returnData("1","noUser");
			die($ret);
		}
		
		$salt=random_string('alnum');
		$hashSalt=md5($salt);
		$hashPwd=sha1($pwd.$hashSalt);
		
		$nowTime=date("Y-m-d H:i:s");
		$sql="UPDATE user SET password=?,salt=?,update_time=? WHERE id=?";
		$query=$this->db->query($sql,[$hashPwd,$salt,$nowTime,$userID]);
		
		$emailURL=site_url('user/forgetPwd/verify/').md5(random_string('alnum',16).session_id());
		$message='Email验证 / '.$this->Setting_model->get("systemName").'<hr>';
		$message.='尊敬的zjh用户，感谢您注册'.$this->Setting_model->get("systemName").'！为验证您的身份，请点击下方链接以完成注册激活：<br>';
		$message.='<a href="'.$emailURL.'">'.$emailURL.'</a><br><br>';
		$message.='此链接15分钟内有效，请尽快激活哦！若无法点击，请复制至浏览器访问。<br><br>';
		$message.='如您本人没有申请过重置密码，请忽略本邮件。此邮件由系统自动发送，请勿回复！谢谢！<br><br>';
		$message.='如有任何问题，请<a href="mailto:service@xshgzs.com">联系我们</a>';
		$message.='<hr>';
		$message.='<center>生蚝科技 &copy;2017-2018</center>';
		
		$this->load->library('email');

		$this->email->from($this->config->item('smtp_user').'@qq.com','生蚝科技');
		$this->email->to($email);
		$this->email->subject('['.$this->Setting_model->get("systemName").'] 注册邮箱认证');
		$this->email->message($message);

		$mailSend=$this->email->send();

		if($this->db->affected_rows()==1 && $mailSend==TRUE){
			$ret=$this->ajax->returnData("200","success");
			die($ret);
		}else{
			$ret=$this->ajax->returnData("0","resetFailed");
			die($ret);
		}
	}
}

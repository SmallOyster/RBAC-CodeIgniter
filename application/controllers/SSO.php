<?php
/**
 * @name 生蚝科技RBAC开发框架-C-SSO
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-02-17
 * @version 2019-07-28
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class SSO extends CI_Controller {

	public $sessPrefix;
	public $nowUserId;
	public $nowUserName;
	public $forgetPwdUserId;
	public $API_PATH;

	function __construct()
	{
		parent::__construct();
		
		$this->sessPrefix=$this->safe->getSessionPrefix();
		$this->API_PATH=$this->setting->get('apiPath');
	}


	public function login()
	{
		$appId=$this->setting->get('ssoAppId');
		$redirectUrl=$this->setting->get('ssoRedirectUrl');
		$ssoLoginUrl=$this->setting->get('ssoServerHost')."login?appId=".$appId."&redirectUrl=".urlencode($redirectUrl);
		$token=isset($_GET['token'])&&$_GET['token']!=""?$_GET['token']:die(header("location:".$ssoLoginUrl));

		$postData=array('method'=>'api','token'=>$token,'appId'=>$appId,'redirectUrl'=>$redirectUrl);
		$output=curl($this->setting->get('ssoApiPath').'user/getUserInfo','post',$postData);
		$data=json_decode($output,TRUE);
		
		if($data['code']!=200){die(header("location:".$ssoLoginUrl));}
		else{$unionId=$data['data']['userInfo']['unionId'];}

		$userInfoQuery=$this->db->get_where('user',['sso_union_id'=>$unionId]);
		
		if($userInfoQuery->num_rows()!=1){
			die('<script>alert("此通行证未绑定用户！");window.location.href="'.base_url('/').'";</script>');
		}else{
			$userInfo=$userInfoQuery->first_row('array');
		}
		
		$userId=$userInfo['id'];
		$userName=$userInfo['user_name'];
		$nickName=$userInfo['nick_name'];
		$roleId=$userInfo['role_id'];
		$status=$userInfo['status'];

		if($status==0){
			die('<script>alert("此通行证绑定的用户被禁用！");window.location.href="'.base_url('/').'";</script>');
		}elseif($status==2){
			die('<script>alert("此通行证绑定的用户未激活！");window.location.href="'.base_url('/').'";</script>');
		}

		// 获取角色名称
		$roleIds=explode(',',$roleId);
		$allRoleInfo=array();
			
		$this->db->where_in($roleIds);
		$roleQuery=$this->db->get('role');

		if($roleQuery->num_rows()<1){
			die('<script>alert("角色信息不存在！\n请联系管理员！");window.location.href="'.base_url('/').'";</script>');
		}else{
			$roleList=$roleQuery->result_array();
				
			foreach($roleList as $roleInfo){
				$allRoleInfo[$roleInfo['id']]=$roleInfo['name'];
			}
		}

		$query=$this->db->query('UPDATE user SET last_login=? WHERE id=?',[date('Y-m-d H:i:s'),$userId]);

		$roleList=$roleQuery->result_array();
		$roleName=$roleList[0]['name'];

		// 将用户信息存入session
		$this->session->set_userdata($this->sessPrefix.'userId',$userId);
		$this->session->set_userdata($this->sessPrefix.'nickName',$nickName);
		$this->session->set_userdata($this->sessPrefix.'userName',$userName);
		$this->session->set_userdata($this->sessPrefix.'roleId',$roleIds[0]);
		$this->session->set_userdata($this->sessPrefix.'roleName',reset($allRoleInfo));
		$this->session->set_userdata($this->sessPrefix.'allRoleInfo',$allRoleInfo);

		die('<script>sessionStorage.setItem("allRoleInfo",'."'".json_encode($allRoleInfo)."'".');window.location.href="'.base_url('/').'";</script>');
	}


	public function bind()
	{
		$this->safe->checkLogin(0);

		$this->nowUserId=$this->session->userdata($this->sessPrefix.'userId');
		$this->nowUserName=$this->session->userdata($this->sessPrefix.'userName');

		$this->load->view('user/ssoBind');
	}


	public function checkBind()
	{
		$userId=$this->session->userdata($this->sessPrefix.'userId');

		$list=$this->db->get_where('user',['id'=>$userId]);
		$info=$list->first_row('array');

		if($info==[]) returnAjaxData(4001,'No user');
		elseif(strlen($info['sso_union_id'])!=8) returnAjaxData(4002,'Invaild SSO unionID');

		$query=curl($this->setting->get('ssoServerHost').'api/user/getUserInfo','post',['method'=>'unionId','unionId'=>$info['sso_union_id']]);
		$query=json_decode($query,true);

		if($query['code']==200){
			returnAjaxData(200,'success',['userInfo'=>$query['data']['userInfo']]);
		}else{
			returnAjaxData(4003,'SSO user not found',$query);
		}
	}


	public function bindLogin()
	{
		$this->safe->checkLogin(1);

		$userName=inputPost('userName',0,1);
		$password=inputPost('password',0,1);

		$query=curl($this->setting->get('ssoServerHost').'user/toLogin','post',['userName'=>$userName,'password'=>$password,'appId'=>$this->setting->get('ssoAppId'),'type'=>'appApi']);
		$query=json_decode($query,true);

		if($query['code']==200){
			$bindToken=sha1(time().md5($query['data']['userInfo']['ssoUnionId']));
			
			$_SESSION[$this->sessPrefix.'ssoBindUserId']=$this->session->userdata($this->sessPrefix.'userId');
			$_SESSION[$this->sessPrefix.'ssoBindUnionId']=$query['data']['userInfo']['ssoUnionId'];
			$_SESSION[$this->sessPrefix.'ssoBindToken']=$bindToken;
			$_SESSION[$this->sessPrefix.'ssoBindTokenExpireTime']=time()+120;

			returnAjaxData(200,'success',['userInfo'=>$query['data']['userInfo'],'token'=>$bindToken,'expireTime'=>$_SESSION[$this->sessPrefix.'ssoBindTokenExpireTime']]);
		}
		elseif($query['code']==4031) returnAjaxData(4031,'Failed to authorize');
		elseif($query['code']==4032) returnAjaxData(4032,$query['message']);
		else returnAjaxData(500,'Failed to request SSO server');
	}
	
	
	public function toBind()
	{
		$this->safe->checkLogin(1);

		$token=inputPost('token',0,1);
		$unionId=inputPost('unionId',0,1);

		if($token!=$this->session->userdata($this->sessPrefix.'ssoBindToken')) returnAjaxData(4031,'Invaild token');
		if($unionId!=$this->session->userdata($this->sessPrefix.'ssoBindUnionId')) returnAjaxData(4032,'Invaild unionID');
		if(time()>$this->session->userdata($this->sessPrefix.'ssoBindTokenExpireTime')) returnAjaxData(4033,'Token expired');

		$this->db->set('sso_union_id',$unionId)
		         ->where('id',$_SESSION[$this->sessPrefix.'ssoBindUserId'])
		         ->update('user');

		if($this->db->affected_rows()==1) returnAjaxData(200,'success');
		else returnAjaxData(500,'Database error');
	}


	public function unbind()
	{
		$this->safe->checkLogin(1);

		$password=inputPost('password',0,1);
		$validate=$this->user->validateUser($password,0,$this->session->userdata($this->sessPrefix.'userName'));

		if($validate==200){
			$this->db->set('sso_union_id',null)
			         ->where('id',$this->session->userdata($this->sessPrefix.'userId'))
			         ->update('user');

			if($this->db->affected_rows()==1) returnAjaxData(200,'success');
			else returnAjaxData(500,'Database error');
		}else{
			returnAjaxData(403,'Failed to authorize');
		}
	}
}

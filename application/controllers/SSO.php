<?php
/**
 * @name 生蚝科技RBAC开发框架-C-SSO
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-02-17
 * @version 2019-02-17
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class SSO extends CI_Controller {

	public $sessPrefix;

	function __construct()
	{
		parent::__construct();
		
		$this->sessPrefix=$this->safe->getSessionPrefix();
	}


	public function login()
	{
		$appId="otsso_025c13ebd55e9af0ebd";
		$returnUrl="https://test.xshgzs.com/rbac/SSO/login";
		$ssoLoginUrl="https://ssouc.xshgzs.com/login/".$appId."/".urlencode($returnUrl);
		$token=isset($_GET['token'])&&$_GET['token']!=""?$_GET['token']:die(header("location:".$ssoLoginUrl));

		$postData=array('method'=>'api','token'=>$token,'appId'=>$appId,'returnUrl'=>$returnUrl);
		$output=curl('https://ssouc.xshgzs.com/api/user/getUserInfo','post',$postData);
		$data=json_decode($output,TRUE);
		
		if($data['code']!=200){die(header("location:".$ssoLoginUrl));}
		else{$unionId=$data['data']['userInfo']['unionId'];}

		$userInfoQuery=$this->db->query('SELECT * FROM user WHERE sso_union_id=?',[$unionId]);
		if($userInfoQuery->num_rows()!=1){
			die('<script>alert("此通行证未绑定用户！");window.location.href="'.base_url('/').'";</script>');
		}else{
			$userInfo=$userInfoQuery->result_array();
			$userInfo=$userInfo[0];
		}
		
		$userID=$userInfo['id'];
		$userName=$userInfo['user_name'];
		$nickName=$userInfo['nick_name'];
		$roleID=$userInfo['role_id'];
		$status=$userInfo['status'];

		if($status==0){
			die('<script>alert("此通行证绑定的用户被禁用！");window.location.href="'.base_url('/').'";</script>');
		}elseif($status==2){
			die('<script>alert("此通行证绑定的用户未激活！");window.location.href="'.base_url('/').'";</script>');
		}

		// 获取角色名称
		$roleQuery=$this->db->query('SELECT name FROM role WHERE id=?',[$roleID]);
		if($roleQuery->num_rows()!=1){
			die('<script>alert("角色信息不存在！\n请联系管理员！");window.location.href="'.base_url('/').'";</script>');
		}

		$query=$this->db->query("UPDATE user SET last_login=? WHERE id=?",[date("Y-m-d H:i:s"),$userID]);

		$roleList=$roleQuery->result_array();
		$roleName=$roleList[0]['name'];

		// 将用户信息存入session
		$this->session->set_userdata($this->sessPrefix.'userID',$userID);
		$this->session->set_userdata($this->sessPrefix.'nickName',$nickName);
		$this->session->set_userdata($this->sessPrefix.'userName',$userName);
		$this->session->set_userdata($this->sessPrefix.'roleID',$roleID);
		$this->session->set_userdata($this->sessPrefix.'roleName',$roleName);

		die('<script>window.location.href="'.base_url('/').'";</script>');
	}
}

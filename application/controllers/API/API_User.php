<?php
/**
 * @name 生蚝科技RBAC开发框架-C-API-用户
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-01-19
 * @version 2019-07-29
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class API_User extends CI_Controller {

	public $sessPrefix;

	public function __construct()
	{
		parent::__construct();
		$this->sessPrefix=$this->safe->getSessionPrefix();
	}


	public function resetPassword()
	{
		$auth=$this->safe->checkAuth('api',substr($this->input->server('HTTP_REFERER'),strpos($this->input->server('HTTP_REFERER'),base_url())+strlen(base_url())));
		if($auth!=true) returnAjaxData(403,"no Permission");

		$userId=inputPost('userId',0,1);
		if($userId==$this->session->userdata($this->sessPrefix.'user_id')) returnAjaxData(400,"cannot Operate own");

		$password=mt_rand(12345678,98765432);
		$salt=getRanSTR(8);
		$hash=sha1(md5($password).$salt);

		$query=$this->db->update('user',array('password'=>$hash,'salt'=>$salt),array('id'=>$userId));
		
		if($query==true) returnAjaxData(200,"success",['password'=>$password]);
		else returnAjaxData(500,"database Error");
	}


	public function delete()
	{
		$auth=$this->safe->checkAuth('api',substr($this->input->server('HTTP_REFERER'),strpos($this->input->server('HTTP_REFERER'),base_url())+strlen(base_url())));
		if($auth!=true) returnAjaxData(403,"no Permission");

		$userId=inputPost('userId',0,1);
		if($userId==$this->session->userdata($this->sessPrefix.'user_id')) returnAjaxData(400,"cannot Operate own");

		$query=$this->db->delete('user',array('id'=>$userId));
		if($query==true) returnAjaxData(200,"success");
		else returnAjaxData(500,"database Error");
	}


	public function getAllUser()
	{
		$query=$this->db->query('SELECT a.id,a.union_id AS unionId,a.user_name AS userName,a.nick_name AS nickName,b.name AS roleName FROM user a,role b WHERE a.role_id=b.id');
		returnAjaxData(200,'success',['list'=>$query->result_array()]);
	}


	public function checkDuplicate()
	{
		$field=inputGet('field',0,1);
		$value=inputGet('value',0,1);
		$vaildFields=['sso_union_id','user_name','nick_name','phone','email'];

		if(in_array($field,$vaildFields)){
			$this->db->where($field,$value);
		}else{
			returnAjaxData(1,"Invaild Field",$field);
		}

		$query=$this->db->get('user');

		if($query->num_rows()!=1){
			returnAjaxData(200,"No user");
		}else{
			returnAjaxData(400,"Have user");
		}
	}


	public function updateUserInfo()
	{
		$userId=isset($_POST['userId'])&&$_POST['userId']!=""?$_POST['userId']:$this->session->userdata($this->sessPrefix.'user_id');
		$userName=inputPost('userName',0,1);
		$nickName=inputPost('nickName',0,1);
		$phone=inputPost('phone',0,1);
		$email=inputPost('email',0,1);
		$roleId=isset($_POST['roleId'])&&$_POST['roleId']!=""?$_POST['roleId']:0;

		// 检查是否有重复
		$this->db->where('user_name',$userName);
		$this->db->where('id!=',$userId);
		$query1=$this->db->get('user');
		if($query1->num_rows()!=0){
			returnAjaxData(1,"have UserName");
		}

		$this->db->where('nick_name',$nickName);
		$this->db->where('id!=',$userId);
		$query2=$this->db->get('user');
		if($query2->num_rows()!=0){
			returnAjaxData(2,"have nickName");
		}

		$this->db->where('phone',$phone);
		$this->db->where('id!=',$userId);
		$query3=$this->db->get('user');
		if($query3->num_rows()!=0){
			returnAjaxData(3,"have Phone");
		}

		// 修改资料
		if($roleId>0){
			$query4=$this->db->update('user',array('role_id'=>$roleId),array('id'=>$userId));
		}else{
			$query4=$this->db->update('user',array('user_name'=>$userName,'nick_name'=>$nickName,'phone'=>$phone,'email'=>$email),array('id'=>$userId));
		}

		if($query4==true){
			returnAjaxData(200,'success');
		}else{
			returnAjaxData(5,'Failed to change');
		}
	}


	public function getUserInfo()
	{
		$method=inputPost('method',0,1);

		if($method=="admin"){
			$id=inputPost('userId',0,1);
			$query=$this->db->query('SELECT user_name AS userName,nick_name AS nickName,phone,email,role_id FROM user WHERE id=?',[$id]);

			if($query->num_rows()!=1){
				returnAjaxData(1,'no User');
			}else{
				$list=$query->result_array();
				$userInfo=$list[0];

				$roleIds=explode(',',$list[0]['role_id']);

				foreach($roleIds as $roleId){
					$this->db->or_where('id', $roleId);
				}

				unset($userInfo['role_id']);
				$query2=$this->db->get('role');
				$roleList=$query2->result_array();
				$roleName='';

				foreach($roleList as $roleInfo){
					$roleName.=$roleInfo['name'].',';
					$userInfo['roleName'][$roleInfo['id']]=$roleInfo['name'];
				}

				$userInfo['allRoleName']=substr($roleName,0,strlen($roleName)-1);

				returnAjaxData(200,'success',['userInfo'=>$userInfo]);
			}
		}else if($method=="own"){
			$query=$this->db->query('SELECT a.user_name AS userName,a.nick_name AS nickName,a.phone,a.email,b.name AS roleName FROM user a,role b WHERE a.id=? AND b.id=?',[$_SESSION[$this->sessPrefix.'userId'],$_SESSION[$this->sessPrefix.'roleId']]);

			if($query->num_rows()!=1){
				returnAjaxData(1,'no User');
			}else{
				$info=$query->first_row('array');
				$info['roleId']=$_SESSION[$this->sessPrefix.'roleId'];
				returnAjaxData(200,'success',['userInfo'=>$info]);
			}
		}/*else if($method=="api"){
			$token=isset($_POST['token'])&&$_POST['token']!=""?$_POST['token']:returnAjaxData(0,"lack Param");
			$this->db->select('user_id');
			$query=$this->db->get_where('login_token',array('token'=>$token));

			if($query->num_rows()==1){
				$list=$query->result_array();
				$userId=$list[0]['user_id'];
				$userInfoQuery=$this->db->query('SELECT,a.user_name AS userName,a.nick_name AS nickName,a.phone,a.email,b.name AS roleName FROM user a,role b WHERE a.id=? AND a.role_id=b.id',[$userId]);

				if($userInfoQuery->num_rows()!=1){
					returnAjaxData(1,"no User");
				}else{
					$userInfoList=$userInfoQuery->result_array();
					$this->db->where('token',$token);
					$this->db->delete('login_token');
					returnAjaxData(200,"success",['userInfo'=>$userInfoList[0]]);
				}
			}else{
				returnAjaxData(403,"failed To Auth");
			}
		}*/else{
			returnAjaxData(2,'Invaild Method');
		}
	}
}

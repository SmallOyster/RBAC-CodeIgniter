<?php
/**
* @name 生蚝科技RBAC开发框架-C-RBAC-用户
* @author Jerry Cheung <master@xshgzs.com>
* @since 2018-02-08
* @version 2019-07-26
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class RbacAdmin_user extends CI_Controller {
	
	public $sessPrefix;
	public $nowUserId;
	public $nowUserName;
	public $API_PATH;
	
	function __construct()
	{
		parent::__construct();

		$this->safe->checkAuth();

		$this->API_PATH=$this->setting->get('apiPath');
		$this->sessPrefix=$this->safe->getSessionPrefix();
		$this->nowUserId=$this->session->userdata($this->sessPrefix.'userId');
		$this->nowUserName=$this->session->userdata($this->sessPrefix.'userName');
		$this->load->helper('string');
	}


	public function toList()
	{
		$this->load->view('admin/user');
	}


	public function get()
	{
		$auth=$this->safe->checkAuth('api','admin/user/list');
		if($auth!=true) returnAjaxData(403002,'No permission');

		$query=$this->db->select('id,sso_union_id,user_name,nick_name,email,phone,status,role_id,sso_union_id,create_time,update_time,last_login')
		                ->get('user');
		$list=$query->result_array();
		returnAjaxData(200,'success',['list'=>$list]);
	}


	public function toOperate()
	{
		$auth=$this->safe->checkAuth('api','admin/user/list');
		if($auth!=true) returnAjaxData(403002,'No permission');

		$vaildFields=['sso_union_id','user_name','nick_name','phone','email','role_id','status','password','salt'];
		$type=inputPost('type',0,1);
		$userId=inputPost('userId',0,1);
		$userData=inputPost('userData',0,1);
		
		foreach($userData as $field=>$value){
			// 检查 此字段是否可修改
			if(!in_array($field,$vaildFields)){
				returnAjaxData(4001,'Invaild Field',[$field]);
			}
			
			// 检查 值是否为空
			if($value==0 || $value==null || $value==''){
				returnAjaxData(4002,'Data cannot be null',[$field]);
			}

			if($field=='sso_union_id' && $value=='null') $value=null;
			
			$this->db->set($field,$value);
		}
		
		$originPassword='';
		if($type==1){
			$originPassword=random_string('nozero');
			$salt=random_string('alnum');
			$hashPassword=sha1(md5($originPassword).$salt);
			
			$this->db->set('password',$hashPassword)
			         ->set('salt',$salt)
			         ->insert('user');
		}elseif($type==2){
			$this->db->where('id',$userId)
			         ->update('user');
		}
		
		if($this->db->affected_rows()==1) returnAjaxData(200,'success',['originPassword'=>$originPassword]);
		else returnAjaxData(500,'Database error');
	}


	public function toDelete()
	{
		$userId=inputPost('userId',0,1);
		
		if($userId==$this->nowUserId){
			returnAjaxData(400,'Now user');
		}

		$this->db->delete('user',['id'=>$userId]);

		if($this->db->affected_rows()==1){
			//$logContent='删除用户|'.$id;
			//$this->Log_model->create('用户',$logContent);
			returnAjaxData(200,'success');
		}else{
			returnAjaxData(500,'Database error');
		}
	}


	public function toResetPwd()
	{
		$auth=$this->safe->checkAuth('api',substr($this->input->server('HTTP_REFERER'),strpos($this->input->server('HTTP_REFERER'),base_url())+strlen(base_url())));
		if($auth!=true) returnAjaxData(403002,'No permission');

		$userId=inputPost('userId',0,1);
		
		if($userId==$this->nowUserId){			
			returnAjaxData(400,'Now user');
		}
		
		// 获取用户名
		$query1=$this->db->select('user_name,nick_name')
		                 ->get_where('user',['id'=>$userId]);
		if($query1->num_rows()!=1){
			returnAjaxData(1,"no User");
		}else{
			$list=$query1->result_array();
			$userName=$list[0]['user_name'];
			$nickName=$list[0]['nick_name'];
		}

		// 生成初始密码
		$originPwd=random_string('nozero');
		$salt=random_string('alnum');
		$hashPwd=sha1(md5($originPwd).$salt);

		$this->db->where('id',$userId)
		         ->where('user_name',$userName)
		         ->update('user',['password'=>$hashPwd,'salt'=>$salt]);

		if($this->db->affected_rows()==1){
			$logContent='重置密码|'.$userId.'|'.$userName;
			$this->Log_model->create('用户',$logContent,$this->nowUserName);
			returnAjaxData(200,"success",['originPwd'=>$originPwd,'userName'=>$userName,'nickName'=>$nickName]);
		}else{
			returnAjaxData(500,'Database error');
		}
	}
	

	public function toUpdateStatus()
	{
		$auth=$this->safe->checkAuth('api',substr($this->input->server('HTTP_REFERER'),strpos($this->input->server('HTTP_REFERER'),base_url())+strlen(base_url())));
		if($auth!=true) returnAjaxData(403002,'No permission');

		$userId=inputPost('userId',0,1);
		$status=inputPost('status',0,1);
		
		if($userId==$this->nowUserId){			
			returnAjaxData(400,'Now user');
		}

		$this->db->where('id',$userId)
		         ->update('user',['status'=>$status]);

		if($this->db->affected_rows()==1){
			returnAjaxData(200,'success');
		}else{
			returnAjaxData(500,'Failed to update status');
		}
	}
}

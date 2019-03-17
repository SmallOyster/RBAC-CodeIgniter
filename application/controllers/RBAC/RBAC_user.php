<?php
/**
* @name 生蚝科技RBAC开发框架-C-RBAC-用户
* @author Jerry Cheung <master@xshgzs.com>
* @since 2018-02-08
* @version 2019-03-16
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class RBAC_user extends CI_Controller {
	
	public $sessPrefix;
	public $nowUserID;
	public $nowUserName;
	public $API_PATH;
	
	function __construct()
	{
		parent::__construct();

		$this->safe->checkPermission();

		$this->API_PATH=$this->setting->get('apiPath');
		$this->sessPrefix=$this->safe->getSessionPrefix();
		$this->nowUserID=$this->session->userdata($this->sessPrefix.'userID');
		$this->nowUserName=$this->session->userdata($this->sessPrefix.'userName');
		$this->load->helper('string');
	}


	public function toList()
	{
		$this->safe->checkPermission();
		$this->ajax->makeAjaxToken();

		$query=$this->db->query("SELECT * FROM user");
		$list=$query->result_array();

		$this->load->view('admin/user/list',['list'=>$list]);
	}


	public function add()
	{
		$this->ajax->makeAjaxToken();

		$this->load->view('admin/user/add',[]);
	}


	public function toAdd()
	{
		$this->ajax->checkAjaxToken(inputPost('token',0,1));

		$userName=inputPost('userName',0,1);
		$nickName=inputPost('nickName',0,1);
		$phone=inputPost('phone',0,1);
		$email=inputPost('email',0,1);
		$roleID=inputPost('roleID',0,1);
		$status=1;
		
		// 检查用户名手机邮箱是否已存在
		$sql1="SELECT id FROM user WHERE user_name=?";
		$query1=$this->db->query($sql1,[$userName]);
		if($query1->num_rows()!=0){
			returnAjaxData(1,"have UserName");
		}
		$sql2="SELECT id FROM user WHERE phone=?";
		$query2=$this->db->query($sql2,[$phone]);
		if($query2->num_rows()!=0){
			returnAjaxData(2,"have Phone");
		}
		$sql3="SELECT id FROM user WHERE email=?";
		$query3=$this->db->query($sql3,[$email]);
		if($query3->num_rows()!=0){
			returnAjaxData(3,"have Email");
		}

		$originPwd=random_string('nozero');
		$salt=random_string('alnum');
		$hashSalt=md5($salt);
		$hashPwd=sha1($originPwd.$hashSalt);

		$sql="INSERT INTO user(user_name,nick_name,password,salt,phone,email,role_id,status) VALUES (?,?,?,?,?,?,?,?)";
		$query=$this->db->query($sql,[$userName,$nickName,$hashPwd,$salt,$phone,$email,$roleID,$status]);

		if($this->db->affected_rows()==1){
			$data['originPwd']=$originPwd;
			returnAjaxData(200,"success",$data);
		}else{
			returnAjaxData(500,"failed to Insert");
		}
	}

	
	public function edit($userID)
	{
		$this->ajax->makeAjaxToken();

		$query=$this->db->query("SELECT * FROM user WHERE id=?",[$userID]);

		if($query->num_rows()!=1){
			header("Location:".base_url());
		}

		$list=$query->result_array();

		$this->load->view('admin/user/edit',['userID'=>$userID,'info'=>$list[0]]);
	}


	public function toEdit()
	{
		$this->ajax->checkAjaxToken(inputPost('token',0,1));

		$userID=inputPost('userID',0,1);
		$userName=inputPost('userName',0,1);
		$nickName=inputPost('nickName',0,1);
		$phone=inputPost('phone',0,1);
		$email=inputPost('email',0,1);
		$roleID=inputPost('roleID',0,1);
		$nowTime=date("Y-m-d H:i:s");
		
		$sql="UPDATE user SET user_name=?,nick_name=?,phone=?,email=?,role_id=?,update_time=? WHERE id=?";
		$query=$this->db->query($sql,[$userName,$nickName,$phone,$email,$roleID,$nowTime,$userID]);

		if($this->db->affected_rows()==1){
			returnAjaxData(200,"success");
		}else{
			returnAjaxData(0,"failed to Update");
		}
	}


	public function toDelete()
	{
		$token=inputPost('token');
		$this->ajax->checkAjaxToken(inputPost('token',0,1));

		$id=inputPost('id',0,1);
		
		if($id==$this->nowUserID){
			returnAjaxData(400,"now User");
		}

		$sql="DELETE FROM user WHERE id=?";
		$query=$this->db->query($sql,[$id]);

		if($this->db->affected_rows()==1){
			$logContent='删除用户|'.$id;
			//$this->Log_model->create('用户',$logContent);
			returnAjaxData(200,"success");
		}else{
			returnAjaxData(1,"failed to Delete");
		}
	}


	public function toResetPwd()
	{
		$this->ajax->checkAjaxToken(inputPost('token',0,1));

		$id=inputPost('id',0,1);
		
		if($id==$this->nowUserID){			
			returnAjaxData(400,'now User');
		}
		
		// 获取用户名
		$sql1="SELECT user_name,nick_name FROM user WHERE id=?";
		$query1=$this->db->query($sql1,[$id]);
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
		$hashSalt=md5($salt);
		$hashPwd=sha1($originPwd.$hashSalt);

		$sql2="UPDATE user SET password=?,salt=? WHERE id=? AND user_name=?";
		$query2=$this->db->query($sql2,[$hashPwd,$salt,$id,$userName]);

		if($this->db->affected_rows()==1){
			$logContent='重置密码|'.$id.'|'.$userName;
			$this->Log_model->create('用户',$logContent,$this->nowUserName);
			returnAjaxData(200,"success",['originPwd'=>$originPwd,'userName'=>$userName,'nickName'=>$nickName]);
		}else{
			returnAjaxData(2,"failed to Reset");
		}
	}
	

	public function toUpdateStatus()
	{
		$this->ajax->checkAjaxToken(inputPost('token',0,1));

		$id=inputPost('id',0,1);
		$status=inputPost('status',0,1);
		
		if($id==$this->nowUserID){			
			returnAjaxData(400,'now User');
		}

		$sql="UPDATE user SET status=? WHERE id=?";
		$query=$this->db->query($sql,[$status,$id]);

		if($this->db->affected_rows()==1){
			returnAjaxData(200,"success");
		}else{
			returnAjaxData(1,"failed to Update Status");
		}
	}
}

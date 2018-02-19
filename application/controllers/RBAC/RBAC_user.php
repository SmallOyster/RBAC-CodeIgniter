<?php
/**
* @name C-RBAC-用户
* @author SmallOysyer <master@xshgzs.com>
* @since 2018-02-08
* @version V1.0 2018-02-19
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class RBAC_user extends CI_Controller {
	
	public $allMenu;
	public $sessPrefix;
	public $nowUserName;
	
	function __construct()
	{
		parent::__construct();
		$this->load->helper('string');
		$this->load->library(array('Ajax'));

		$this->allMenu=$this->RBAC_model->getAllMenuByRole("1");
		$this->sessPrefix=$this->safe->getSessionPrefix();
		$this->nowUserName="super";
	}


	public function list()
	{
		$query=$this->db->query("SELECT * FROM user");
		$list=$query->result_array();

		$this->load->view('user/list',['list'=>$list,"navData"=>$this->allMenu]);
	}


	public function add()
	{
		$this->load->view('user/add',["navData"=>$this->allMenu]);
	}


	public function toAdd()
	{
		$token=$this->input->post('token');
		$this->ajax->checkAjaxToken($token);

		$userName=$this->input->post('userName');
		$nickName=$this->input->post('nickName');
		$phone=$this->input->post('phone');
		$email=$this->input->post('email');
		$roleID=$this->input->post('roleID');
		$status=1;
		
		$originPwd=random_string('nozero');
		$salt=random_string('alnum');
		$hashSalt=md5($salt);
		$hashPwd=sha1($originPwd.$hashSalt);

		$sql="INSERT INTO user(user_name,nick_name,password,salt,phone,email,role_id,status) VALUES (?,?,?,?,?,?,?,?)";
		$query=$this->db->query($sql,[$userName,$nickName,$hashPwd,$salt,$phone,$email,$roleID,$status]);

		if($this->db->affected_rows()==1){
			$data['originPwd']=$originPwd;
			$ret=$this->ajax->returnData("200","success",$data);
			die($ret);
		}else{
			$ret=$this->ajax->returnData("1","insertFailed");
			die($ret);
		}
	}

	
	public function edit($userID)
	{
		$this->ajax->makeAjaxToken();

		$query=$this->db->query("SELECT * FROM user WHERE id=?",[$userID]);

		if($query->num_rows()!=1){
			header("Location:".site_url());
		}

		$list=$query->result_array();

		$this->load->view('user/edit',["navData"=>$this->allMenu,'userID'=>$userID,'info'=>$list[0]]);
	}


	public function toEdit()
	{
		$token=$this->input->post('token');
		$this->ajax->checkAjaxToken($token);

		$userID=$this->input->post('userID');
		$userName=$this->input->post('userName');
		$nickName=$this->input->post('nickName');
		$phone=$this->input->post('phone');
		$email=$this->input->post('email');
		$roleID=$this->input->post('roleID');
		$nowTime=date("Y-m-d H:i:s");
		
		$sql="UPDATE user SET user_name=?,nick_name=?,phone=?,email=?,role_id=?,update_time=? WHERE id=?";
		$query=$this->db->query($sql,[$userName,$nickName,$phone,$email,$roleID,$nowTime,$userID]);

		if($this->db->affected_rows()==1){
			$ret=$this->ajax->returnData("200","success");
			die($ret);
		}else{
			$ret=$this->ajax->returnData("1","updateFailed");
			die($ret);
		}
	}


	public function toDel()
	{
		$token=$this->input->post('token');
		$this->ajax->checkAjaxToken($token);

		$id=$this->input->post('id');

		$sql="DELETE FROM user WHERE id=?";
		$query=$this->db->query($sql,[$id]);

		if($this->db->affected_rows()==1){
			$logContent='删除用户|'.$id;
			$this->Log_model->create('用户',$logContent,$this->nowUserName);
			$ret=$this->ajax->returnData("200","success");
			die($ret);
		}else{
			$ret=$this->ajax->returnData("1","deleteFailed");
			die($ret);
		}
	}


	public function toResetPwd()
	{
		$token=$this->input->post('token');
		$this->ajax->checkAjaxToken($token);

		$id=$this->input->post('id');

		// 获取用户名
		$sql1="SELECT user_name,nick_name FROM user WHERE id=?";
		$query1=$this->db->query($sql1,[$id]);
		if($query1->num_rows()!=1){
			$ret=$this->ajax->returnData("1","noUser");
			die($ret);
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

		$sql2="UPDATE user SET password=?,salt=?,status=2 WHERE id=? AND user_name=?";
		$query2=$this->db->query($sql2,[$hashPwd,$salt,$id,$userName]);

		if($this->db->affected_rows()==1){
			$logContent='重置密码|'.$id.'|'.$userName;
			$this->Log_model->create('用户',$logContent,$this->nowUserName);
			$ret=$this->ajax->returnData("200","success",['originPwd'=>$originPwd,'userName'=>$userName,'nickName'=>$nickName]);
			die($ret);
		}else{
			$ret=$this->ajax->returnData("2","resetFailed");
			die($ret);
		}
	}
}

<?php
/**
 * @name C-用户
 * @author SmallOysyer <master@xshgzs.com>
 * @since 2018-02-19
 * @version V1.0 2018-02-22
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
	
	
	public function reg()
	{
		$this->ajax->makeAjaxToken();

		$this->load->view('user/reg');
	}
	
	
	public function toReg()
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

		$sql="INSERT INTO user(user_name,nick_name,password,salt,role_id,phone,email) VALUES (?,?,?,?,?,?,?)";
		$query=$this->db->query($sql,[$userName,$nickName,$hashPwd,$salt,$roleID,$phone,$email]);

		if($this->db->affected_rows()==1){
			$ret=$this->ajax->returnData("200","success");
			die($ret);
		}else{
			$ret=$this->ajax->returnData("0","regFailed");
			die($ret);
		}
	}
}

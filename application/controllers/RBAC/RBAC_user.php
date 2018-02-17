<?php
/**
* @name C-RBAC-用户
* @author SmallOysyer <master@xshgzs.com>
* @since 2018-02-08
* @version V1.0 2018-02-17
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class RBAC_user extends CI_Controller {
	
	public $allMenu;
	public $sessPrefix;
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('RBAC_model');
		$this->load->helper('string');
		$this->load->library(array('Ajax'));

		$this->allMenu=$this->RBAC_model->getAllMenuByRole("1");
		$this->sessPrefix=$this->safe->getSessionPrefix();
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
		$realName=$this->input->post('realName');
		$phone=$this->input->post('phone');
		$email=$this->input->post('email');
		$roleID=$this->input->post('roleID');
		$status=1;
		
		$originPwd=random_string('nozero');
		$salt=random_string('alnum');
		$hashSalt=md5($salt);
		$hashPwd=sha1($originPwd.$hashSalt);

		$sql="INSERT INTO user(user_name,real_name,password,salt,phone,email,role_id,status) VALUES (?,?,?,?,?,?,?,?)";
		$query=$this->db->query($sql,[$userName,$realName,$hashPwd,$salt,$phone,$email,$roleID,$status]);

		if($this->db->affected_rows()==1){
			$data['originPwd']=$originPwd;
			$ret=$this->ajax->returnData("200","success",$data);
			die($ret);
		}else{
			$ret=$this->ajax->returnData("1","insertFailed");
			die($ret);
		}
	}

	
	public function list()
	{
		$query=$this->db->query("SELECT * FROM user");
		$list=$query->result_array();

		$this->load->view('user/list',['list'=>$list,"navData"=>$this->allMenu]);
	}


	public function toDel()
	{
		$token=$this->input->post('token');
		$this->ajax->checkAjaxToken($token);

		$id=$this->input->post('id');

		$sql="DELETE FROM user WHERE id=?";
		$query=$this->db->query($sql,[$id]);

		if($this->db->affected_rows()==1){
			$ret=$this->ajax->returnData("200","success");
			die($ret);
		}else{
			$ret=$this->ajax->returnData("1","deleteFailed");
			die($ret);
		}
	}
}

<?php
/**
* @name C-RBAC-菜单
* @author SmallOysyer <master@xshgzs.com>
* @since 2018-02-17
* @version V1.0 2018-02-17
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class RBAC_menu extends CI_Controller {

	public $allMenu;
	public $sessPrefix;
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('RBAC_model');
		$this->load->library(array('Ajax'));

		$this->allMenu=$this->RBAC_model->getAllMenuByRole("1");
		$this->sessPrefix=$this->safe->getSessionPrefix();
	}


	public function add($fatherID)
	{
		$this->ajax->makeAjaxToken();
		
		if($fatherID==0){
			$fatherName="主菜单";
			$fatherIcon="home";
		}else{
			$query=$this->db->query("SELECT name,icon FROM menu WHERE id=?",[$fatherID]);		
			
			if($query->num_rows()==1){
				$info=$query->result_array();
				$fatherName=$info[0]['name'];
				$fatherIcon=$info[0]['icon'];
			}else{
				header('Location:'.site_url('index'));
			}
		}
		
		$this->load->view('sys/menu/add',["navData"=>$this->allMenu,'fatherID'=>$fatherID,'fatherName'=>$fatherName,'fatherIcon'=>$fatherIcon]);
	}


	public function toAdd()
	{
		$token=$this->input->post('token');
		$this->ajax->checkAjaxToken($token);

		$fatherID=$this->input->post('fatherID');
		$name=$this->input->post('name');
		$icon=$this->input->post('icon');
		$url=$this->input->post('url');
		
		$sql="INSERT INTO menu(father_id,name,icon,url) VALUES (?,?,?,?)";
		$query=$this->db->query($sql,[$fatherID,$name,$icon,$url]);

		if($this->db->affected_rows()==1){
			$ret=$this->ajax->returnData("200","success");
			die($ret);
		}else{
			$ret=$this->ajax->returnData("1","insertFailed");
			die($ret);
		}
	}

	
	public function list()
	{
		$list=$this->RBAC_model->getAllMenu();

		$this->ajax->makeAjaxToken();
		$this->load->view('sys/menu/list',["navData"=>$this->allMenu,'list'=>$list]);
	}


	public function toDel()
	{
		$token=$this->input->post('token');
		$this->ajax->checkAjaxToken($token);

		$id=$this->input->post('id');

		$sql1="DELETE FROM menu WHERE id=?";
		$query1=$this->db->query($sql1,[$id]);
		
		if($this->db->affected_rows()==1){
			$sql2="DELETE FROM role_permission WHERE menu_id=?";
		$query2=$this->db->query($sql2,[$id]);
		$ret=$this->ajax->returnData("200","success");
			die($ret);
		}else{
			$ret=$this->ajax->returnData("1","deleteFailed");
			die($ret);
		}
	}
}

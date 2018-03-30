<?php
/**
* @name C-RBAC-菜单
* @author SmallOysyer <master@xshgzs.com>
* @since 2018-02-17
* @version V1.0 2018-03-29
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class RBAC_menu extends CI_Controller {

	public $allMenu;
	public $sessPrefix;
	public $nowUserID;
	public $nowUserName;
	
	function __construct()
	{
		parent::__construct();
		$this->load->library(array('Ajax'));

		$this->sessPrefix=$this->safe->getSessionPrefix();
		$roleID=$this->session->userdata($this->sessPrefix."roleID");
		$this->allMenu=$this->RBAC_model->getAllMenuByRole($roleID);
		
		$this->nowUserID=$this->session->userdata($this->sessPrefix.'userID');
		$this->nowUserName=$this->session->userdata($this->sessPrefix.'userName');
	}


	public function toList()
	{
		$this->safe->checkPermission();
		$this->ajax->makeAjaxToken();
		
		$list=$this->RBAC_model->getAllMenu();
		$this->load->view('admin/sys/menu/list',["navData"=>$this->allMenu,'list'=>$list]);
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
		
		$this->load->view('admin/sys/menu/add',['fatherID'=>$fatherID,'fatherName'=>$fatherName,'fatherIcon'=>$fatherIcon]);
	}


	public function toAdd()
	{
		$token=$this->input->post('token');
		$this->ajax->checkAjaxToken($token);

		$fatherID=$this->input->post('fatherID');
		$name=$this->input->post('name');
		$icon=$this->input->post('icon');
		$uri=$this->input->post('uri');
		
		if(substr($uri,0,13)=='show/jumpout/'){
			$jumpToURL=urlencode(substr(12));
			$uri='show/jumpout'.$jumpToURL;
		}
		
		$sql="INSERT INTO menu(father_id,name,icon,uri) VALUES (?,?,?,?)";
		$query=$this->db->query($sql,[$fatherID,$name,$icon,$uri]);

		if($this->db->affected_rows()==1){
			$ret=$this->ajax->returnData("200","success");
			die($ret);
		}else{
			$ret=$this->ajax->returnData("0","insertFailed");
			die($ret);
		}
	}

	
	public function edit($menuID)
	{
		$this->ajax->makeAjaxToken();

		// 获取菜单信息
		$query1=$this->db->query("SELECT * FROM menu WHERE id=?",[$menuID]);
		$list1=$query1->result_array();
		$info=$list1[0];
		$fatherID=$info['father_id'];

		if($query1->num_rows()!=1){
			header("Location:".site_url());
		}

		// 获取父菜单名称和Icon
		if($fatherID==0){
			$fatherName="主菜单";
			$fatherIcon="home";
		}else{
			$query2=$this->db->query("SELECT * FROM menu WHERE id=?",[$fatherID]);
			$list2=$query2->result_array();
			$fatherInfo=$list2[0];
			$fatherName=$fatherInfo['name'];
			$fatherIcon=$fatherInfo['icon'];
		}

		$this->load->view('admin/sys/menu/edit',['menuID'=>$menuID,'info'=>$info,'fatherName'=>$fatherName,'fatherIcon'=>$fatherIcon]);
	}


	public function toEdit()
	{
		$token=$this->input->post('token');
		$this->ajax->checkAjaxToken($token);

		$menuID=$this->input->post('menuID');
		$name=$this->input->post('name');
		$icon=$this->input->post('icon');
		$uri=$this->input->post('uri');
		$nowTime=date("Y-m-d H:i:s");
	
		if(substr($uri,0,13)=='show/jumpout/'){
			$jumpToURL=urlencode(substr($uri,13));
			$uri='show/jumpout/'.$jumpToURL;
		}
		
		$sql="UPDATE menu SET name=?,icon=?,uri=?,update_time=? WHERE id=?";
		$query=$this->db->query($sql,[$name,$icon,$uri,$nowTime,$menuID]);

		if($this->db->affected_rows()==1){
			$ret=$this->ajax->returnData("200","success");
			die($ret);
		}else{
			$ret=$this->ajax->returnData("0","updateFailed");
			die($ret);
		}
	}


	public function toDelete()
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
			$ret=$this->ajax->returnData("0","deleteFailed");
			die($ret);
		}
	}
}

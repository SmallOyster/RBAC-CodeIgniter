<?php
/**
* @name 生蚝科技RBAC开发框架-C-RBAC-菜单
* @author Jerry Cheung <master@xshgzs.com>
* @since 2018-02-17
* @version 2019-03-22
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class RBAC_menu extends CI_Controller {

	public $sessPrefix;
	public $nowUserId;
	public $nowUserName;
	public $API_PATH;
	
	function __construct()
	{
		parent::__construct();

		$this->safe->checkPermission();

		$this->API_PATH=$this->setting->get('apiPath');
		$this->sessPrefix=$this->safe->getSessionPrefix();
		$this->nowUserId=$this->session->userdata($this->sessPrefix.'userId');
		$this->nowUserName=$this->session->userdata($this->sessPrefix.'userName');
	}


	public function toList()
	{
		$this->safe->checkPermission();
		$this->ajax->makeAjaxToken();
		
		$list=$this->RBAC_model->getAllMenu();
		$this->load->view('admin/menu/list',['list'=>$list]);
	}


	public function add($fatherId)
	{
		$this->ajax->makeAjaxToken();
		
		if($fatherId==0){
			$fatherName="主菜单";
			$fatherIcon="home";
		}else{
			$query=$this->db->query("SELECT name,icon FROM menu WHERE id=?",[$fatherId]);		
			
			if($query->num_rows()==1){
				$info=$query->result_array();
				$fatherName=$info[0]['name'];
				$fatherIcon=$info[0]['icon'];
			}else{
				header('Location:'.base_url('index'));
			}
		}
		
		$this->load->view('admin/menu/add',['fatherId'=>$fatherId,'fatherName'=>$fatherName,'fatherIcon'=>$fatherIcon]);
	}


	public function toAdd()
	{
		$this->ajax->checkAjaxToken(inputPost('token',0,1));

		$fatherId=inputPost('fatherId',0,1);
		$name=inputPost('name',0,1);
		$icon=inputPost('icon',0,1);
		$uri=inputPost('uri',0,1);
		
		if(substr($uri,0,13)=='show/jumpout/'){
			$jumpToURL=urlencode(substr($uri,13));
			$uri='show/jumpout/'.$jumpToURL;
		}
		
		$sql="INSERT INTO menu(father_id,name,icon,uri) VALUES (?,?,?,?)";
		$query=$this->db->query($sql,[$fatherId,$name,$icon,$uri]);

		if($this->db->affected_rows()==1){
			returnAjaxData(200,"success");
		}else{
			returnAjaxData(1,"failed to Insert");
		}
	}

	
	public function edit($menuId)
	{
		$this->ajax->makeAjaxToken();

		// 获取菜单信息
		$query1=$this->db->query("SELECT * FROM menu WHERE id=?",[$menuId]);
		$list1=$query1->result_array();
		$info=$list1[0];
		$fatherId=$info['father_id'];

		if($query1->num_rows()!=1){
			header("Location:".base_url());
		}

		// 获取父菜单名称和Icon
		if($fatherId==0){
			$fatherName="主菜单";
			$fatherIcon="home";
		}else{
			$query2=$this->db->query("SELECT * FROM menu WHERE id=?",[$fatherId]);
			$list2=$query2->result_array();
			$fatherInfo=$list2[0];
			$fatherName=$fatherInfo['name'];
			$fatherIcon=$fatherInfo['icon'];
		}

		$this->load->view('admin/menu/edit',['menuId'=>$menuId,'info'=>$info,'fatherName'=>$fatherName,'fatherIcon'=>$fatherIcon]);
	}


	public function toEdit()
	{
		$this->ajax->checkAjaxToken(inputPost('token',0,1));

		$menuId=inputPost('menuId',0,1);
		$name=inputPost('name',0,1);
		$icon=inputPost('icon',0,1);
		$uri=inputPost('uri',0,1);
		$nowTime=date("Y-m-d H:i:s");
	
		if(substr($uri,0,13)=='show/jumpout/'){
			$jumpParam=substr($uri,13);
			$loc=strpos($jumpParam,"/");
			$jumpToURL=substr($jumpParam,0,$loc);
			$jumpName=substr($jumpParam,$loc+1);
			$uri='show/jumpout/'.$jumpToURL.'/'.$jumpName;
		}
		
		$sql="UPDATE menu SET name=?,icon=?,uri=?,update_time=? WHERE id=?";
		$query=$this->db->query($sql,[$name,$icon,$uri,$nowTime,$menuId]);

		if($this->db->affected_rows()==1){
			returnAjaxData(200,"success");
		}else{
			returnAjaxData(1,"failed to Update");
		}
	}


	public function toDelete()
	{
		$this->ajax->checkAjaxToken(inputPost('token',0,1));

		$id=inputPost('id',0,1);
		$this->db->delete('menu',['id'=>$id]);
		
		if($this->db->affected_rows()==1){
			$this->db->delete('role_permission',['menu_id'=>$id]);
			returnAjaxData(200,"success");
		}else{
			returnAjaxData(1,"failed to Delete");
		}
	}
}

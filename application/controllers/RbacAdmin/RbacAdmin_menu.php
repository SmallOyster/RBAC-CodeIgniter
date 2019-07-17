<?php
/**
* @name 生蚝科技RBAC开发框架-C-RBAC-菜单
* @author Jerry Cheung <master@xshgzs.com>
* @since 2018-02-17
* @version 2019-06-12
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class RbacAdmin_menu extends CI_Controller {

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
		$this->load->view('admin/menu/list',['list'=>$this->rbac->getAllMenu()]);
	}


	public function toAdd()
	{
		$fatherId=inputPost('fatherId',0,1);
		$type=inputPost('type',0,1);
		$name=inputPost('name',0,1);
		$icon=inputPost('icon',0,1);
		$uri=inputPost('uri',1,1);
		
		if(substr($uri,0,13)=='show/jumpout/'){
			$jumpToURL=urlencode(substr($uri,13));
			$uri='show/jumpout/'.$jumpToURL;
		}
		
		$sql='INSERT INTO menu(father_id,type,name,icon,uri) VALUES (?,?,?,?,?)';
		$query=$this->db->query($sql,[$fatherId,$type,$name,$icon,$uri]);

		if($this->db->affected_rows()==1){
			returnAjaxData(200,"success");
		}else{
			returnAjaxData(1,"failed to Insert");
		}
	}

	
	public function edit()
	{
		$menuId=inputGet('menuId',0);

		// 获取菜单信息
		$query1=$this->db->get_where('menu',['id'=>$menuId]);
		$list1=$query1->result_array();
		$info=$list1[0];
		$fatherId=$info['father_id'];

		if($query1->num_rows()!=1){
			header("location:".base_url());
		}

		// 获取父菜单名称和Icon
		if($fatherId==0){
			$fatherName="主菜单";
			$fatherIcon="home";
		}else{
			$query2=$this->db->get_where('menu',['id'=>$fatherId]);
			$list2=$query2->result_array();
			$fatherInfo=$list2[0];
			$fatherName=$fatherInfo['name'];
			$fatherIcon=$fatherInfo['icon'];
		}

		$this->load->view('admin/menu/edit',['menuId'=>$menuId,'info'=>$info,'fatherName'=>$fatherName,'fatherIcon'=>$fatherIcon]);
	}


	public function toEdit()
	{
		$menuId=inputPost('menuId',0,1);
		$type=inputPost('type',0,1);
		$name=inputPost('name',0,1);
		$icon=inputPost('icon',0,1);
		$uri=inputPost('uri',1,1);
		$nowTime=date("Y-m-d H:i:s");
	
		if(substr($uri,0,13)=='show/jumpout/'){
			$jumpParam=substr($uri,12);
			$loc=strpos($jumpParam,"/");
			$jumpToURL=substr($jumpParam,0,$loc);
			$jumpName=substr($jumpParam,$loc+1);
			$uri='show/jumpout/'.$jumpToURL.'/'.$jumpName;
		}
		
		$this->db->where('id', $menuId);
		$this->db->update('menu',['type'=>$type,'name'=>$name,'icon'=>$icon,'uri'=>$uri,'update_time'=>$nowTime]);

		if($this->db->affected_rows()==1){
			returnAjaxData(200,"success");
		}else{
			returnAjaxData(1,"failed to Update");
		}
	}


	public function toDelete()
	{
		$id=inputPost('id',0,1);
		$this->db->delete('menu',['id'=>$id]);
		
		if($this->db->affected_rows()==1){
			$this->db->delete('role_permission',['menu_id'=>$id]);
			returnAjaxData(200,"success");
		}else{
			returnAjaxData(1,"Failed to delete");
		}
	}
}

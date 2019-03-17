<?php
/**
* @name 生蚝科技RBAC开发框架-C-RBAC-角色
* @author Jerry Cheung <master@xshgzs.com>
* @since 2018-02-08
* @version 2019-03-17
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class RBAC_role extends CI_Controller {

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
	}


	public function toList()
	{
		$this->safe->checkPermission();
		$this->ajax->makeAjaxToken();
		
		$query=$this->db->query("SELECT * FROM role");
		$list=$query->result_array();

		$this->load->view('admin/role/list',['list'=>$list]);
	}


	public function add()
	{
		$this->ajax->makeAjaxToken();
		
		$this->load->view('admin/role/add',[]);
	}


	public function toAdd()
	{
		$this->ajax->checkAjaxToken(inputPost('token',0,1));

		$name=inputPost('name',0,1);
		$remark=inputPost('remark',0,1);
		
		$sql="INSERT INTO role(name,remark) VALUES (?,?)";
		$query=$this->db->query($sql,[$name,$remark]);

		if($this->db->affected_rows()==1){
			returnAjaxData(200,"success");
		}else{
			returnAjaxData(1,"failed to Insert");
		}
	}


	public function edit($roleId)
	{
		$this->ajax->makeAjaxToken();

		$query=$this->db->query("SELECT * FROM role WHERE id=?",[$roleId]);
		
		if($query->num_rows()!=1){
			header("Location: ".base_url('/'));
		}

		$list=$query->result_array();
		$info=$list[0];
		
		$this->load->view('admin/role/edit',['roleId'=>$roleId,'info'=>$info]);
	}


	public function toEdit()
	{
		$this->ajax->checkAjaxToken(inputPost('token',0,1));

		$name=inputPost('name',0,1);
		$remark=inputPost('remark',1,1);
		$roleId=inputPost('roleId',0,1);
		$nowTime=date("Y-m-d H:i:s");

		$sql="UPDATE role SET name=?,remark=?,update_time=? WHERE id=?";
		$query=$this->db->query($sql,[$name,$remark,$nowTime,$roleId]);

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

		$sql="DELETE FROM role WHERE id=?";
		$query=$this->db->query($sql,[$id]);

		if($this->db->affected_rows()==1){
			$logContent='删除角色|'.$id;
			$this->Log_model->create('角色',$logContent);
			returnAjaxData(200,"success");
		}else{
			returnAjaxData(1,"failed to Delete");
		}
	}
	
	
	public function setPermission($roleId,$roleName){
		$this->ajax->makeAjaxToken();
		
		$list=$this->RBAC_model->getAllMenu();

		$permissions=$this->RBAC_model->getAllPermissionByRole($roleId);
		$permissions=implode(",",$permissions);
		
		$this->load->view('admin/role/setPermission',['list'=>$list,'roleId'=>$roleId,"permission"=>$permissions,'roleName'=>$roleName]);
	}


	public function toSetPermission()
	{
		$this->ajax->checkAjaxToken(inputPost('token',0,1));

		$roleId=inputPost('roleId',0,1);
		$menuIds=inputPost('menuIds',0,1);
		$menuIds=explode(",",$menuIds);

		// 清空旧权限
		$sql1="DELETE FROM role_permission WHERE role_id='".$roleId."'";
		$query1=$this->db->simple_query($sql1);
		if($query1!==TRUE){
			returnAjaxData(1,"failed to Truncate Role Permission");
		}

		$totalMenu=count($menuIds);
		$sql2="INSERT INTO role_permission(role_id,menu_id) VALUES ";
		$data=array();

		// 循环写入添加权限的SQL语句
		foreach($menuIds as $id){
			$sql2.="(?,?),";
			array_push($data,$roleId,$id);
		}

		$sql2=substr($sql2,0,strlen($sql2)-1);
		$query2=$this->db->query($sql2,$data);

		if($this->db->affected_rows()==$totalMenu){
			returnAjaxData(200,"success");
		}else{
			returnAjaxData(500,"quantity Mismatch");
		}
	}


	public function toSetDefaultRole()
	{
		$this->ajax->checkAjaxToken(inputPost('token',0,1));

		$id=inputPost('id',0,1);
		$sql1="UPDATE role SET is_default=1 WHERE id=?";
		$query1=$this->db->query($sql1,[$id]);

		if($this->db->affected_rows()==1){
			$sql2="UPDATE role SET is_default=0 WHERE id<>?";
			$query2=$this->db->query($sql2,[$id]);
			
			returnAjaxData(200,"success");
		}else{
			returnAjaxData(1,"failed to Set Default Role");
		}
	}
}

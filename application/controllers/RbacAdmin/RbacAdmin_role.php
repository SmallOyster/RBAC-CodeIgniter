<?php
/**
* @name 生蚝科技RBAC开发框架-C-RBAC-角色
* @author Jerry Cheung <master@xshgzs.com>
* @since 2018-02-08
* @version 2019-07-28
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class RbacAdmin_role extends CI_Controller {

	public $sessPrefix;
	public $nowUserName;
	public $API_PATH;
	
	function __construct()
	{
		parent::__construct();

		$this->safe->checkAuth();

		$this->API_PATH=$this->setting->get('apiPath');
		$this->sessPrefix=$this->safe->getSessionPrefix();
		$this->nowUserName=$this->session->userdata($this->sessPrefix.'userName');
		$this->load->helper('string');
	}


	public function toList()
	{
		$this->load->view('admin/role/list');
	}


	public function toOperate()
	{
		$auth=$this->safe->checkAuth('api','admin/role/list');
		if($auth!=true) returnAjaxData(403002,'No permission');

		$vaildFields=['name','remark'];
		$type=inputPost('type',0,1);
		$roleId=inputPost('roleId',0,1);
		$data=inputPost('data',0,1);
		
		foreach($data as $field=>$value){
			// 检查 此字段是否可修改
			if(!in_array($field,$vaildFields)){
				returnAjaxData(4001,'Invaild Field',[$field]);
			}
			
			// 检查 值是否为空
			if($value==null || $value==''){
				returnAjaxData(4002,'Data cannot be null',[$field]);
			}
			
			$this->db->set($field,$value);
		}
		
		$originPassword='';
		if($type==1){
			$roleId=strtoupper(random_string('alnum',6));

			$this->db->set('id',$roleId)
			         ->insert('role');
		}elseif($type==2){
			$this->db->where('id',$roleId)
			         ->update('role');
		}
		
		if($this->db->affected_rows()==1) returnAjaxData(200,'success');
		else returnAjaxData(500,'Database error');
	}


	public function add()
	{
		$this->load->view('admin/role/add');
	}


	public function toAdd()
	{
		$name=inputPost('name',0,1);
		$remark=inputPost('remark',1,1);
		$roleId=strtoupper(random_string('alnum',6));
		
		$sql="INSERT INTO role(id,name,remark) VALUES (?,?,?)";
		$query=$this->db->query($sql,[$roleId,$name,$remark]);

		if($this->db->affected_rows()==1){
			returnAjaxData(200,"success");
		}else{
			returnAjaxData(1,"failed to Insert");
		}
	}


	public function edit()
	{
		$roleId=inputGet('id',0);
		$query=$this->db->get_where('role',['id'=>$roleId]);
		
		if($query->num_rows()!=1) header("location: ".base_url());
		else $this->load->view('admin/role/edit',['roleId'=>$roleId,'info'=>$query->first_row('array')]);
	}


	public function toEdit()
	{
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
		$this->db->delete('role',['id'=>inputPost('id',0,1)]);

		if($this->db->affected_rows()==1){
			$logContent='删除角色|'.$id;
			$this->Log_model->create('角色',$logContent);
			returnAjaxData(200,"success");
		}else{
			returnAjaxData(1,"failed to Delete");
		}
	}
	
	
	public function setPermission()
	{
		$roleId=inputGet('id',0);
		$roleName=inputGet('name',0);

		$list=$this->rbac->getAllMenu();

		$permissions=$this->rbac->getAllPermissionByRole($roleId);
		$permissions=implode(",",$permissions);
		
		$this->load->view('admin/role/setPermission',['list'=>$list,'roleId'=>$roleId,"permission"=>$permissions,'roleName'=>$roleName]);
	}


	public function toSetPermission()
	{
		$roleId=inputPost('roleId',0,1);
		$menuIds=inputPost('menuIds',0,1);
		$menuIds=explode(",",$menuIds);

		// 清空旧权限
		$this->db->delete('role_permission',['role_id'=>$roleId]);

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
		$id=inputPost('id',0,1);
		
		$this->db->where('id',$id);
		$this->db->update('role',['is_default'=>1]);

		if($this->db->affected_rows()==1){
			$this->db->where('id !=',$id);
			$this->db->update('role',['is_default'=>0]);
			
			returnAjaxData(200,"success");
		}else{
			returnAjaxData(1,"Failed to set default role");
		}
	}
}

<?php
/**
* @name C-RBAC-角色
* @author SmallOysyer <master@xshgzs.com>
* @since 2018-02-08
* @version V1.0 2018-03-14
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class RBAC_role extends CI_Controller {

	public $allMenu;
	public $sessPrefix;
	public $nowUserID;
	public $nowUserName;
	
	function __construct()
	{
		parent::__construct();
		$this->load->library(array('Ajax'));
		$this->load->helper('string');

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
		
		$query=$this->db->query("SELECT * FROM role");
		$list=$query->result_array();

		$this->load->view('admin/role/list',['list'=>$list,"navData"=>$this->allMenu]);
	}


	public function add()
	{
		$this->ajax->makeAjaxToken();
		
		$this->load->view('admin/role/add',["navData"=>$this->allMenu]);
	}


	public function toAdd()
	{
		$token=$this->input->post('token');
		$this->ajax->checkAjaxToken($token);

		$name=$this->input->post('name');
		$remark=$this->input->post('remark');
		
		$sql="INSERT INTO role(name,remark) VALUES (?,?)";
		$query=$this->db->query($sql,[$name,$remark]);

		if($this->db->affected_rows()==1){
			$ret=$this->ajax->returnData("200","success");
			die($ret);
		}else{
			$ret=$this->ajax->returnData("0","insertFailed");
			die($ret);
		}
	}


	public function edit($roleID)
	{
		$this->ajax->makeAjaxToken();

		$query=$this->db->query("SELECT * FROM role WHERE id=?",[$roleID]);
		
		if($query->num_rows()!=1){
			header("Location: ".site_url('/'));
		}

		$list=$query->result_array();
		$info=$list[0];
		
		$this->load->view('admin/role/edit',["navData"=>$this->allMenu,'roleID'=>$roleID,'info'=>$info]);
	}


	public function toEdit()
	{
		$token=$this->input->post('token');
		$this->ajax->checkAjaxToken($token);

		$name=$this->input->post('name');
		$remark=$this->input->post('remark');
		$roleID=$this->input->post('roleID');
		$nowTime=date("Y-m-d H:i:s");

		$sql="UPDATE role SET name=?,remark=?,update_time=? WHERE id=?";
		$query=$this->db->query($sql,[$name,$remark,$nowTime,$roleID]);

		if($this->db->affected_rows()==1){
			$ret=$this->ajax->returnData("200","success");
			die($ret);
		}else{
			$ret=$this->ajax->returnData("0","updateFailed");
			die($ret);
		}
	}

	
	public function toDel()
	{
		$token=$this->input->post('token');
		$this->ajax->checkAjaxToken($token);

		$id=$this->input->post('id');

		$sql="DELETE FROM role WHERE id=?";
		$query=$this->db->query($sql,[$id]);

		if($this->db->affected_rows()==1){
			$logContent='删除角色|'.$id;
			$this->Log_model->create('角色',$logContent);
			$ret=$this->ajax->returnData("200","success");
			die($ret);
		}else{
			$ret=$this->ajax->returnData("0","deleteFailed");
			die($ret);
		}
	}
	
	
	public function setPermission($roleID,$roleName){
		$this->ajax->makeAjaxToken();
		
		$list=$this->RBAC_model->getAllMenu();

		$permissions=$this->RBAC_model->getAllPermissionByRole($roleID);
		$permissions=implode(",",$permissions);
		
		$this->load->view('admin/role/setPermission',["navData"=>$this->allMenu,'list'=>$list,'roleID'=>$roleID,"permission"=>$permissions,'roleName'=>$roleName]);
	}


	public function toSetPermission()
	{
		$token=$this->input->post('token');
		$this->ajax->checkAjaxToken($token);

		$roleID=$this->input->post('roleID');
		$menuIDs=$this->input->post('menuIDs');
		$menuIDs=explode(",",$menuIDs);

		// 清空旧权限
		$sql1="DELETE FROM role_permission WHERE role_id='".$roleID."'";
		$query1=$this->db->simple_query($sql1);
		if($query1!==TRUE){
			$ret=$this->ajax->returnData("0","truncateFailed");
			die($ret);
		}

		$totalMenu=count($menuIDs);
		$sql2="INSERT INTO role_permission(role_id,menu_id) VALUES ";
		$data=array();

		// 循环写入添加权限的SQL语句
		foreach($menuIDs as $id){
			$sql2.="(?,?),";
			array_push($data,$roleID,$id);
		}

		$sql2=substr($sql2,0,strlen($sql2)-1);
		$query2=$this->db->query($sql2,$data);

		if($this->db->affected_rows()==$totalMenu){
			$ret=$this->ajax->returnData("200","success");
			die($ret);
		}else{
			$ret=$this->ajax->returnData("1","quantityMismatch");
			die($ret);
		}
	}


	public function toSetDefaultRole()
	{
		$token=$this->input->post('token');
		$this->ajax->checkAjaxToken($token);

		$id=$this->input->post('id');
		$sql1="UPDATE role SET is_default=1 WHERE id=?";
		$query1=$this->db->query($sql1,[$id]);

		if($this->db->affected_rows()==1){
			$sql2="UPDATE role SET is_default=0 WHERE id<>?";
			$query2=$this->db->query($sql2,[$id]);
			
			$ret=$this->ajax->returnData("200","success");
			die($ret);
		}else{
			$ret=$this->ajax->returnData("0","setFailed");
			die($ret);
		}
	}
}

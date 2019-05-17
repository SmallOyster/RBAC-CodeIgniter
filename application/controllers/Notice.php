<?php
/**
 * @name 生蚝科技RBAC开发框架-C-通知
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-03-28
 * @version 2019-05-17
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Notice extends CI_Controller {

	public $sessPrefix;
	public $nowUserName;
	public $API_PATH;

	function __construct()
	{
		parent::__construct();
		
		$this->safe->checkPermission();

		$this->API_PATH=$this->setting->get('apiPath');
		$this->sessPrefix=$this->safe->getSessionPrefix();
		$this->nowUserName=$this->session->userdata($this->sessPrefix.'userName');
	}


	public function adminList()
	{
		$this->ajax->makeAjaxToken();
		$list=$this->Notice_model->get();
		
		$this->load->view('admin/notice/list',['list'=>$list]);
	}
	
	
	public function list()
	{
		$list=$this->Notice_model->get();
		
		$this->load->view('notice/list',['list'=>$list]);
	}


	public function publish()
	{
		$this->ajax->makeAjaxToken();
		
		$this->load->view('admin/notice/pub');
	}


	public function toPublish()
	{
		$this->ajax->checkAjaxToken(inputPost('token',0,1));
		
		$title=inputPost('title');
		$content=inputPost('content');
		
		$nowNickName=$this->session->userdata($this->sessPrefix.'nickName');
		$sql="INSERT INTO notice(title,content,publisher_id) VALUES (?,?,?)";
		$query=$this->db->query($sql,[$title,$content,$this->session->userdata($this->sessPrefix.'userId')]);

		if($this->db->affected_rows()==1){
			returnAjaxData(200,"success");
		}else{
			returnAjaxData(1,"failed to Publish");
		}
	}
	
	
	public function toDelete()
	{
		$this->ajax->checkAjaxToken(inputPost('token',0,1));

		$id=inputPost('id');
		$this->db->delete('notice',['id'=>$id]);
		
		if($this->db->affected_rows()==1){
			returnAjaxData(200,"success");
		}else{
			returnAjaxData(1,"failed to Delete");
		}
	}
	
	
	public function showDetail($id)
	{
		$info=$this->Notice_model->get($id);
	 
		if($info==array()){
	 		header("Location:".base_url('/'));
	 	}
	 
		$this->load->view('notice/detail',['info'=>$info[0]]);
	}


	public function edit($id)
	{
		$info=$this->Notice_model->get($id);
	 
		if($info==array()){
	 		header("Location:".base_url('/'));
	 	}
	 
		$this->load->view('admin/notice/edit',['info'=>$info[0]]);
	}


	public function toEdit()
	{
		$this->ajax->checkAjaxToken(inputPost('token',0,1));
		
		$id=inputPost('id',0,1);
		$title=inputPost('title',0,1);
		$content=inputPost('content',0,1);
		
		$sql="UPDATE notice SET title=?,content=? WHERE id=?";
		$query=$this->db->query($sql,[$title,$content,$id]);

		if($this->db->affected_rows()==1){
			returnAjaxData(200,"success");
		}else{
			returnAjaxData(1,"failed to Update");
		}
	}
}

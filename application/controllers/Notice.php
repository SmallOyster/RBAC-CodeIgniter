<?php
/**
 * @name 生蚝科技RBAC开发框架-C-通知
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-03-28
 * @version 2019-02-23
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Notice extends CI_Controller {

	public $sessPrefix;
	public $nowUserID;
	public $nowUserName;
	public $API_PATH;

	function __construct()
	{
		parent::__construct();
		
		$this->safe->checkPermission();
		$this->sessPrefix=$this->safe->getSessionPrefix();

		$this->API_PATH=$this->setting->get('apiPath');
		$this->nowUserID=$this->session->userdata($this->sessPrefix.'userID');
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
		
		$this->load->view('admin/notice/pub',[]);
	}


	public function toPublish()
	{
		$token=$this->input->post('token');
		$this->ajax->checkAjaxToken($token);
		
		$title=$this->input->post('title');
		$content=$this->input->post('content');
		
		$nowNickName=$this->session->userdata($this->sessPrefix.'nickName');
		$sql="INSERT INTO notice(title,content,create_user) VALUES (?,?,?)";
		$query=$this->db->query($sql,[$title,$content,$nowNickName]);

		if($this->db->affected_rows()==1){
			$ret=$this->ajax->returnData("200","success");
			die($ret);
		}else{
			$ret=$this->ajax->returnData("0","publishFailed");
			die($ret);
		}
	}
	
	
	public function toDelete()
	{
		$token=$this->input->post('token');
		$this->ajax->checkAjaxToken($token);

		$id=$this->input->post('id');

		$sql1="DELETE FROM notice WHERE id=?";
		$query1=$this->db->query($sql1,[$id]);
		
		if($this->db->affected_rows()==1){
			$ret=$this->ajax->returnData("200","success");
			die($ret);
		}else{
			$ret=$this->ajax->returnData("0","deleteFailed");
			die($ret);
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
		$token=$this->input->post('token');
		$this->ajax->checkAjaxToken($token);
		
		$id=$this->input->post('id');
		$title=$this->input->post('title');
		$content=$this->input->post('content');
		
		$sql="UPDATE notice SET title=?,content=? WHERE id=?";
		$query=$this->db->query($sql,[$title,$content,$id]);

		if($this->db->affected_rows()==1){
			$ret=$this->ajax->returnData("200","success");
			die($ret);
		}else{
			$ret=$this->ajax->returnData("0","updateFailed");
			die($ret);
		}
	}
}

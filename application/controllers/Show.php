<?php
/**
* @name 生蚝科技RBAC开发框架-C-显示
* @author Jerry Cheung <master@xshgzs.com>
* @since 2018-02-06
* @version 2019-03-17
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class Show extends CI_Controller {

	public $sessPrefix;
	public $nowUserID;
	public $nowUserName;
	public $API_PATH;

	function __construct()
	{
		parent::__construct();
		
		$this->sessPrefix=$this->safe->getSessionPrefix();
		$this->API_PATH=$this->setting->get('apiPath');
		$this->nowUserID=$this->session->userdata($this->sessPrefix.'userID');
		$this->nowUserName=$this->session->userdata($this->sessPrefix.'userName');
	}


	public function blank()
	{
		$this->load->view('show/blank',[]);
	}
	
	
	public function list()
	{
		$query=$this->db->query("SELECT * FROM notice");
		$list=$query->result_array();
		
		$this->load->view('show/list',['list'=>$list]);
	}


	public function login()
	{
		$this->load->view('show/login');
	}
	

	public function jumpOut($url,$name=""){
		$url=urldecode($url);
		$this->load->view('show/jumpOut',['url'=>$url,'name'=>$name]);
	}
}

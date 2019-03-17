<?php
/**
 * @name 生蚝科技RBAC开发框架-C-Log日志
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-02-26
 * @version 2019-03-17
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Log extends CI_Controller {

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
		$this->ajax->makeAjaxToken();
		
		$query=$this->db->query("SELECT * FROM log",[]);
		$list=$query->result_array();
		
		$this->load->view('admin/sys/log/list',['list'=>$list]);
	}
	
	
	public function toTruncate()
	{
		$this->ajax->checkAjaxToken(inputPost('token',0,1));
		
		$pwd=inputPost('pwd',0,1);
		$userStatus=$this->user->validateUser($this->nowUserID,"",$pwd);
		
		if($userStatus!="200"){
			returnAjaxData(403,"invaild Password");
		}
		
		$this->db->truncate('log');
		
		$logInsert=$this->Log_model->create("日志","清空日志");
		
		if($logInsert==true){
			returnAjaxData(200,"success");
		}else{
			returnAjaxData(500,"failed to Truncate");
		}
	}
}

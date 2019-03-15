<?php
/**
 * @name 生蚝科技RBAC开发框架-C-Log日志
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-02-26
 * @version 2019-02-23
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
		$this->sessPrefix=$this->safe->getSessionPrefix();

		$this->API_PATH=$this->setting->get('apiPath');
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
		$token=$this->input->post('token');
		$this->ajax->checkAjaxToken($token);
		
		$pwd=$this->input->post('pwd');
		$userStatus=$this->User_model->validateUser($this->nowUserID,"",$pwd);
		
		if($userStatus!="200"){
			$ret=$this->ajax->returnData("1","invaildPwd");
			die($ret);
		}
		
		$sql1="DELETE FROM log";
		$query1=$this->db->query($sql1,[]);
		
		$logInsert=$this->Log_model->create("日志","清空日志");
		
		if($logInsert==TRUE){
			$ret=$this->ajax->returnData("200","success");
			die($ret);
		}else{
			$ret=$this->ajax->returnData("0","truncateFailed");
			die($ret);
		}
	}
}

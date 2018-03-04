<?php
/**
* @name C-显示
* @author SmallOysyer <master@xshgzs.com>
* @since 2018-02-06
* @version V1.0 2018-02-25
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class Show extends CI_Controller {

	public $allMenu; 
	public $sessPrefix;
	public $nowUserID;
	public $nowUserName;

	function __construct()
	{
		parent::__construct();
		
		$this->sessPrefix=$this->safe->getSessionPrefix();
		$roleID=$this->session->userdata($this->sessPrefix."roleID");
		$this->allMenu=$this->RBAC_model->getAllMenuByRole($roleID);
		
		$this->nowUserID=$this->session->userdata($this->sessPrefix.'userID');
		$this->nowUserName=$this->session->userdata($this->sessPrefix.'userName');
	}


	public function blank()
	{
		$this->load->view('show/blank',["navData"=>$this->allMenu]);
	}


	public function login()
	{
		$this->load->view('show/login');
	}
	

	public function jumpOut($url){
		$url=urldecode($url);
		$this->load->view('show/jumpOut',["url"=>$url,"navData"=>$this->allMenu]);
	}
}

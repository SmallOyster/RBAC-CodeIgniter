<?php
/**
 * @name C-基本
 * @author SmallOysyer <master@xshgzs.com>
 * @since 2018-02-07
 * @version V1.0 2018-03-26
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	public $allMenu;
	public $sessPrefix;
	public $nowUserID;
	public $nowUserName;

	function __construct()
	{
		parent::__construct();
		
		$this->sessPrefix=$this->safe->getSessionPrefix();
		$roleID=$this->session->userdata($this->sessPrefix.'roleID');
		$this->allMenu=$this->RBAC_model->getAllMenuByRole($roleID);

		$this->nowUserID=$this->session->userdata($this->sessPrefix.'userID');
		$this->nowUserName=$this->session->userdata($this->sessPrefix.'userName');
	}


	public function index()
	{
		if($this->nowUserID==NULL){
			header('Location:'.site_url('user/logout'));
		}
		
		$latestNotice=$this->Notice_model->get(0,'index');
		
		$this->load->view('index',['allNotice'=>$latestNotice]);
	}
}

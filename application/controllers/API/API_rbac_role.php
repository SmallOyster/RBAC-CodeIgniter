<?php
/**
* @name 生蚝科技RBAC开发框架-A-角色API
* @author Jerry Cheung <master@xshgzs.com>
* @since 2018-02-17
* @version 2019-03-22
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class API_rbac_role extends CI_Controller {

	public $sessPrefix;
	
	function __construct()
	{
		parent::__construct();

		$this->sessPrefix=$this->safe->getSessionPrefix();
	}


	public function getAllRole(){
		$this->ajax->checkAjaxToken(inputPost('token',0,1));

		$list=$this->RBAC_model->getAllRole();
		$data['list']=$list;

		returnAjaxData(200,'Success',$data);
	}
}

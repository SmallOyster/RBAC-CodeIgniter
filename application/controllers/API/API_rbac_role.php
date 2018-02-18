<?php
/**
* @name A-RBAC-角色API
* @author SmallOysyer <master@xshgzs.com>
* @since 2018-02-17
* @version V1.0 2018-02-18
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class API_rbac_role extends CI_Controller {

	public $sessPrefix;
	
	function __construct()
	{
		parent::__construct();
		$this->load->library(array('Ajax'));

		$this->sessPrefix=$this->safe->getSessionPrefix();
	}


	public function getAllRole(){
		$token=$this->input->post('token');
		$this->ajax->checkAjaxToken($token);

		$list=$this->RBAC_model->getAllRole();
		$data['list']=$list;

		die($this->ajax->returnData('200','Success',$data));
	}
}

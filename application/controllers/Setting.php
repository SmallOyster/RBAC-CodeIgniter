<?php
/**
 * @name C-系统配置
 * @author SmallOysyer <master@xshgzs.com>
 * @since 2018-03-03
 * @version V1.0 2018-03-03
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends CI_Controller {

	public $allMenu;
	public $sessPrefix;
	public $nowUserID;
	public $nowUserName;

	function __construct()
	{
		parent::__construct();
		
		$this->load->library(array('Ajax'));
		
		$this->sessPrefix=$this->safe->getSessionPrefix();
		$roleID=$this->session->userdata($this->sessPrefix."roleID");
		$this->allMenu=$this->RBAC_model->getAllMenuByRole($roleID);

		$this->nowUserID=$this->session->userdata($this->sessPrefix.'userID');
		$this->nowUserName=$this->session->userdata($this->sessPrefix.'userName');
	}


	public function list()
	{
		$this->ajax->makeAjaxToken();
		
		$this->load->view('admin/sys/setting/list',["navData"=>$this->allMenu]);
	}


	public function toSave()
	{
		$token=$this->input->post('token');
		$this->ajax->checkAjaxToken($token);
		
		$name=$this->input->post('name');
		$value=$this->input->post('value');
		$allConfig=$this->config->item('allConfig');

		if(!array_key_exists($name,$allConfig)){
			$ret=$this->ajax->returnData("1","noSetting");
			die($ret);
		}else{
			$this->config->set_item('index_page','item_value');
			$this->config->set_item($name,$value);
			$ret=$this->ajax->returnData("200".$name.$value,"success1");
			die($ret);
		}
	}
}

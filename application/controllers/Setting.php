<?php
/**
 * @name C-系统配置
 * @author SmallOysyer <master@xshgzs.com>
 * @since 2018-03-03
 * @version V1.0 2018-03-06
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
		$list=$this->Setting_model->list();
		
		$this->load->view('admin/sys/setting/list',["navData"=>$this->allMenu,'list'=>$list]);
	}


	public function toSave()
	{
		$token=$this->input->post('token');
		$this->ajax->checkAjaxToken($token);
		
		$name=$this->input->post('name');
		$value=$this->input->post('value');
		
		$saveStatus=$this->Setting_model->save($name,$value);
		
		if($saveStatus==TRUE){
			$ret=$this->ajax->returnData("200","success");
			die($ret);
		}else{
			$ret=$this->ajax->returnData("0","saveFailed");
			die($ret);
		}
	}
}

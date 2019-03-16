<?php
/**
 * @name 生蚝科技RBAC开发框架-C-系统配置
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-03-03
 * @version 2019-03-16
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends CI_Controller {

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
		$list=$this->Setting_model->list();
		
		$this->load->view('admin/sys/setting/list',['list'=>$list]);
	}


	public function toSave()
	{
		$this->ajax->checkAjaxToken(inputPost('token',0,1));
		
		$name=inputPost('name',0,1);
		$value=inputPost('value',0,1);
		
		$saveStatus=$this->Setting_model->save($name,$value);
		
		if($saveStatus==true){
			returnAjaxData(200,"success");
		}else{
			returnAjaxData(1,"failed to save");
		}
	}
}

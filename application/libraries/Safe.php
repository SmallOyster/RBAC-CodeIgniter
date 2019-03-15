<?php
/**
* @name 生蚝科技RBAC开发框架-L-安全类
* @author Jerry Cheung <master@xshgzs.com>
* @since 2018-01-18
* @version 2019-03-15
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class Safe {

	protected $_CI;
	public $sessPrefix;

	function __construct(){
		$this->_CI =& get_instance();
		$this->_CI->load->helper(array('url'));
		$this->_CI->load->model(array('Setting_model'));

		$this->sessPrefix=$this->_CI->Setting_model->get('sessionPrefix');
	}

	/**
	 * 获取系统全局Session名称前缀
	 * @return String 全局Session名前缀
	 */
	public function getSessionPrefix()
	{
		return $this->sessPrefix;
	}
	
	
	/**
	 * 判断当前页面是否有权限访问
	 */
	public function checkPermission()
	{
		// 判断Ajax请求
		if($this->_CI->input->is_ajax_request()){
			return;
		}
		
		$roleID=$this->_CI->session->userdata($this->sessPrefix."roleID");
		$allPermission=$this->_CI->RBAC_model->getAllPermissionByRole($roleID);
		$menuID=$this->_CI->RBAC_model->getMenuID($this->_CI->uri->uri_string());
		
		if($roleID<1){
			die('<script>alert("抱歉！您暂无权限访问此页面！\n请从正常途径访问系统！");window.location.href="'.base_url('/').'";</script>');
		}elseif($menuID==null){
			// 当前页面不存在于数据库中
			return;
		}elseif(in_array($menuID,$allPermission) && $allPermission!=array()){
			// 有权限
			return;
		}else{
			die('<script>alert("抱歉！您暂无权限访问此页面！\n请从正常途径访问系统！");window.location.href="'.base_url('/').'";</script>');
		}
	}
}

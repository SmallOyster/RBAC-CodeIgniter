<?php
/**
* @name 生蚝科技RBAC开发框架-L-安全类
* @author Jerry Cheung <master@xshgzs.com>
* @since 2018-01-18
* @version 2019-05-25
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
		
		$roleId=$this->_CI->session->userdata($this->sessPrefix."roleId");
		$allPermission=$this->_CI->RBAC_model->getAllPermissionByRole($roleId);
		$menuId=$this->_CI->RBAC_model->getMenuId($this->_CI->uri->uri_string());
		
		if(strlen($roleId)!=6){
			die('<script>alert("抱歉！您暂无权限访问此页面！\n请从正常途径访问系统！");window.location.href="'.base_url().'?redirect='.urlencode($_SERVER['REQUEST_URI']).'";</script>');
		}elseif($menuId==null){
			// 当前页面不存在于数据库中
			return;
		}elseif(in_array($menuId,$allPermission) && $allPermission!=array()){
			// 有权限
			return;
		}else{
			die('<script>alert("抱歉！您暂无权限访问此页面！\n请从正常途径访问系统！");window.location.href="'.base_url().'?redirect='.urlencode($_SERVER['REQUEST_URI']).'";</script>');
		}
	}
	
	
	/**
  * 判断当前页面是否有权限访问
 	*/
	public function checkAuth($method="",$uri="")
	{
		if($this->_CI->session->userdata($this->sessPrefix.'userId')<1){
			if($method!='api'){
				logout();
			}else{
				return false;
			}
		}

		if($uri=='') $uri=uri_string();
		$menuPermission=array();
		$roleId=$this->_CI->session->userdata($this->sessPrefix.'roleId');

		$this->_CI->db->select('menu_id');
		$this->_CI->db->where('role_id',$roleId);
		$query=$this->_CI->db->get('role_permission');
		$list=$query->result_array();

		foreach($list as $value){
			array_push($menuPermission,$value['menu_id']);
		}

		$this->_CI->db->like('uri',$uri);
		$query=$this->_CI->db->get('menu');

		if($query->num_rows()!=1){
			return false;
		}else{
			$info=$query->result_array();
			$menuId=$info[0]['id'];

			if(in_array($menuId,$menuPermission)){
				return true;
			}else{
				if($method!='api'){
					logout();
				}else{
					return false;
				}
			}
		}
	}
}

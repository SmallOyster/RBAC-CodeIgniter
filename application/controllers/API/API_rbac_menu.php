<?php
/**
* @name 生蚝科技RBAC开发框架-A-菜单API
* @author Jerry Cheung <master@xshgzs.com>
* @since 2018-02-17
* @version 2019-03-22
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class API_rbac_menu extends CI_Controller {

	public $sessPrefix;
	
	function __construct()
	{
		parent::__construct();

		$this->sessPrefix=$this->safe->getSessionPrefix();
	}


	public function getAllMenuForZtree(){
		$this->ajax->checkAjaxToken(inputPost('token',0,1));
		
		$query=$this->db->query("SELECT * FROM menu");
		$list=$query->result_array();
		
		// 获取现有权限
		$roleId=inputPost('roleId',0,1);
		if($roleId!==NULL){
			$allPermission=$this->RBAC_model->getAllPermissionByRole($roleId);
		}else{
			$allPermission=array();
		}
		
		$i=0;
		foreach($list as $info){
			$rtn[$i]['id']=(int)$info['id'];
			$rtn[$i]['pId']=(int)$info['father_id'];
			$rtn[$i]['name']=urlencode($info['name']);
			
			// 勾选现有权限
			if(in_array($rtn[$i]['id'],$allPermission)){
				$rtn[$i]['checked']=true;
			}
			
			$i++;
		}

		$rtn = urldecode(json_encode($rtn));
		die($rtn);
	}
}

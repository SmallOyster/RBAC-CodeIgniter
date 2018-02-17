<?php
/**
* @name A-RBAC-菜单API
* @author SmallOysyer <master@xshgzs.com>
* @since 2018-02-17
* @version V1.0 2018-02-17
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class API_rbac_menu extends CI_Controller {

	public $sessPrefix;
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('RBAC_model');
		$this->load->library(array('Ajax'));

		$this->sessPrefix=$this->safe->getSessionPrefix();
	}


	public function getSimpleAllMenu(){
		$token=$this->input->post('token');
		$this->ajax->checkAjaxToken($token);

		$query=$this->db->query("SELECT * FROM menu");
		$list=$query->result_array();

		$i=0;
		foreach($list as $info){
			$rtn[$i]['id']=(int)$info['id'];
			$rtn[$i]['pId']=(int)$info['father_id'];
			$rtn[$i]['name']=urlencode($info['name']);
			$i++;
		}

		$rtn = urldecode(json_encode($rtn));
		die($rtn);
	}
}

<?php
/**
* @name M-系统配置
* @author SmallOysyer <master@xshgzs.com>
* @since 2018-03-05
* @version V1.0 2018-03-05
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class Setting_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}


	public function save($name,$value)
	{
		$nowTime=date("Y-m-d H:i:s");
		
		$sql="UPDATE setting SET value=?,update_time=? WHERE name=?";
		$query=$this->db->query($sql,[$value,$nowTime,$name]);

		if($this->db->affected_rows()==1){
			$this->Log_model->create("系统","修改系统配置：".$name."|".$value);
			return true;
		}else{
			return false;
		}
	}
	
	
	public function get($name)
	{
		$sql="SELECT value FROM setting WHERE name=?";
		$query=$this->db->query($sql,[$name]);
		
		if($query->num_rows()==1){
			$list=$query->result_array();
			return $list[0]['value'];
		}else{
			return NULL;
		}
	}
	
	
	public function list()
	{
		$sql="SELECT * FROM setting";
		$query=$this->db->query($sql,[]);
		$list=$query->result_array();
		return $list;
	}

}

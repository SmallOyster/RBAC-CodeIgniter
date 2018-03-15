<?php
/**
* @name M-Log日志
* @author SmallOysyer <master@xshgzs.com>
* @since 2018-02-18
* @version V1.0 2018-03-14
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class Log_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}


	/**
	 * 向数据库写入系统操作记录
	 * @param String 操作类型
	 * @param String 操作内容
	 */
	public function create($type,$content)
	{
		$ip=$this->input->ip_address();
		$name=$this->session->userdata($this->sessPrefix.'userName');
		
		$sql="INSERT INTO log(type,content,user_name,create_ip) VALUES(?,?,?,?)";
		$query=$this->db->query($sql,[$type,$content,$name,$ip]);

		if($this->db->affected_rows()==1){
			return true;
		}else{
			return false;
		}
	}

}
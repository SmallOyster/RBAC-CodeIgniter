<?php
/**
 * @name M-用户
 * @author SmallOysyer <master@xshgzs.com>
 * @since 2018-02-20
 * @version V1.0 2018-02-22
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	}


	public function getUserInfoByUserName($userName)
	{
		$sql="SELECT * FROM user WHERE user_name=?";
		$query=$this->db->query($sql,[$userName]);

		if($query->num_rows()!=1){
			return array();
		}
		
		$info=$query->result_array();
		return $info[0];
	}


	public function validateUser($userID=0,$userName="",$pwd)
	{
		$sql1="SELECT salt,password,status FROM user WHERE id=? OR user_name=?";
		$query1=$this->db->query($sql1,[$userID,$userName]);
		
		if($query1->num_rows()!=1){
			return "404";
		}
		
		$info=$query1->result_array();
		$status=$info[0]['status'];
		$salt=$info[0]['salt'];
		$pwd_indb=$info[0]['password'];
		$hashSalt=md5($salt);

		if(sha1($pwd.$hashSalt)==$pwd_indb){
			return "200";
		}elseif($status==0){
			return "403";
		}else{
			return "0";
		}
	}
}

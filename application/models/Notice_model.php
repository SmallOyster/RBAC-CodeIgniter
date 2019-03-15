<?php
/**
* @name 生蚝科技RBAC开发框架-M-通知
* @author Jerry Cheung <master@xshgzs.com>
* @since 2018-03-25
* @version 2018-03-30
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class Notice_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}
	
	
	/**
	 * 获取通知
	 * @param INT    通知ID (0代表获取所有)
	 * @param String 获取类型
	 * @return Array 所有系统配置
	 */
	public function get($id=0,$type="")
	{
		$sql='SELECT * FROM notice WHERE 1=1 ';
		
		if($id>0){
			$sql.='AND id=? ';
			$data=array($id);
		}elseif($id==0){
			$sql.='';		
			$data=array();
			
			// 判断是否为特殊获取类型
			if($type=='nav'){
				// 导航栏里仅显示7天内的所有通知
				$limitTime=time()-604800;
				$limitTime=date('Y-m-d H:i:s',$limitTime);
				$sql.='AND create_time>=? ORDER BY create_time DESC';
				array_push($data,$limitTime);
			}elseif($type=="index"){
				// 首页的通知栏仅显示最新的5条
				$sql.='ORDER BY create_time DESC LIMIT 0,5';
			}
		}else{
			return array();
		}
		
		$query=$this->db->query($sql,$data);
		$list=$query->result_array();
		
		return $list;
	}
}

<?php
/**
* @name 生蚝科技RBAC开发框架-M-通知
* @author Jerry Cheung <master@xshgzs.com>
* @since 2018-03-25
* @version 2018-05-13
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class Notice_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}
	
	
	/**
	 * 获取通知
	 * @param INT    通知Id (0代表获取所有)
	 * @param String 获取类型
	 * @return Array 通知列表
	 */
	public function get($id=0,$type="")
	{
		$sql='SELECT a.*,b.nick_name AS publisherName FROM notice a,user b WHERE 1=1 AND a.publisher_id=b.id ';
		
		if($id>0){
			$sql.='AND a.id=? ';
			$data=array($id);
		}elseif($id==0){
			$sql.='';		
			$data=array();
			
			// 判断是否为特殊获取类型
			if($type=='nav'){
				// 导航栏里仅显示7天内的所有通知
				$limitTime=time()-604800;
				$limitTime=date('Y-m-d H:i:s',$limitTime);
				$sql.='AND a.create_time>=? ORDER BY a.create_time DESC';
				array_push($data,$limitTime);
			}elseif($type=="index"){
				// 首页的通知栏仅显示最新的5条
				$sql.='ORDER BY a.create_time DESC LIMIT 0,5';
			}
		}else{
			return array();
		}
		
		$query=$this->db->query($sql,$data);
		$list=$query->result_array();
		
		return $list;
	}
}

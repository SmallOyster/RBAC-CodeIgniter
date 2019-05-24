<?php
/**
 * @name 生蚝科技RBAC开发框架-C-API-角色
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-01-19
 * @version 2019-05-24
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class API_Role extends CI_Controller {

	public $sessPrefix;

	public function __construct()
	{
		parent::__construct();
		$this->sessPrefix=$this->safe->getSessionPrefix();
	}

	public function getRoleInfo($roleId=0)
	{
		if(strlen($roleId)==6) $this->db->where('id',$roleId);
		$query=$this->db->get('role');

		returnAjaxData(200,"success",['list'=>$query->result_array()]);
	}


	public function getUserMenu()
	{
		$roleId=$this->session->userdata($this->sessPrefix."roleId");
		
		if(strlen($roleId)==6) returnAjaxData(200,"success",['treeData'=>self::getAllMenuByRole($roleId)]);
		else returnAjaxData(403,"failed To Auth");
	}


	private function getFatherMenuByRole($roleId,$type=0)
	{
		$sql='SELECT a.menu_id,b.* FROM role_permission a,menu b WHERE a.role_id=? AND a.menu_id=b.id AND b.father_id=0 ';
		if($type>0) $sql.=' AND b.type='.$type;
		$query=$this->db->query($sql,[$roleId]);
		$list=$query->result_array();

		return $list;
	}


	private function getChildMenuByRole($roleId,$fatherId,$type=0)
	{
		$sql='SELECT a.menu_id,b.* FROM role_permission a,menu b WHERE a.role_id=? AND a.menu_id=b.id AND b.father_id=? ';
		if($type>0) $sql.=' AND b.type='.$type;
		$query=$this->db->query($sql,[$roleId,$fatherId]);
		$list=$query->result_array();

		return $list;
	}


	private function getAllMenuByRole($roleId)
	{
		$allMenu=array();

		$fatherMenu_list=self::getFatherMenuByRole($roleId,1);
		$allMenu=$fatherMenu_list;
		$allMenu_total=count($allMenu);

		// 搜寻二级菜单
		for($i=0;$i<$allMenu_total;$i++){
			$fatherId=$allMenu[$i]['id'];
			$child_list=self::getChildMenuByRole($roleId,$fatherId,1);

			if($child_list==null){
			// 没有二级菜单
				$allMenu[$i]['hasChild']="0";
				$allMenu[$i]['child']=array();
			}else{
			// 有二级菜单
				$allMenu[$i]['hasChild']="1";
				$allMenu[$i]['child']=$child_list;

				// 二级菜单的数量
				$child_list_total=count($child_list);

				// 搜寻三级菜单
				for($j=0;$j<$child_list_total;$j++){
					$father2Id=$child_list[$j]['id'];
					$child2_list=self::getChildMenuByRole($roleId,$father2Id,1);

					if($child2_list==null){
						// 没有三级菜单
						$allMenu[$i]['child'][$j]['hasChild']="0";
						$allMenu[$i]['child'][$j]['child']=array();
					}else{
						// 有三级菜单
						$allMenu[$i]['child'][$j]['hasChild']="1";
						$allMenu[$i]['child'][$j]['child']=$child2_list;
					}
				}
			}
		}

		return $allMenu;
	}


	public function getRoleMenuForZtree($roleId=0)
	{
		$rtn=array();
		$allPermission=array();

		$menuQuery=$this->db->get('menu');
		$menuList=$menuQuery->result_array();

		$permissionQuery=$this->db->get_where('role_permission',['role_id'=>$roleId]);
		$permissionList=$permissionQuery->result_array();

		foreach($permissionList as $permissionInfo){
			array_push($allPermission,$permissionInfo['menu_id']);
		}

		foreach($menuList as $key=>$info){
			$rtn[$key]['id']=(int)$info['id'];
			$rtn[$key]['pId']=(int)$info['father_id'];
			$rtn[$key]['name']=$info['type']==1?urlencode($info['name']):($info['type']==2?'(按钮)'.urlencode($info['name']):'(接口)'.urlencode($info['name']));
			if(in_array($info['id'],$allPermission)) $rtn[$key]['checked']=true;
		}

		die(urldecode(json_encode($rtn)));
	}
}

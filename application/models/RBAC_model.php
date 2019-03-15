<?php
/**
 * @name 生蚝科技RBAC开发框架-M-RBAC
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-02-06
 * @version 2018-04-01
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class RBAC_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}


	/**
	 * 根据URI获取菜单ID
	 * @param String URI
	 * @return INT 菜单ID
	 */
	public function getMenuID($uri)
	{
		$sql="SELECT id FROM menu WHERE uri=?";
		$query=$this->db->query($sql,[$uri]);
		
		if($query->num_rows()!=1){
			return null;
		}
		
		$list=$query->result_array();
		$info=$list[0];
		
		return $info['id'];
	}
	
	
	/**
	 * 根据角色获取所有权限的菜单ID
	 * @param String 角色ID
	 * @return Array 菜单ID
	 */
	public function getAllPermissionByRole($roleID)
	{
		$rtn=array();
		
		$sql="SELECT menu_id FROM role_permission WHERE role_id=?";
		$query=$this->db->query($sql,[$roleID]);
		$list=$query->result_array();
		
		foreach($list as $info){
			array_push($rtn,$info['menu_id']);
		}
		
		return $rtn;
	}


	/**
	 * 获取所有角色
	 * @return Array 所有角色信息
	 */
	public function getAllRole()
	{
		$query=$this->db->query("SELECT * FROM role");
		$list=$query->result_array();
		return $list;
	}
	
	
	/**
	 * 根据角色获取所有父菜单
	 * @param String 角色ID
	 * @return Array 父菜单信息
	 */
	public function getFatherMenuByRole($roleID)
	{
		$sql='SELECT a.*,b.* FROM role_permission a,menu b WHERE a.role_id=? AND a.menu_id=b.id AND b.father_id="0"';
		$query=$this->db->query($sql,[$roleID]);
		$list=$query->result_array();
		
		return $list;	
	}
	
	
	/**
	 * 根据角色和父菜单ID获取子菜单信息
	 * @param String 角色ID
	 * @param String 父菜单ID
	 * @return Array 子菜单信息
	 */
	public function getChildMenuByRole($roleID,$fatherID)
	{
		$sql="SELECT a.*,b.* FROM role_permission a,menu b WHERE a.role_id=? AND a.menu_id=b.id AND b.father_id=?";
		$query=$this->db->query($sql,[$roleID,$fatherID]);
		$list=$query->result_array();
		
		return $list;
	}
	
	
	/**
	 * 根据角色获取所有菜单详细信息
	 * @param String 角色ID
	 * @return Array 菜单详细信息
	 */
	public function getAllMenuByRole($roleID)
	{
		$allMenu=array();
		
		$fatherMenu_list=$this->RBAC_model->getFatherMenuByRole($roleID);
		$allMenu=$fatherMenu_list;
		$allMenu_total=count($allMenu);
		
		// 搜寻二级菜单
		for($i=0;$i<$allMenu_total;$i++){
			$fatherID=$allMenu[$i]['id'];
			$child_list=$this->RBAC_model->getChildMenuByRole($roleID,$fatherID);
			
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
					$father2ID=$child_list[$j]['id'];
					$child2_list=$this->RBAC_model->getChildMenuByRole($roleID,$father2ID);

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
	

	/**
	 * 获取所有父菜单
	 * @return Array 父菜单信息
	 */
	public function getFatherMenu()
	{
		$sql='SELECT * FROM menu WHERE father_id="0"';
		$query=$this->db->query($sql);
		$list=$query->result_array();
		
		return $list;	
	}
	
	
	/**
	 * 根据父菜单ID获取子菜单
	 * @param String 父菜单ID
	 * @return Array 子菜单信息
	 */
	public function getChildMenu($fatherID)
	{
		$sql="SELECT * FROM menu WHERE father_id=?";
		$query=$this->db->query($sql,[$fatherID]);
		$list=$query->result_array();
		
		return $list;
	}
	
	
	/**
	 * 获取所有菜单
	 * @return Array 菜单详细信息
	 */
	public function getAllMenu()
	{
		$allMenu=array();
		
		$fatherMenu_list=$this->RBAC_model->getFatherMenu();
		$allMenu=$fatherMenu_list;
		$allMenu_total=count($allMenu);
		
		// 搜寻二级菜单
		for($i=0;$i<$allMenu_total;$i++){
			$fatherID=$allMenu[$i]['id'];
			$child_list=$this->RBAC_model->getChildMenu($fatherID);
			
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
					$father2ID=$child_list[$j]['id'];
					$child2_list=$this->RBAC_model->getChildMenu($father2ID);

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
}
	 
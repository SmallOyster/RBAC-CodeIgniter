<?php
/**
 * @name M-RBAC
 * @author SmallOysyer <master@xshgzs.com>
 * @since 2018-02-06
 * @version V1.0 2018-02-20
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class RBAC_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}


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


	public function getAllRole()
	{
		$query=$this->db->query("SELECT * FROM role");
		$list=$query->result_array();
		return $list;
	}
	
	
	public function getFatherMenuByRole($roleID)
	{
		$sql='SELECT a.*,b.* FROM role_permission a,menu b WHERE a.role_id=? AND a.menu_id=b.id AND b.father_id="0"';
		$query=$this->db->query($sql,[$roleID]);
		$list=$query->result_array();
		
		return $list;	
	}
	
	
	public function getChildMenuByRole($roleID,$fatherID)
	{
		$sql="SELECT a.*,b.* FROM role_permission a,menu b WHERE a.role_id=? AND a.menu_id=b.id AND b.father_id=?";
		$query=$this->db->query($sql,[$roleID,$fatherID]);
		$list=$query->result_array();
		
		return $list;
	}
	
	
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
	

	public function getFatherMenu()
	{
		$sql='SELECT * FROM menu WHERE father_id="0"';
		$query=$this->db->query($sql);
		$list=$query->result_array();
		
		return $list;	
	}
	
	
	public function getChildMenu($fatherID)
	{
		$sql="SELECT * FROM menu WHERE father_id=?";
		$query=$this->db->query($sql,[$fatherID]);
		$list=$query->result_array();
		
		return $list;
	}
	
	
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
	 
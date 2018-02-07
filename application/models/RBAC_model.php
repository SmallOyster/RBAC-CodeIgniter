<?php
/**
* @name M-RBAC
* @author SmallOysyer <master@xshgzs.com>
* @since 2018-02-06
* @version V1.0-B180207 2018-02-07
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class RBAC_model extends CI_Model {
  
  public function __construct() {
    parent::__construct();
    $this->load->library(array("safe"));
  }
  
  
  public function validateUser($userName,$pwd)
  {
    $sql1="SELECT salt,password FROM user WHERE user_name=?";
    $query1=$this->db->query($sql1,[$userName]);
    
    if($query->num_rows()!=1){
      return "1";
    }
    
    $info=$query->result_array();
    $salt=$info[0]['salt'];
    $pwd_indb=$info[0]['password'];
    $hashPwd=$this->safe->validatePassword($pwd,$salt,$pwd_indb);
    
    if($hashPwd===TRUE){
      return "200";
    }else{
      return "2";
    }
  }
  
  
  public function getAllMenuByRole($roleID)
  {
    $sql="SELECT a.*,b.* FROM role_permission a,menu b WHERE a.role_id=? AND a.menu_id=b.id";
    $query=$this->db->query($sql,[$roleID]);
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
  
  
  public function getAllMenu()
  {
    $allMenu=array();
    
    $fatherMenu_list=$this->RBAC_model->getFatherMenuByRole("1");
    $allMenu=$fatherMenu_list;
    $allMenu_total=count($allMenu);
    
    // 搜寻二级菜单
    for($i=0;$i<$allMenu_total;$i++){
      $fatherID=$allMenu[$i]['id'];
      $child_list=$this->RBAC_model->getChildMenuByRole("1",$fatherID);
      
      if($child_list==null){
        // 没有二级菜单
        $allMenu[$i]['hasChild']="0";
      }else{
        // 有二级菜单
        $allMenu[$i]['hasChild']="1";
        $allMenu[$i]['child']=$child_list;

        // 二级菜单的数量
        $child_list_total=count($child_list);

        // 搜寻三级菜单
        for($j=0;$j<$child_list_total;$j++){
          $father2ID=$child_list[$j]['id'];
          $child2_list=$this->RBAC_model->getChildMenuByRole("1",$father2ID);

          if($child2_list==null){
            // 没有三级菜单
            $allMenu[$i]['child'][$j]['hasChild']="0";
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
   
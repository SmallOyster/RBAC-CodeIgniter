<?php
/**
* @name L-安全类
* @author SmallOysyer <master@xshgzs.com>
* @since 2018-01-18
* @version V1.0.1 2018-02-07
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class Safe{

  public $sessPrefix;

  function __construct(){
    $CI=& get_instance();
    $CI->load->helper(array('url'));
    $CI->load->library(array('session'));
    $CI->config->load('custom');

    $this->sessPrefix=$CI->config->item('sessionPrefix');
  }

  /**
  * ------------------------------
  * 获取系统全局Session名称前缀
  * ------------------------------
  * @return String 全局Session名前缀
  * ------------------------------
  **/
  public function get_session_prefix()
  {
    return $this->sessPrefix;
  }


  /**
  * ------------------------------
  * 创建AJAX-Token
  * ------------------------------
  * @return String AJAX-Token值
  * ------------------------------
  **/
  public function make_ajax_token()
  {
    $CI=& get_instance();
    
    // 设置AJAX-Token
    $timestamp=time();
    $hashTimestamp=md5($timestamp);
    $token=sha1(session_id().$hashTimestamp);
    
    $CI->session->set_userdata($this->sessPrefix.'AJAX_token',$token);
    
    return $token;
  }

  
  /**
  * ------------------------------
  * 校验AJAX-Token的有效性
  * ------------------------------
  * @param String 待校验的Token值
  * ------------------------------
  **/
  public function check_ajax_token($token)
  {
    $CI=& get_instance();
    if($token!=$CI->session->userdata($this->sessPrefix.'AJAX_token')){
      die('invaildToken');
    }
  }


  /**
   * 检测密码有效性
   * @param  String $pwd  待检测的密码
   * @param  String $hash 密文
   * @param  String $salt 盐值
   * @return String       0无效,1有效
   */
  public function validatePassword($pwd,$hash,$salt)
  {
    $hashSalt=md5($salt);
    if(sha1($pwd.$hashSalt)==$hash){
      return "1";
    }else{
      return "0";
    }
  }
}
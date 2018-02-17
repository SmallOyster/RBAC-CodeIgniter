<?php
/**
* @name L-安全类
* @author SmallOysyer <master@xshgzs.com>
* @since 2018-01-18
* @version V1.0.1 2018-02-17
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class Safe {

	protected $_CI;
	public $sessPrefix;

	function __construct(){
		$this->_CI =& get_instance();
		$this->_CI->load->helper(array('url'));

		$this->sessPrefix=$this->_CI->config->item('sessionPrefix');
	}

	/**
	 * 获取系统全局Session名称前缀
	 * @return String 全局Session名前缀
	 */
	public function getSessionPrefix()
	{
		return $this->sessPrefix;
	}


	/**
	 * 检测密码有效性
	 * @param	String $pwd	待检测的密码
	 * @param	String $hash 密文
	 * @param	String $salt 盐值
	 * @return String			 0无效,1有效
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

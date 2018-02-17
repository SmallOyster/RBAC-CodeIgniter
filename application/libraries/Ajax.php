<?php
/**
* @name L-Ajax
* @author SmallOysyer <master@xshgzs.com>
* @since 2018-02-10
* @version V1.0.1 2018-02-17
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax {
	
	protected $_CI;
	public $sessPrefix;

	public function __construct()
	{
		$this->_CI =& get_instance();

		$this->sessPrefix=$this->_CI->config->item('sessionPrefix');
	}


	/**
	 * 返回JSON数据
	 * @param  string 状态码
	 * @param  string 待返回的数据
	 * @return JSON   已处理好的JSON数据
	 */
	public function returnData($code,$msg,$data=""){
		$ret=array('code'=>$code,'message'=>$msg,'data'=>$data);
		return json_encode($ret);
	}


	/**
	 * 显示AJAX-Token
	 * @return string Token名+值
	 */
	public function showAjaxToken(){
		return '"token":"'.$this->_CI->session->userdata($this->sessPrefix.'AJAX_token').'"';
	}


	/**
	 * 创建AJAX-Token
	 * @return String AJAX-Token值
	 */
	public function makeAjaxToken()
	{
		// 设置AJAX-Token
		$timestamp=time();
		$hashTimestamp=md5($timestamp);
		$token=sha1(session_id().$hashTimestamp);
		
		$this->_CI->session->set_userdata($this->sessPrefix.'AJAX_token',$token);
		
		return $token;
	}

	
	/**
	 * 校验AJAX-Token的有效性
	 * @param String 待校验的Token值
	 */
	public function checkAjaxToken($token)
	{
		if($token!=$this->_CI->session->userdata($this->sessPrefix.'AJAX_token')){
			die(self::returnData("0","invaildToken"));
		}
	}
}

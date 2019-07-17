<?php
/**
 * @name 生蚝科技RBAC开发框架-L-Ajax
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-02-10
 * @version 2019-06-11
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax {
	
	protected $_CI;
	public $sessPrefix;

	public function __construct()
	{
		$this->_CI =& get_instance();
	}
}

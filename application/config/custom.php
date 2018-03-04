<?php
/**
* @name 自定义全局配置文件
* @author SmallOysyer <master@xshgzs.com>
* @since 2018-01-19
* @version V1.0 2018-03-04
*/

defined('BASEPATH') OR exit('No direct script access allowed');

$config['allConfig'] = array(
	'sessionPrefix'=>'Session名称前缀',
	'systemName'=>'系统名称',
);

$config['sessionPrefix']='CI_RBAC_';
$config['systemName']='RBAC';
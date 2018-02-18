<?php
/**
* @name 全局路由
* @author CodeIgniter,SmallOyster
* @since 2018-02-06
* @version V1.0 2018-02-18
*/

defined('BASEPATH') OR exit('No direct script access allowed');


// System Default Routes
$route['default_controller'] = 'main/index';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;


// Custom Routes

/************* RBAC-API **************/
$route['api/getAllRole']='API/API_rbac_role/getAllRole';
$route['api/getAllMenuForZtree']='API/API_rbac_menu/getAllMenuForZtree';

/************* RBAC-Role **************/
$route['role/list']='RBAC/RBAC_role/list';
$route['role/toList']='RBAC/RBAC_role/toList';
$route['role/add']='RBAC/RBAC_role/add';
$route['role/toAdd']['POST']='RBAC/RBAC_role/toAdd';
$route['role/edit/(:num)/(:any)']['GET']='RBAC/RBAC_role/edit/$1/$2';
$route['role/toEdit']['POST']='RBAC/RBAC_role/toEdit';
$route['role/toDel']['POST']='RBAC/RBAC_role/toDel';
$route['role/setPermission/(:num)/(:any)']='RBAC/RBAC_role/setPermission/$1/$2';
$route['role/toSetPermission']['POST']='RBAC/RBAC_role/toSetPermission';

/************* RBAC-User **************/
$route['user/list']='RBAC/RBAC_user/list';
$route['user/add']='RBAC/RBAC_user/add';
$route['user/toAdd']['POST']='RBAC/RBAC_user/toAdd';
$route['user/toDel']['POST']='RBAC/RBAC_user/toDel';
$route['user/edit/(:num)']['GET']='RBAC/RBAC_user/edit/$1';
$route['user/toEdit']['POST']='RBAC/RBAC_user/toEdit';
$route['user/toResetPwd']['POST']='RBAC/RBAC_user/toResetPwd';

/************* RBAC-Menu **************/
$route['sys/menu/list']='RBAC/RBAC_menu/list';
$route['sys/menu/toDel']['POST']='RBAC/RBAC_menu/toDel';
$route['sys/menu/add/(:num)']='RBAC/RBAC_menu/add/$1';
$route['sys/menu/toAdd']['POST']='RBAC/RBAC_menu/toAdd';
$route['sys/menu/edit/(:num)']='RBAC/RBAC_menu/edit/$1';
$route['sys/menu/toEdit']['POST']='RBAC/RBAC_menu/toEdit';

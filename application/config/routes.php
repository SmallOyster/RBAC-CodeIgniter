<?php
/**
* @name 全局路由
* @author CodeIgniter,SmallOyster
* @since 2018-02-06
* @version V1.0 2018-02-19
*/

defined('BASEPATH') OR exit('No direct script access allowed');


// System Default Routes
$route['admin/default_controller'] = 'main/index';
$route['admin/404_override'] = '';
$route['admin/translate_uri_dashes'] = FALSE;


// Custom Routes

/************* RBAC-API **************/
$route['api/getAllRole']='API/API_rbac_role/getAllRole';
$route['api/getAllMenuForZtree']='API/API_rbac_menu/getAllMenuForZtree';

/************* RBAC-Role **************/
$route['admin/role/list']='RBAC/admin/RBAC_role/list';
$route['admin/role/toList']='RBAC/admin/RBAC_role/toList';
$route['admin/role/add']='RBAC/admin/RBAC_role/add';
$route['admin/role/toAdd']['POST']='RBAC/admin/RBAC_role/toAdd';
$route['admin/role/edit/(:num)/(:any)']['GET']='RBAC/admin/RBAC_role/edit/$1/$2';
$route['admin/role/toEdit']['POST']='RBAC/admin/RBAC_role/toEdit';
$route['admin/role/toDel']['POST']='RBAC/admin/RBAC_role/toDel';
$route['admin/role/setPermission/(:num)/(:any)']='RBAC/admin/RBAC_role/setPermission/$1/$2';
$route['admin/role/toSetPermission']['POST']='RBAC/admin/RBAC_role/toSetPermission';

/************* RBAC-User **************/
$route['admin/user/list']='RBAC/admin/RBAC_user/list';
$route['admin/user/add']='RBAC/admin/RBAC_user/add';
$route['admin/user/toAdd']['POST']='RBAC/admin/RBAC_user/toAdd';
$route['admin/user/toDel']['POST']='RBAC/admin/RBAC_user/toDel';
$route['admin/user/edit/(:num)']['GET']='RBAC/admin/RBAC_user/edit/$1';
$route['admin/user/toEdit']['POST']='RBAC/admin/RBAC_user/toEdit';
$route['admin/user/toResetPwd']['POST']='RBAC/admin/RBAC_user/toResetPwd';

/************* RBAC-Menu **************/
$route['admin/sys/menu/list']='RBAC/admin/RBAC_menu/list';
$route['admin/sys/menu/toDel']['POST']='RBAC/admin/RBAC_menu/toDel';
$route['admin/sys/menu/add/(:num)']='RBAC/admin/RBAC_menu/add/$1';
$route['admin/sys/menu/toAdd']['POST']='RBAC/admin/RBAC_menu/toAdd';
$route['admin/sys/menu/edit/(:num)']='RBAC/admin/RBAC_menu/edit/$1';
$route['admin/sys/menu/toEdit']['POST']='RBAC/admin/RBAC_menu/toEdit';

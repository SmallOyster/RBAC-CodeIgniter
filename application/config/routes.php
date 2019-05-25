<?php
/**
 * @name 生蚝科技RBAC开发框架-路由
 * @author CodeIgniter,Jerry Cheung
 * @since 2018-02-06
 * @version 2019-05-25
 */

defined('BASEPATH') OR exit('No direct script access allowed');


// System Default Routes
$route['default_controller'] = 'Main/index';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;


// Custom Routes
$route['dashborad'] = 'Main/index';

/************* RBAC-API **************/
$route['api/role/get']='API/API_Role/getRoleInfo';
$route['api/role/getUserMenu']='API/API_Role/getUserMenu';
$route['api/role/getRoleMenuForZtree/(:any)']='API/API_Role/getRoleMenuForZtree/$1';


/******* API-用户 *******/
$route['api/user/resetPassword']['POST']='API/API_User/resetPassword';
$route['api/user/delete']['POST']='API/API_User/delete';
$route['api/user/getAllUser']='API/API_User/getAllUser';
$route['api/user/checkDuplicate/(:any)/(:any)']='API/API_User/checkDuplicate/$1/$2';
$route['api/user/getUserInfo']['POST']='API/API_User/getUserInfo';
$route['api/user/updateUserInfo']['POST']='API/API_User/updateUserInfo';


/******* API-通知 *******/
$route['api/notice/get']='API/API_Notice/get';


/*********** RBAC-Admin-Role ************/
$route['admin/role/list']='RBAC/RBAC_role/toList';
$route['admin/role/get']='RBAC/RBAC_role/get';
$route['admin/role/add']='RBAC/RBAC_role/add';
$route['admin/role/toAdd']['POST']='RBAC/RBAC_role/toAdd';
$route['admin/role/edit']='RBAC/RBAC_role/edit';
$route['admin/role/toEdit']['POST']='RBAC/RBAC_role/toEdit';
$route['admin/role/toDelete']['POST']='RBAC/RBAC_role/toDelete';
$route['admin/role/setPermission']='RBAC/RBAC_role/setPermission';
$route['admin/role/toSetPermission']['POST']='RBAC/RBAC_role/toSetPermission';
$route['admin/role/toSetDefaultRole']['POST']='RBAC/RBAC_role/toSetDefaultRole';


/*********** RBAC-Admin-User ************/
$route['admin/user/list']='RBAC/RBAC_user/toList';
$route['admin/user/get']='RBAC/RBAC_user/get';
$route['admin/user/add']='RBAC/RBAC_user/add';
$route['admin/user/toAdd']['POST']='RBAC/RBAC_user/toAdd';
$route['admin/user/toDelete']['POST']='RBAC/RBAC_user/toDelete';
$route['admin/user/edit']='RBAC/RBAC_user/edit';
$route['admin/user/toEdit']['POST']='RBAC/RBAC_user/toEdit';
$route['admin/user/toResetPwd']['POST']='RBAC/RBAC_user/toResetPwd';
$route['admin/user/toUpdateStatus']['POST']='RBAC/RBAC_user/toUpdateStatus';


/*********** RBAC-Admin-Menu ************/
$route['admin/menu/list']='RBAC/RBAC_menu/toList';
$route['admin/menu/toDelete']['POST']='RBAC/RBAC_menu/toDelete';
$route['admin/menu/add/(:num)']='RBAC/RBAC_menu/add/$1';
$route['admin/menu/toAdd']['POST']='RBAC/RBAC_menu/toAdd';
$route['admin/menu/edit/(:num)']='RBAC/RBAC_menu/edit/$1';
$route['admin/menu/toEdit']['POST']='RBAC/RBAC_menu/toEdit';


/************ Admin-Setting *************/
$route['admin/sys/setting/list']='Setting/toList';
$route['admin/sys/setting/toSave']['POST']='Setting/toSave';


/************* Admin-Log **************/
$route['admin/sys/log/list']='Log/toList';
$route['admin/sys/log/toTruncate']['POST']='Log/toTruncate';


/************* Admin-Notice **************/
$route['admin/notice/list']='Notice/adminList';
$route['admin/notice/pub']='Notice/Publish';
$route['admin/notice/toPublish']['POST']='Notice/toPublish';
$route['admin/notice/edit/(:num)']='Notice/edit/$1';
$route['admin/notice/toEdit']['POST']='Notice/toEdit';
$route['admin/notice/toDelete']['POST']='Notice/toDelete';


/************* Notice **************/
$route['notice/detail/(:num)']='Notice/showDetail/$1';


/************* RBAC-User **************/
$route['user/toUpdateProfile']['POST']='User/toUpdateProfile';
$route['user/toLogin']['POST']='User/toLogin';
$route['user/forgetPassword/sendCode']['POST']='User/forgetPasswordSendCode';
$route['user/forgetPassword/verify']['POST']='User/forgetPasswordVerifyCode';
$route['user/toResetPwd']['POST']='User/toResetPassword';

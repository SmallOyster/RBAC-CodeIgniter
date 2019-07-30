<?php
/**
 * @name 生蚝科技RBAC开发框架-路由
 * @author CodeIgniter,Jerry Cheung
 * @since 2018-02-06
 * @version 2019-07-28
 */

defined('BASEPATH') OR exit('No direct script access allowed');

// System Default Routes
$route['default_controller'] = 'Main/index';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;


// Custom Routes
$route['dashborad'] = 'Main/index';

/*********** API-Role ************/
$route['api/role/get']='API/API_Role/getRoleInfo';
$route['api/role/getRoleMenuForZtree']='API/API_Role/getRoleMenuForZtree';
$route['api/role/getUserMenu']='API/API_Role/getUserMenu';


/*********** API-User ************/
$route['api/user/resetPassword']['POST']='API/API_User/resetPassword';
$route['api/user/delete']['POST']='API/API_User/delete';
$route['api/user/getAllUser']='API/API_User/getAllUser';
$route['api/user/checkDuplicate']='API/API_User/checkDuplicate';
$route['api/user/getUserInfo']['POST']='API/API_User/getUserInfo';
$route['api/user/updateUserInfo']['POST']='API/API_User/updateUserInfo';


/*********** RBAC-Admin-Role ************/
$route['admin/role/list']='RbacAdmin/RbacAdmin_role/toList';
$route['admin/role/get']='RbacAdmin/RbacAdmin_role/get';
$route['admin/role/toOperate']['POST']='RbacAdmin/RbacAdmin_role/toOperate';
$route['admin/role/toDelete']['POST']='RbacAdmin/RbacAdmin_role/toDelete';
$route['admin/role/setPermission']='RbacAdmin/RbacAdmin_role/setPermission';
$route['admin/role/toSetPermission']['POST']='RbacAdmin/RbacAdmin_role/toSetPermission';
$route['admin/role/toSetDefaultRole']['POST']='RbacAdmin/RbacAdmin_role/toSetDefaultRole';


/*********** RBAC-Admin-User ************/
$route['admin/user/list']='RbacAdmin/RbacAdmin_user/toList';
$route['admin/user/get']='RbacAdmin/RbacAdmin_user/get';
$route['admin/user/toOperate']['POST']='RbacAdmin/RbacAdmin_user/toOperate';
$route['admin/user/toDelete']['POST']='RbacAdmin/RbacAdmin_user/toDelete';
$route['admin/user/toResetPwd']['POST']='RbacAdmin/RbacAdmin_user/toResetPwd';
$route['admin/user/toUpdateStatus']['POST']='RbacAdmin/RbacAdmin_user/toUpdateStatus';


/*********** RBAC-Admin-Menu ************/
$route['admin/menu/list']='RbacAdmin/RbacAdmin_menu/toList';
$route['admin/menu/toDelete']['POST']='RbacAdmin/RbacAdmin_menu/toDelete';
$route['admin/menu/toOperate']['POST']='RbacAdmin/RbacAdmin_menu/toOperate';


/************ Admin-Setting *************/
$route['admin/sys/setting/list']='Setting/toList';
$route['admin/sys/setting/toSave']['POST']='Setting/toSave';


/************* Admin-Log **************/
$route['admin/sys/log/list']='Log/toList';
$route['admin/sys/log/toTruncate']['POST']='Log/toTruncate';


/************* RBAC-User **************/
$route['user/toUpdateProfile']['POST']='User/toUpdateProfile';
$route['user/toLogin']['POST']='User/toLogin';
$route['user/forgetPassword/sendCode']['POST']='User/forgetPasswordSendCode';
$route['user/forgetPassword/verify']['POST']='User/forgetPasswordVerifyCode';
$route['user/toResetPwd']['POST']='User/toResetPassword';

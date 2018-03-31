<?php
/**
 * @name 全局路由
 * @author CodeIgniter,SmallOyster
 * @since 2018-02-06
 * @version V1.0 2018-03-31
 */

defined('BASEPATH') OR exit('No direct script access allowed');


// System Default Routes
$route['default_controller'] = 'Main/index';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;


// Custom Routes

/************* RBAC-API **************/
$route['api/getAllRole']='API/API_rbac_role/getAllRole';
$route['api/getAllMenuForZtree']='API/API_rbac_menu/getAllMenuForZtree';

/************* RBAC-Admin-Role **************/
$route['admin/role/list']='RBAC/RBAC_role/toList';
$route['admin/role/add']='RBAC/RBAC_role/add';
$route['admin/role/toAdd']['POST']='RBAC/RBAC_role/toAdd';
$route['admin/role/edit/(:num)/(:any)']['GET']='RBAC/RBAC_role/edit/$1/$2';
$route['admin/role/toEdit']['POST']='RBAC/RBAC_role/toEdit';
$route['admin/role/toDelete']['POST']='RBAC/RBAC_role/toDelete';
$route['admin/role/setPermission/(:num)/(:any)']='RBAC/RBAC_role/setPermission/$1/$2';
$route['admin/role/toSetPermission']['POST']='RBAC/RBAC_role/toSetPermission';
$route['admin/role/toSetDefaultRole']['POST']='RBAC/RBAC_role/toSetDefaultRole';

/************* RBAC-Admin-User **************/
$route['admin/user/list']='RBAC/RBAC_user/toList';
$route['admin/user/add']='RBAC/RBAC_user/add';
$route['admin/user/toAdd']['POST']='RBAC/RBAC_user/toAdd';
$route['admin/user/toDelete']['POST']='RBAC/RBAC_user/toDelete';
$route['admin/user/edit/(:num)']['GET']='RBAC/RBAC_user/edit/$1';
$route['admin/user/toEdit']['POST']='RBAC/RBAC_user/toEdit';
$route['admin/user/toResetPwd']['POST']='RBAC/RBAC_user/toResetPwd';
$route['admin/user/toUpdateStatus']['POST']='RBAC/RBAC_user/toUpdateStatus';

/************* RBAC-Admin-Menu **************/
$route['admin/sys/menu/list']='RBAC/RBAC_menu/toList';
$route['admin/sys/menu/toDelete']['POST']='RBAC/RBAC_menu/toDelete';
$route['admin/sys/menu/add/(:num)']='RBAC/RBAC_menu/add/$1';
$route['admin/sys/menu/toAdd']['POST']='RBAC/RBAC_menu/toAdd';
$route['admin/sys/menu/edit/(:num)']='RBAC/RBAC_menu/edit/$1';
$route['admin/sys/menu/toEdit']['POST']='RBAC/RBAC_menu/toEdit';

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
$route['notice/list']='Notice/userList';
$route['notice/detail/(:num)']='Notice/showDetail/$1';

/************* RBAC-User **************/
$route['user/updateProfile']='User/updateProfile';
$route['user/toUpdateProfile']['POST']='User/toUpdateProfile';
$route['user/login']='User/login';
$route['user/toLogin']['POST']='User/toLogin';
$route['user/logout']='User/logout';
$route['user/reg']='User/register';
$route['user/toReg']['POST']='User/toRegister';
$route['user/reg/verify/(:any)']='User/verifyRegister/$1';
$route['user/forgetPwd']='User/forgetPassword';
$route['user/forgetPwd/sendCode']['POST']='User/forgetPasswordSendCode';
$route['user/forgetPwd/verify']['POST']='User/forgetPasswordVerifyCode';
$route['user/resetPwd']='User/resetPassword';
$route['user/toResetPwd']['POST']='User/toResetPassword';

<?php
/**
* @name 生蚝科技RBAC开发框架-邮件配置
* @author Jerry Cheung <master@xshgzs.com>
* @since 2018-03-04
* @version 2018-03-08
*/

defined('BASEPATH') OR exit('No direct script access allowed');

// QQ邮箱地址(个人邮箱仅需填写QQ号)
$config['smtp_user'] = '10000';
// QQ邮箱的16位IMTP授权码
$config['smtp_pass'] = '****************';


/* !!!!!!!!!! 下方配置无需修改 !!!!!!!!!! */
$config['protocol'] = 'smtp';
$config['charset'] = 'utf-8';
$config['wordwrap'] = true;
$config['smtp_host'] = 'ssl://smtp.exmail.qq.com';
$config['smtp_port'] = 465;
$config['mailtype'] = 'html';
$config['crlf']="\r\n";
$config['newline']="\r\n";
/* !!!!!!!!!! 上方配置无需修改 !!!!!!!!!!! */

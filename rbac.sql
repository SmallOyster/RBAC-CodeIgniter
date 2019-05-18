CREATE DATABASE IF NOT EXISTS `rbac` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `rbac`;

CREATE TABLE `log` (
  `id` int(11) NOT NULL,
  `type` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '类型',
  `content` text COLLATE utf8_unicode_ci NOT NULL COMMENT '内容',
  `user_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '记录用户名',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0.0.0.0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `father_id` int(11) NOT NULL COMMENT '父菜单ID（0为主菜单）',
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '名称',
  `icon` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '图标名（FA）',
  `uri` text COLLATE utf8_unicode_ci COMMENT '链接URL',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` varchar(19) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `menu` (`id`, `father_id`, `name`, `icon`, `uri`, `create_time`, `update_time`) VALUES
(1, 0, '系统管理', 'gears', '', '2018-02-18 04:46:23', '2018-03-02 13:09:30'),
(2, 1, '用户列表', 'user-circle-o', 'admin/user/list', '2018-02-18 04:46:23', ''),
(3, 1, '角色列表', 'users', 'admin/role/list', '2018-02-18 04:46:23', ''),
(4, 1, '菜单管理', 'bars', 'admin/menu/list', '2018-02-18 04:46:23', ''),
(5, 1, '操作记录列表', 'list-alt', 'admin/sys/log/list', '2018-02-18 04:46:23', ''),
(7, 1, '公告管理', 'bell', '', '2018-02-18 04:46:23', '2018-03-29 22:02:37'),
(8, 1, '修改系统参数', 'gear', 'admin/sys/setting/list', '2018-03-01 21:16:51', '2019-03-17 22:30:14'),
(10, 0, '示例页面', 'file', '', '2018-03-14 06:39:43', '2018-03-14 22:42:10'),
(11, 10, '列表页', 'list', 'show/list', '2018-03-14 06:41:24', '0000-00-00 00:00:00'),
(12, 10, '空白页', 'file-o', 'show/blank', '2018-03-14 06:41:59', '0000-00-00 00:00:00'),
(13, 10, '表单页', 'table', 'show/form', '2018-03-14 06:43:46', '0000-00-00 00:00:00'),
(14, 7, '公告列表', 'list-alt', 'admin/notice/list', '2018-03-14 21:08:11', '2018-03-28 18:14:03'),
(15, 7, '发布新公告', 'bullhorn', 'admin/notice/pub', '2018-03-14 21:08:46', '2019-03-17 19:26:20'),
(17, 0, '常用链接', 'link', '', '2019-03-23 15:49:14', '0000-00-00 00:00:00'),
(18, 17, '图标库', 'circle-o', 'show/jumpout/http%3A%2F%2Fwww.fontawesome.com.cn%2Ffaicons%2F', '2019-03-23 15:53:45', '0000-00-00 00:00:00'),
(19, 17, '数据库管理', 'circle-o', 'show/jumpout/https%3A%2F%2Fwww.xshgzs.com%2FdbAdmin%2F', '2019-03-24 00:20:50', '0000-00-00 00:00:00'),
(20, 17, 'SSO用户中心', 'circle-o', 'show/jumpout/https%3A%2F%2Fssouc.xshgzs.com', '2019-03-24 00:22:09', '0000-00-00 00:00:00');

CREATE TABLE `notice` (
  `id` int(11) NOT NULL,
  `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `receiver` text COLLATE utf8_unicode_ci NOT NULL,
  `publisher_id` int(11) NOT NULL,
  `praise` int(11) NOT NULL DEFAULT '0',
  `read_count` int(11) NOT NULL DEFAULT '0',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `role` (
  `id` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '角色名称',
  `remark` text COLLATE utf8_unicode_ci COMMENT '备注',
  `is_default` int(1) NOT NULL DEFAULT '0' COMMENT '是否为默认角色',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` varchar(19) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='角色表';

INSERT INTO `role` (`id`, `name`, `remark`, `is_default`, `create_time`, `update_time`) VALUES
('1p2wx4', '超级管理员', '拥有全部权限', 0, '2018-02-18 01:33:20', '2019-03-17 22:06:08');

CREATE TABLE `role_permission` (
  `id` int(11) NOT NULL,
  `role_id` varchar(6) COLLATE utf8_unicode_ci NOT NULL COMMENT '角色ID',
  `menu_id` int(11) NOT NULL COMMENT '菜单ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `role_permission` (`id`, `role_id`, `menu_id`) VALUES
(1, '1p2wx4', 1),
(2, '1p2wx4', 2),
(3, '1p2wx4', 3),
(4, '1p2wx4', 4),
(5, '1p2wx4', 5),
(6, '1p2wx4', 7),
(7, '1p2wx4', 14),
(8, '1p2wx4', 15),
(9, '1p2wx4', 8),
(10, '1p2wx4', 10),
(11, '1p2wx4', 11),
(12, '1p2wx4', 12),
(13, '1p2wx4', 13),
(14, '1p2wx4', 17),
(15, '1p2wx4', 18),
(16, '1p2wx4', 19),
(17, '1p2wx4', 20),
(18, '1p2wx4', 26),
(19, '1p2wx4', 27),
(20, '1p2wx4', 41),
(21, '1p2wx4', 42),
(22, '1p2wx4', 29),
(23, '1p2wx4', 30),
(24, '1p2wx4', 31),
(25, '1p2wx4', 43),
(26, '1p2wx4', 34),
(27, '1p2wx4', 35),
(28, '1p2wx4', 36),
(29, '1p2wx4', 37),
(30, '1p2wx4', 38),
(31, '1p2wx4', 39),
(32, '1p2wx4', 40);

CREATE TABLE `send_mail` (
  `id` int(11) NOT NULL,
  `email` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `token` text COLLATE utf8_unicode_ci NOT NULL,
  `param` text COLLATE utf8_unicode_ci NOT NULL,
  `expire_time` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `setting` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `chinese_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` varchar(19) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `setting` (`id`, `name`, `chinese_name`, `value`, `create_time`, `update_time`) VALUES
(1, 'sessionPrefix', 'Session名称前缀', 'OTRBAC2_', '2018-03-05 03:55:19', '2018-09-26 18:45:52'),
(2, 'systemName', '系统名称', '.0', '2018-03-05 03:55:19', '2019-05-17 22:13:13'),
(3, 'apiPath', 'API接口目录', 'https://test.xshgzs.com/rbac/api/', '2019-02-23 09:29:55', '2019-03-17 22:20:08'),
(4, 'ssoAppId', 'SSO-AppId', '', '2019-03-17 14:22:25', '2019-03-17 22:28:51'),
(5, 'ssoReturnUrl', 'SSO-回调URL', 'https://test.xshgzs.com/rbac/SSO/login', '2019-03-17 14:22:56', '0000-00-00 00:00:00'),
(6, 'ssoServerHost', 'SSO-中心服务器域名', 'https://ssouc.xshgzs.com/', '2019-03-17 14:24:52', '0000-00-00 00:00:00'),
(7, 'ssoApiPath', 'SSO-API接口目录', 'https://ssouc.xshgzs.com/api/', '2019-03-17 14:25:21', '0000-00-00 00:00:00');

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `sso_union_id` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户名',
  `nick_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '昵称',
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL COMMENT '密码',
  `salt` varchar(8) COLLATE utf8_unicode_ci NOT NULL COMMENT '盐',
  `role_id` text COLLATE utf8_unicode_ci NOT NULL COMMENT '角色ID',
  `status` int(1) NOT NULL DEFAULT '2' COMMENT '状态(0:禁用,1:正常,2:未激活)',
  `phone` varchar(11) COLLATE utf8_unicode_ci NOT NULL COMMENT '手机号',
  `email` varchar(60) COLLATE utf8_unicode_ci NOT NULL COMMENT '邮箱地址',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` varchar(19) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_login` varchar(19) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `user` (`id`, `sso_union_id`, `user_name`, `nick_name`, `password`, `salt`, `role_id`, `status`, `phone`, `email`, `create_time`, `update_time`, `last_login`) VALUES
(1, 'G18KECAD', 'super', '管理员', '?', '?', '1p2wx4,g3sa86', 1, '0', '?', '2018-02-17 05:19:58', '2019-03-16 15:56:04', '2019-05-18 09:21:37');


ALTER TABLE `log`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `notice`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `role`
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `id` (`id`);

ALTER TABLE `role_permission`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `send_mail`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `setting`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `user_name` (`user_name`),
  ADD UNIQUE KEY `email` (`email`);


ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
ALTER TABLE `notice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `role_permission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
ALTER TABLE `send_mail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

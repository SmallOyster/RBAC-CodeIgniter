CREATE DATABASE IF NOT EXISTS `rbac`;
USE `rbac`;

CREATE TABLE IF NOT EXISTS `log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '类型',
  `content` text COLLATE utf8_unicode_ci NOT NULL COMMENT '内容',
  `user_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '记录用户名',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0.0.0.0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DELETE FROM `log`;

CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `father_id` int(11) NOT NULL COMMENT '父菜单ID（0为主菜单）',
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '名称',
  `icon` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '图标名（FA）',
  `uri` text COLLATE utf8_unicode_ci COMMENT '链接URI',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` varchar(19) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DELETE FROM `menu`;
INSERT INTO `menu` (`id`, `father_id`, `name`, `icon`, `uri`, `create_time`, `update_time`) VALUES
	(1, 0, '系统管理', 'gears', '', '2018-02-18 20:46:23', ''),
	(2, 1, '用户列表', 'user-circle-o', 'admin/user/list', '2018-02-18 20:46:23', ''),
	(3, 1, '角色列表', 'users', 'admin/role/list', '2018-02-18 20:46:23', ''),
	(4, 1, '菜单管理', 'bars', 'admin/sys/menu/list', '2018-02-18 20:46:23', ''),
	(5, 1, '操作记录列表', 'list-alt', 'admin/sys/log/list', '2018-02-18 20:46:23', ''),
	(6, 1, '数据库后台', 'database', 'show/jumpout/https%3a%2f%2fwww.baidu.com', '2018-02-18 20:46:23', ''),
	(7, 0, '发布公告', 'bullhorn', 'admin/notice/pub', '2018-02-18 20:46:23', '');

CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '角色名称',
  `remark` text COLLATE utf8_unicode_ci COMMENT '备注',
  `is_default` int(1) NOT NULL DEFAULT '0' COMMENT '是否为默认角色',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` varchar(19) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='角色表';

DELETE FROM `role`;
INSERT INTO `role` (`id`, `name`, `remark`, `is_default`, `create_time`, `update_time`) VALUES
	(1, 'KljGfipz', '系统自动创建', 1, '2018-02-18 17:33:20', '0000-00-00 00:00:00'),
	(2, 'vQytYX1z', '系统自动创建', 0, '2018-02-18 17:33:20', '0000-00-00 00:00:00'),
	(3, 'GNVmjJha', '系统自动创建', 0, '2018-02-18 17:33:20', '0000-00-00 00:00:00'),
	(4, 'w19ZdFeJ', '系统自动创建', 0, '2018-02-18 17:33:20', '0000-00-00 00:00:00'),
	(5, 'oUlFnadP', '系统自动创建', 0, '2018-02-18 17:33:20', '0000-00-00 00:00:00');

CREATE TABLE IF NOT EXISTS `role_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL COMMENT '角色ID',
  `menu_id` int(11) NOT NULL COMMENT '菜单ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DELETE FROM `role_permission`;
INSERT INTO `role_permission` (`id`, `role_id`, `menu_id`) VALUES
	(1, 1, 1),
	(2, 1, 2),
	(3, 1, 3),
	(4, 1, 4),
	(5, 1, 5),
	(6, 1, 6),
	(7, 1, 7);

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户名',
  `nick_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '昵称',
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL COMMENT '密码',
  `salt` varchar(8) COLLATE utf8_unicode_ci NOT NULL COMMENT '盐',
  `role_id` int(11) NOT NULL COMMENT '角色ID',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '状态(0:禁用,1:正常,2:未激活)',
  `phone` varchar(11) COLLATE utf8_unicode_ci NOT NULL COMMENT '手机号',
  `email` varchar(60) COLLATE utf8_unicode_ci NOT NULL COMMENT '邮箱地址',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` varchar(19) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `phone` (`phone`),
  UNIQUE KEY `user_name` (`user_name`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DELETE FROM `user`;
INSERT INTO `user` (`id`, `user_name`, `nick_name`, `password`, `salt`, `role_id`, `status`, `phone`, `email`, `create_time`, `update_time`) VALUES
	(1, 'super', 'xsh', '322fd52ada7d025a073526fc26f6d30f3c59f487', 'xce4H1Jv', 1, 2, '13902253100', '571339406@qq.com', '2018-02-17 21:19:58', '2018-02-19 17:40:38');

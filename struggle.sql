CREATE DATABASE IF NOT EXISTS `rbac`;
USE `rbac`;

CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `father_id` int(11) NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `icon` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `url` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DELETE FROM `menu`;
INSERT INTO `menu` (`id`, `father_id`, `name`, `icon`, `url`) VALUES
	(1, 0, '系统管理', 'gears', ''),
	(2, 1, '用户列表', 'user-circle-o', 'user/list'),
	(3, 1, '角色列表', 'users', 'role/list'),
	(4, 1, '菜单管理', 'bars', 'sys/menu/list'),
	(5, 1, '操作记录列表', 'list-alt', 'sys/log/list'),
	(6, 1, '数据库后台', 'database', '/show/jumpout/https%3a%2f%2fsql.itrclub.com%2fsql_admin'),
	(7, 0, '发布公告', 'bullhorn', 'notice/pub');

CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `remark` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='角色表';

DELETE FROM `role`;
INSERT INTO `role` (`id`, `name`, `remark`) VALUES
	(1, '超级管理员', NULL),
	(2, 'xDO5zZ948V', NULL),
	(3, 'JvcdSqYshz', NULL),
	(4, 't2NcPaRGAE', NULL),
	(5, 'Tc7Y2UtHrb', NULL),
	(6, 'BPYihLqg37', NULL),
	(7, 'MVjaPAcOf0', NULL),
	(8, 'ApVRmaO7ct', NULL),
	(9, 'A6bY4v9DNf', NULL),
	(11, 'CR0nHSLZXu', NULL),
	(12, 'pT0rojiRBU', NULL),
	(13, 'jhdQ9zFa4L', NULL),
	(14, 'c78nrCh0fQ', NULL),
	(15, '3Ppz21oNrD', NULL),
	(16, 'uWUkgpT3xd', NULL),
	(17, '34dMCLrYVm', NULL),
	(18, 'FTkEwf5inX', NULL),
	(19, 'AEeRzQypn1', NULL),
	(20, 'bVwz8DYMe9', NULL),
	(21, '7sz2MvKAV8', NULL),
	(22, 'muGNnL6g5Q', NULL),
	(23, 'scbey7uBr3', NULL),
	(24, 'u3VlByOdQv', NULL),
	(25, 'PL2FsBdiWO', NULL),
	(26, 'V5qWpXwHRv', NULL),
	(27, 'RLdMx4WcPs', NULL),
	(28, 'hDSyrU7dLv', NULL),
	(29, 'O7XYdMSL86', NULL),
	(30, 'Vf3Zaj896l', NULL),
	(31, 'ku8DI50apr', NULL),
	(32, 'lR1LaF3HfP', NULL),
	(33, 'JH7OusnCqy', NULL),
	(34, 'secy8AICnp', NULL),
	(36, 'ds8PqpLCrj', NULL),
	(37, 'wvV09fyE4e', NULL),
	(38, 'Ojx2gJr3d5', NULL),
	(39, 'CeWgqUpcdu', NULL),
	(40, 'EyfCWj9FqB', NULL),
	(41, 't6qcSI2EjJ', NULL),
	(42, 'NvFP9wn7Uh', NULL),
	(43, '410vr6c8Cb', NULL),
	(44, 'rpzu2YXfEa', NULL),
	(45, 'IgFQeWtBhN', NULL),
	(46, 'V87rZNofM5', NULL),
	(47, 'mD5Yoz1g4G', NULL),
	(48, 'TAGLcJsKMn', NULL),
	(49, 'YXnrQPRB2k', NULL),
	(50, 'BsaZc6xqjP', NULL),
	(51, 'RXv0kQW5bC', NULL),
	(52, 'O2xpBmS39Y', NULL),
	(53, 'JWl90NVt1R', NULL),
	(54, 'NJkERHVpgf', NULL);

CREATE TABLE IF NOT EXISTS `role_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
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
  `user_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `real_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `salt` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `role_id` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `phone` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` varchar(19) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `phone` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

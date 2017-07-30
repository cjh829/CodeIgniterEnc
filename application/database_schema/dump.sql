-- --------------------------------------------------------
-- Host:                         192.168.33.10
-- Server version:               5.5.52-MariaDB - MariaDB Server
-- Server OS:                    Linux
-- HeidiSQL Version:             9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for ci
CREATE DATABASE IF NOT EXISTS `ci` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `ci`;

-- Dumping structure for table ci.adm_acl
CREATE TABLE IF NOT EXISTS `adm_acl` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `parent_id` bigint(20) NOT NULL DEFAULT '0',
  `name` varchar(50) DEFAULT NULL,
  `controller` varchar(255) NOT NULL,
  `method` varchar(255) NOT NULL,
  `is_menu` tinyint(4) NOT NULL DEFAULT '1',
  `is_enabled` tinyint(4) DEFAULT '1',
  `sort` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- Dumping data for table ci.adm_acl: ~14 rows (approximately)
/*!40000 ALTER TABLE `adm_acl` DISABLE KEYS */;
INSERT INTO `adm_acl` (`id`, `parent_id`, `name`, `controller`, `method`, `is_menu`, `is_enabled`, `sort`) VALUES
	(1, 0, 'admin', '', '', 1, 1, 1),
	(2, 1, 'user', 'user', 'lists', 1, 1, 1),
	(3, 2, 'add user', 'user', 'add', 0, 1, 1),
	(4, 2, 'edit user', 'user', 'edit', 0, 1, 2),
	(5, 1, 'group', 'group', 'lists', 1, 1, 2),
	(6, 2, 'add group', 'group', 'add', 0, 1, 1),
	(7, 2, 'edit group', 'group', 'edit', 0, 1, 2),
	(8, 0, 'demo1', '', '', 1, 1, 2),
	(9, 8, 'demo11', 'demo', 'demo11', 1, 1, 1),
	(10, 8, 'demo12', 'demo', 'demo12', 1, 1, 2),
	(11, 9, 'add demo11', 'demo', 'add11', 0, 1, 1),
	(12, 9, 'edit demo11', 'demo', 'edit11', 0, 1, 2),
	(13, 10, 'add demo12', 'demo', 'add12', 0, 1, 1),
	(14, 10, 'edit demo12', 'demo', 'demo12', 0, 1, 2);
/*!40000 ALTER TABLE `adm_acl` ENABLE KEYS */;

-- Dumping structure for table ci.adm_group
CREATE TABLE IF NOT EXISTS `adm_group` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `is_enabled` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table ci.adm_group: ~2 rows (approximately)
/*!40000 ALTER TABLE `adm_group` DISABLE KEYS */;
INSERT INTO `adm_group` (`id`, `name`, `is_enabled`) VALUES
	(1, 'admin', 1),
	(2, 'testg1', 1);
/*!40000 ALTER TABLE `adm_group` ENABLE KEYS */;

-- Dumping structure for table ci.adm_group_acl
CREATE TABLE IF NOT EXISTS `adm_group_acl` (
  `sn` bigint(20) NOT NULL AUTO_INCREMENT,
  `group_id` bigint(20) NOT NULL,
  `acl_id` bigint(20) NOT NULL,
  `is_enabled` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`sn`),
  UNIQUE KEY `aclgroupid_aclid` (`group_id`,`acl_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- Dumping data for table ci.adm_group_acl: ~14 rows (approximately)
/*!40000 ALTER TABLE `adm_group_acl` DISABLE KEYS */;
INSERT INTO `adm_group_acl` (`sn`, `group_id`, `acl_id`, `is_enabled`) VALUES
	(1, 1, 1, 1),
	(2, 1, 2, 1),
	(3, 1, 3, 1),
	(4, 1, 4, 1),
	(5, 1, 5, 1),
	(6, 1, 6, 1),
	(7, 1, 7, 1),
	(8, 1, 8, 1),
	(9, 1, 9, 1),
	(10, 1, 10, 1),
	(11, 1, 11, 1),
	(12, 1, 12, 1),
	(13, 1, 13, 1),
	(14, 1, 14, 1);
/*!40000 ALTER TABLE `adm_group_acl` ENABLE KEYS */;

-- Dumping structure for table ci.adm_user
CREATE TABLE IF NOT EXISTS `adm_user` (
  `id` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `group_id` bigint(20) NOT NULL,
  `is_enabled` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table ci.adm_user: ~13 rows (approximately)
/*!40000 ALTER TABLE `adm_user` DISABLE KEYS */;
INSERT INTO `adm_user` (`id`, `password`, `group_id`, `is_enabled`) VALUES
	('admin', '$2y$10$OvhGOJI/bje5N.Y5rni.VOsJV6LpNEUMhhcj/ujMh4aWp8eoXs/gu', 1, 1),
	('test1', '', 0, 1),
	('test10', '', 0, 1),
	('test2', '', 0, 1),
	('test3', '', 0, 1),
	('test4', '', 0, 1),
	('test5', '', 0, 1),
	('test6', '', 0, 1),
	('test7', '', 0, 1),
	('test8', '', 0, 1),
	('test9', '', 0, 1);
/*!40000 ALTER TABLE `adm_user` ENABLE KEYS */;

-- Dumping structure for table ci.adm_user_acl
CREATE TABLE IF NOT EXISTS `adm_user_acl` (
  `sn` bigint(20) NOT NULL AUTO_INCREMENT,
  `userid` varchar(50) NOT NULL,
  `acl_id` bigint(20) NOT NULL,
  `is_enabled` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`sn`),
  UNIQUE KEY `userid_aclsn` (`userid`,`acl_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table ci.adm_user_acl: ~0 rows (approximately)
/*!40000 ALTER TABLE `adm_user_acl` DISABLE KEYS */;
/*!40000 ALTER TABLE `adm_user_acl` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

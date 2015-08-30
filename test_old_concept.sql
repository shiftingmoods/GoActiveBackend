/*
SQLyog Ultimate v11.11 (64 bit)
MySQL - 5.6.12-log : Database - test_old_concept
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `control_p_admin` */

DROP TABLE IF EXISTS `control_p_admin`;

CREATE TABLE `control_p_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `address` text COLLATE utf8_unicode_ci,
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `control_p_group_id` int(11) NOT NULL,
  `status` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'ACTIVE',
  PRIMARY KEY (`id`),
  KEY `display_name` (`first_name`),
  KEY `group` (`control_p_group_id`),
  CONSTRAINT `group` FOREIGN KEY (`control_p_group_id`) REFERENCES `control_p_group` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `control_p_admin` */

insert  into `control_p_admin`(`id`,`email`,`username`,`password`,`first_name`,`last_name`,`date_of_birth`,`address`,`city`,`mobile`,`website`,`control_p_group_id`,`status`) values (1,'admin@domain.com','super_admin','ed49c3fed75a513a79cb8bd1d4715d57','adham','ghannam','1986-05-20','Kfarhim','Kfarhim','',NULL,0,'ACTIVE');

/*Table structure for table `control_p_file_type` */

DROP TABLE IF EXISTS `control_p_file_type`;

CREATE TABLE `control_p_file_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `extentions` text COLLATE utf8_unicode_ci NOT NULL,
  `max_file_size` int(11) NOT NULL DEFAULT '10485760',
  `status` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'ACTIVE',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `control_p_file_type` */

insert  into `control_p_file_type`(`id`,`name`,`extentions`,`max_file_size`,`status`) values (1,'audio','audio/mp3 audio/wma',10485760,'ACTIVE');

/*Table structure for table `control_p_folder` */

DROP TABLE IF EXISTS `control_p_folder`;

CREATE TABLE `control_p_folder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `control_p_folder_id` int(20) DEFAULT '0',
  `status` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'ACTIVE',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `control_p_folder` */

/*Table structure for table `control_p_group` */

DROP TABLE IF EXISTS `control_p_group`;

CREATE TABLE `control_p_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'ACTIVE',
  PRIMARY KEY (`id`),
  KEY `display_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `control_p_group` */

insert  into `control_p_group`(`id`,`name`,`status`) values (0,'full_control','ACTIVE');

/*Table structure for table `control_p_login` */

DROP TABLE IF EXISTS `control_p_login`;

CREATE TABLE `control_p_login` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `control_p_admin` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `control_p_group_id` int(11) NOT NULL,
  `start_date` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `end_date` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ip` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `user_agent` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `online` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'YES',
  PRIMARY KEY (`id`),
  KEY `FK_login` (`control_p_group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `control_p_login` */

insert  into `control_p_login`(`id`,`control_p_admin`,`username`,`email`,`control_p_group_id`,`start_date`,`end_date`,`ip`,`user_agent`,`online`) values (3,1,'super_admin',NULL,0,'May 9, 2014, 2:03 pm','PENDING','127.0.0.1','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.131 Safari/537.36','Yes'),(4,1,'super_admin',NULL,0,'May 9, 2014, 3:02 pm','PENDING','127.0.0.1','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.131 Safari/537.36','Yes'),(5,1,'super_admin',NULL,0,'May 10, 2014, 4:32 am','PENDING','127.0.0.1','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.131 Safari/537.36','Yes');

/*Table structure for table `control_p_page_levels` */

DROP TABLE IF EXISTS `control_p_page_levels`;

CREATE TABLE `control_p_page_levels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `menu_display_names` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `menu_pages` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `display_name` (`page`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `control_p_page_levels` */

insert  into `control_p_page_levels`(`id`,`page`,`menu_display_names`,`menu_pages`) values (1,'addControl_p_admin','Admin','control_p_admin'),(2,'addControl_p_file_type','File Type','control_p_file_type'),(3,'addControl_p_folder','Folder','control_p_folder'),(4,'addControl_p_group','Group','control_p_group'),(5,'addControl_p_login','Login','control_p_login'),(6,'addControl_p_page_levels','Page Levels','control_p_page_levels'),(7,'addControl_p_privilege','Privilege','control_p_privilege'),(8,'addControl_p_privilege_to_group','Privilege To Group','control_p_privilege_to_group'),(9,'addControl_p_seq','Seq','control_p_seq'),(10,'addControl_p_table_view_columns','Table View Columns','control_p_table_view_columns'),(11,'addFile','File','file'),(12,'addFiles','Files','files'),(13,'addLanguage','Language','language'),(14,'control_p_admin','Admin','control_p_admin'),(15,'control_p_file_type','File Type','control_p_file_type'),(16,'control_p_folder','Folder','control_p_folder'),(17,'control_p_group','Group','control_p_group'),(18,'control_p_login','Login','control_p_login'),(19,'control_p_page_levels','Page Levels','control_p_page_levels'),(20,'control_p_privilege','Privilege','control_p_privilege'),(21,'control_p_privilege_to_group','Privilege To Group','control_p_privilege_to_group'),(22,'control_p_seq','Seq','control_p_seq'),(23,'control_p_table_view_columns','Table View Columns','control_p_table_view_columns'),(24,'editControl_p_admin','Admin','control_p_admin'),(25,'editControl_p_file_type','File Type','control_p_file_type'),(26,'editControl_p_folder','Folder','control_p_folder'),(27,'editControl_p_group','Group','control_p_group'),(28,'editControl_p_login','Login','control_p_login'),(29,'editControl_p_page_levels','Page Levels','control_p_page_levels'),(30,'editControl_p_privilege','Privilege','control_p_privilege'),(31,'editControl_p_privilege_to_group','Privilege To Group','control_p_privilege_to_group'),(32,'editControl_p_seq','Seq','control_p_seq'),(33,'editControl_p_table_view_columns','Table View Columns','control_p_table_view_columns'),(34,'editFile','File','file'),(35,'editFiles','Files','files'),(36,'editLanguage','Language','language'),(37,'file','File','file'),(38,'files','Files','files'),(39,'index','Index','index'),(40,'language','Language','language'),(41,'login','Login','login'),(42,'manageFiles','ManageFiles','manageFiles'),(43,'manageImages','ManageImages','manageImages'),(44,'settings','Settings','settings'),(45,'viewItem','ViewItem','viewItem'),(46,'addItem','Item','item'),(47,'editItem','Item','item'),(48,'item','Item','item'),(49,'addItem_language','Item Language','item_language'),(50,'editItem_language','Item Language','item_language'),(51,'item_language','Item Language','item_language');

/*Table structure for table `control_p_privilege` */

DROP TABLE IF EXISTS `control_p_privilege`;

CREATE TABLE `control_p_privilege` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'ACTIVE',
  PRIMARY KEY (`id`),
  UNIQUE KEY `display_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=269 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `control_p_privilege` */

insert  into `control_p_privilege`(`id`,`name`,`status`) values (1,'addControl_p_admin','ACTIVE'),(2,'addControl_p_file_type','ACTIVE'),(3,'addControl_p_folder','ACTIVE'),(4,'addControl_p_group','ACTIVE'),(5,'addControl_p_login','ACTIVE'),(6,'addControl_p_page_levels','ACTIVE'),(7,'addControl_p_privilege','ACTIVE'),(8,'addControl_p_privilege_to_group','ACTIVE'),(9,'addControl_p_seq','ACTIVE'),(10,'addControl_p_table_view_columns','ACTIVE'),(11,'addFile','ACTIVE'),(12,'addFiles','ACTIVE'),(13,'addLanguage','ACTIVE'),(14,'delete_control_p_admin','ACTIVE'),(15,'control_p_admin','ACTIVE'),(16,'delete_control_p_file_type','ACTIVE'),(17,'control_p_file_type','ACTIVE'),(18,'delete_control_p_folder','ACTIVE'),(19,'control_p_folder','ACTIVE'),(20,'delete_control_p_group','ACTIVE'),(21,'control_p_group','ACTIVE'),(22,'delete_control_p_login','ACTIVE'),(23,'control_p_login','ACTIVE'),(24,'delete_control_p_page_levels','ACTIVE'),(25,'control_p_page_levels','ACTIVE'),(26,'delete_control_p_privilege','ACTIVE'),(27,'control_p_privilege','ACTIVE'),(28,'delete_control_p_privilege_to_group','ACTIVE'),(29,'control_p_privilege_to_group','ACTIVE'),(30,'delete_control_p_seq','ACTIVE'),(31,'control_p_seq','ACTIVE'),(32,'delete_control_p_table_view_columns','ACTIVE'),(33,'control_p_table_view_columns','ACTIVE'),(34,'editControl_p_admin','ACTIVE'),(35,'editControl_p_file_type','ACTIVE'),(36,'editControl_p_folder','ACTIVE'),(37,'editControl_p_group','ACTIVE'),(38,'editControl_p_login','ACTIVE'),(39,'editControl_p_page_levels','ACTIVE'),(40,'editControl_p_privilege','ACTIVE'),(41,'editControl_p_privilege_to_group','ACTIVE'),(42,'editControl_p_seq','ACTIVE'),(43,'editControl_p_table_view_columns','ACTIVE'),(44,'editFile','ACTIVE'),(45,'editFiles','ACTIVE'),(46,'editLanguage','ACTIVE'),(47,'delete_file','ACTIVE'),(48,'file','ACTIVE'),(49,'delete_files','ACTIVE'),(50,'files','ACTIVE'),(51,'delete_index','ACTIVE'),(52,'index','ACTIVE'),(53,'delete_language','ACTIVE'),(54,'language','ACTIVE'),(55,'delete_login','ACTIVE'),(56,'login','ACTIVE'),(57,'delete_manageFiles','ACTIVE'),(58,'manageFiles','ACTIVE'),(59,'delete_manageImages','ACTIVE'),(60,'manageImages','ACTIVE'),(61,'delete_settings','ACTIVE'),(62,'settings','ACTIVE'),(63,'delete_viewItem','ACTIVE'),(64,'viewItem','ACTIVE'),(141,'addItem','ACTIVE'),(175,'editItem','ACTIVE'),(183,'delete_item','ACTIVE'),(184,'item','ACTIVE'),(210,'addItem_language','ACTIVE'),(245,'editItem_language','ACTIVE'),(255,'delete_item_language','ACTIVE'),(256,'item_language','ACTIVE');

/*Table structure for table `control_p_privilege_to_group` */

DROP TABLE IF EXISTS `control_p_privilege_to_group`;

CREATE TABLE `control_p_privilege_to_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `control_p_group_id` int(11) NOT NULL,
  `control_p_privilege_id` int(11) NOT NULL,
  `status` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'ACTIVE',
  PRIMARY KEY (`id`),
  UNIQUE KEY `NewIndex1` (`control_p_group_id`,`control_p_privilege_id`),
  KEY `FK_group` (`control_p_privilege_id`),
  CONSTRAINT `control_p_privilege` FOREIGN KEY (`control_p_privilege_id`) REFERENCES `control_p_privilege` (`id`),
  CONSTRAINT `control_p_group` FOREIGN KEY (`control_p_group_id`) REFERENCES `control_p_group` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `control_p_privilege_to_group` */

/*Table structure for table `control_p_seq` */

DROP TABLE IF EXISTS `control_p_seq`;

CREATE TABLE `control_p_seq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `seq_array` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `control_p_seq` */

/*Table structure for table `control_p_table_view_columns` */

DROP TABLE IF EXISTS `control_p_table_view_columns`;

CREATE TABLE `control_p_table_view_columns` (
  `id` int(11) NOT NULL DEFAULT '0',
  `table_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `columns` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `control_p_table_view_columns` */

/*Table structure for table `file` */

DROP TABLE IF EXISTS `file`;

CREATE TABLE `file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `control_p_file_type_id` int(11) NOT NULL,
  `control_p_folder_id` int(11) unsigned NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `date_created` date NOT NULL,
  `last_viewed` date DEFAULT NULL,
  `counter` int(11) DEFAULT '0',
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `file` */

/*Table structure for table `files` */

DROP TABLE IF EXISTS `files`;

CREATE TABLE `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `extensions` varchar(255) COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `display_name` (`table`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `files` */

/*Table structure for table `image` */

DROP TABLE IF EXISTS `image`;

CREATE TABLE `image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'ACTIVE',
  PRIMARY KEY (`id`),
  KEY `display_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `image` */

insert  into `image`(`id`,`name`,`status`) values (0,'default.jpg','ACTIVE'),(4,'1399645730.0544.jpg','ACTIVE'),(5,'1399696398.3107.jpg','ACTIVE');

/*Table structure for table `item` */

DROP TABLE IF EXISTS `item`;

CREATE TABLE `item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image_id` int(11) DEFAULT '0',
  `status` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'ACTIVE',
  `price` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `item` */

insert  into `item`(`id`,`image_id`,`status`,`price`) values (2,0,'ACTIVE',25),(3,4,'ACTIVE',40),(4,5,'ACTIVE',90);

/*Table structure for table `item_language` */

DROP TABLE IF EXISTS `item_language`;

CREATE TABLE `item_language` (
  `item_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL DEFAULT '1',
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `details` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`item_id`,`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `item_language` */

insert  into `item_language`(`item_id`,`language_id`,`name`,`description`,`details`) values (2,1,'ITM001','English description','English details'),(2,2,'ITM001FR','French\r\ndescription','French details'),(3,1,'ITM002','English Description ','English Details'),(4,1,'ITMEN003','Eng Desc Item 3','Eng Details Item 3'),(4,2,'ITMFR003','FR Desc Item 3','FR Details Item 3');

/*Table structure for table `language` */

DROP TABLE IF EXISTS `language`;

CREATE TABLE `language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `image_id` int(11) DEFAULT '0',
  `dir` varchar(3) COLLATE utf8_unicode_ci DEFAULT 'ltr',
  PRIMARY KEY (`id`),
  KEY `display_name` (`name`),
  KEY `image` (`image_id`),
  CONSTRAINT `image` FOREIGN KEY (`image_id`) REFERENCES `image` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `language` */

insert  into `language`(`id`,`name`,`code`,`image_id`,`dir`) values (1,'English','EN',0,'ltr'),(2,'France','FR',0,'ltr');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

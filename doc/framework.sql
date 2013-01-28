-- phpMyAdmin SQL Dump
-- version 3.5.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 28, 2013 at 09:17 AM
-- Server version: 5.5.28
-- PHP Version: 5.4.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- --------------------------------------------------------

--
-- Table structure for table `lh_abstract_ad_zone`
--

CREATE TABLE IF NOT EXISTS `lh_abstract_ad_zone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `header_slot` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `lh_abstract_council`
--

CREATE TABLE IF NOT EXISTS `lh_abstract_council` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `location` varchar(250) NOT NULL,
  `lat` float NOT NULL,
  `lon` float NOT NULL,
  `aname` varchar(200) NOT NULL,
  `telephone` varchar(100) NOT NULL,
  `other` tinyint(4) NOT NULL DEFAULT '0',
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lh_abstract_council_address`
--

CREATE TABLE IF NOT EXISTS `lh_abstract_council_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_person` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `telephone` varchar(250) NOT NULL,
  `address1` varchar(250) NOT NULL,
  `address2` varchar(250) NOT NULL,
  `council_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `council_id` (`council_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lh_abstract_date_filter`
--

CREATE TABLE IF NOT EXISTS `lh_abstract_date_filter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `value` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `lh_abstract_date_filter`
--

INSERT INTO `lh_abstract_date_filter` (`id`, `name`, `value`, `position`) VALUES
(2, 'For last week', 7, 20),
(3, 'For last month', 31, 30),
(4, 'For last two months', 60, 40),
(5, 'Today', 1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `lh_abstract_email_templates`
--

CREATE TABLE IF NOT EXISTS `lh_abstract_email_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `subject_en_en` text NOT NULL,
  `content_en_en` text NOT NULL,
  `from_name` varchar(250) NOT NULL,
  `from_email` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `lh_abstract_postcode`
--

CREATE TABLE IF NOT EXISTS `lh_abstract_postcode` (
  `postcode` varchar(10) NOT NULL,
  `paper` varchar(100) NOT NULL,
  `lat` float NOT NULL,
  `lon` float NOT NULL,
  `ad_searchtext` varchar(200) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`postcode`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lh_abstract_sort_option`
--

CREATE TABLE IF NOT EXISTS `lh_abstract_sort_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name_en_en` varchar(100) NOT NULL,
  `position` int(11) NOT NULL,
  `value` varchar(80) NOT NULL,
  `identifier` varchar(50) NOT NULL,
  `dir` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - ASC, 1 - DESC',
  `sort_type` varchar(20) NOT NULL COMMENT 'Sort type identifier',
  `name_ru_ru` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sort_type` (`sort_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

--
-- Dumping data for table `lh_abstract_sort_option`
--

INSERT INTO `lh_abstract_sort_option` (`id`, `name_en_en`, `position`, `value`, `identifier`, `dir`, `sort_type`, `name_ru_ru`) VALUES
(13, 'Newest ads first', 0, 'ptime desc', 'new', 1, 'search', 'New Ad to Old Ad ru'),
(14, 'Oldest ads first', 1, 'ptime asc', 'newasc', 0, 'search', ''),
(21, 'Newest ads first', 0, 'ptime desc', 'new', 1, 'search_relevance', ''),
(22, 'Oldest ads first', 1, 'ptime asc', 'newasc', 0, 'search_relevance', ''),
(26, 'Most relevant first', 4, '@relevance DESC, ptime DESC', 'relevance', 1, 'search_relevance', '');

-- --------------------------------------------------------

--
-- Table structure for table `lh_abstract_sub_regions`
--

CREATE TABLE IF NOT EXISTS `lh_abstract_sub_regions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `position` int(11) NOT NULL,
  `region_id` int(11) NOT NULL,
  `visible` tinyint(4) NOT NULL DEFAULT '1',
  `lat` float NOT NULL,
  `lon` float NOT NULL,
  `aurl` varchar(100) NOT NULL,
  `distance` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `make_id` (`region_id`),
  KEY `visible` (`visible`),
  KEY `aurl` (`aurl`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lh_abstract_url_alias`
--

CREATE TABLE IF NOT EXISTS `lh_abstract_url_alias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url_alias` varchar(100) NOT NULL,
  `url_destination` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `lh_abstract_url_alias`
--

INSERT INTO `lh_abstract_url_alias` (`id`, `url_alias`, `url_destination`) VALUES
(1, 'user/login', 'sign-in');

-- --------------------------------------------------------

--
-- Table structure for table `lh_article`
--

CREATE TABLE IF NOT EXISTS `lh_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article_name` varchar(100) NOT NULL,
  `intro` text NOT NULL,
  `file_name` varchar(250) NOT NULL,
  `body` text NOT NULL,
  `publishtime` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `has_photo` int(11) NOT NULL,
  `pos` int(11) NOT NULL,
  `alias_url` varchar(100) NOT NULL,
  `alternative_url` varchar(100) NOT NULL,
  `mtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

--
-- Dumping data for table `lh_article`
--

INSERT INTO `lh_article` (`id`, `article_name`, `intro`, `file_name`, `body`, `publishtime`, `category_id`, `has_photo`, `pos`, `alias_url`, `alternative_url`, `mtime`) VALUES
(8, 'sdfsdfsdf', '<p>asdasdads</p>\r\n', '', '<p>asdadsad</p>\r\n', 1358630308, 1, 0, 0, 'about-us', '', 0),
(9, 'sdfsdfsdf', '<p>asdasdads</p>\r\n', '', '<p>asdadsad</p>\r\n', 1358630377, 1, 0, 0, 'about-us', '', 0),
(11, 'Article name', '<p>Article body</p>\r\n', '', '', 1358674058, 0, 0, 0, '', '', 0),
(12, 'sdf', '<p>sdf</p>\r\n', '', '', 1358674190, 1, 0, 0, '', '', 1358674754),
(13, 'f', '<p>sdfsdf</p>\r\n', '09bcc23748c28d73814752ba84975c883f921cb4.jpg', '', 1358674234, 1, 1, 0, '', '', 1358674748),
(14, 'hj', '<p>ghj</p>\r\n', '', '', 1358679167, 1, 0, 0, '', '', 1358679168),
(15, 'ghj', '<p>ghj</p>\r\n', '', '', 1358679173, 1, 0, 0, '', '', 1358679173),
(16, 'ghj', '<p>gj</p>\r\n', '', '', 1358679178, 1, 0, 0, '', '', 1358679178),
(17, 'Without alias page', '<p>ghj</p>\r\n', '', '', 1358679183, 1, 0, 0, '', '', 1358694433),
(18, 'gh', '<p>ghj</p>\r\n', '', '', 1358679188, 1, 0, 0, '', '', 1358679188),
(19, 'ghj', '<p>ghj</p>\r\n', '', '', 1358679195, 1, 0, 0, '', '', 1358679195),
(20, 'fgh', '<p>fgh</p>\r\n', '', '', 1359141958, 1, 0, 0, '', '', 1359141958),
(23, 'sdf', '<p>sdf</p>\r\n', '', '', 1359192585, 5, 0, 0, '', '', 1359192585),
(24, 'New sreenshot', '<p>asdasd<img alt="" src="/var/media/images/Penguins.jpg" style="width: 267px; height: 200px;" /></p>\r\n\r\n<p>hjghjghj</p>\r\n\r\n<ul>\r\n	<li>&nbsp;</li>\r\n</ul>\r\n', '', '', 1359356890, 10, 0, 0, '', '', 1359357162);

-- --------------------------------------------------------

--
-- Table structure for table `lh_article_category`
--

CREATE TABLE IF NOT EXISTS `lh_article_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(100) NOT NULL,
  `intro` text NOT NULL,
  `parent_id` int(11) NOT NULL,
  `pos` int(11) NOT NULL,
  `hide_articles` int(11) NOT NULL,
  `alternative_url` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `lh_article_category`
--

INSERT INTO `lh_article_category` (`id`, `category_name`, `intro`, `parent_id`, `pos`, `hide_articles`, `alternative_url`) VALUES
(1, 'Footer articles', '<p><iframe frameborder="0" height="315" scrolling="no" src="http://www.youtube.com/embed/7W807-GokEQ" width="560"></iframe></p>\r\n', 0, 0, 0, ''),
(2, 'Top menu', '<p>top meniu</p>\r\n', 0, 0, 0, ''),
(4, 'Support project', '<p style="margin: 1em 0px 0px; padding: 0px; font-family: Helvetica, Arial, Verdana, sans-serif; font-size: 12px; line-height: normal;">Developing whole application takes a lot of time. So if you want to speedup application development you can make contributions to projects. You can support by paypal or join project itself.</p>\r\n\r\n<p style="margin: 1em 0px 0px; padding: 0px; font-family: Helvetica, Arial, Verdana, sans-serif; font-size: 12px; line-height: normal;">Currently now I&#39;m looking for people who could make new translations or fix current grammar mistakes :D. I would be very glad if someone could review English translations :). Just write me a letter.</p>\r\n', 2, 10, 0, ''),
(5, 'News', '', 2, 0, 0, 'http://google.com'),
(6, 'Documentation', '<p>dfg</p>\r\n', 2, 20, 0, ''),
(7, 'Requirements', '<p><span style="font-family: Helvetica, Arial, Verdana, sans-serif; font-size: 12px; line-height: normal;">Live helper chat requires these software components</span></p>\r\n\r\n<ol style="margin: 2px 0px 10px 15px; padding-right: 0px; padding-left: 0px; font-family: Helvetica, Arial, Verdana, sans-serif; font-size: 12px; line-height: normal;">\r\n	<li style="margin: 0px; padding: 0px;">PHP 5.x version</li>\r\n	<li style="margin: 0px; padding: 0px;">Mysql 4.x or higher version&nbsp;</li>\r\n</ol>\r\n', 2, 30, 0, ''),
(8, 'Features', '', 2, 40, 0, ''),
(9, 'How to install', '<p><span style="font-family: Helvetica, Arial, Verdana, sans-serif; font-size: 12px; line-height: normal;">Point your browser to&nbsp;</span><strong style="margin: 0px; padding: 0px; font-family: Helvetica, Arial, Verdana, sans-serif; font-size: 12px; line-height: normal;">http://&lt;install_domain&gt;/&lt;install_path&gt;/index.php/install/install/</strong><span style="font-family: Helvetica, Arial, Verdana, sans-serif; font-size: 12px; line-height: normal;">and follow installation instructions.</span></p>\r\n', 2, 50, 0, ''),
(10, 'Screenshots', '<p>All provided application screenshots are described in the documentation.</p>\r\n<h3>Application main window</h3>\r\n<a class="image-fancy" href="/docimages/main-window/main-window.png"><img src="/docimages/main-window/thumbnails/main-window.png"></a>\r\n\r\n<h3>Chats managing</h3>\r\n<a class="image-fancy" href="/docimages/chat/chat-activelist.png"><img src="/docimages/chat/thumbnails/chat-activelist.png"></a>\r\n<a class="image-fancy" href="/docimages/chat/chat-listing.png"><img src="/docimages/chat/thumbnails/chat-listing.png"></a>\r\n<a class="image-fancy" href="/docimages/chat/chat-pending-transfer.png"><img src="/docimages/chat/thumbnails/chat-pending-transfer.png"></a>\r\n<a class="image-fancy" href="/docimages/chat/chat-transfer.png"><img src="/docimages/chat/thumbnails/chat-transfer.png"></a>\r\n<a class="image-fancy" href="/docimages/chat/chat-single.png"><img src="/docimages/chat/thumbnails/chat-single.png"></a>\r\n<a class="image-fancy" href="/docimages/chat/chat-window.png"><img src="/docimages/chat/thumbnails/chat-window.png"></a>\r\n<a class="image-fancy" href="/docimages/chat/chat-rooms.png"><img src="/docimages/chat/thumbnails/chat-rooms.png"></a>\r\n<a class="image-fancy" href="/docimages/chat/chat-list.png"><img src="/docimages/chat/thumbnails/chat-list.png"></a>\r\n\r\n<h3>Users, groups managing</h3>\r\n<a class="image-fancy" href="/docimages/users/account.png"><img src="/docimages/users/thumbnails/account.png"></a>\r\n<a class="image-fancy" href="/docimages/users/edit-user.png"><img src="/docimages/users/thumbnails/edit-user.png"></a>\r\n<a class="image-fancy" href="/docimages/users/new-user.png"><img src="/docimages/users/thumbnails/new-user.png"></a>\r\n<a class="image-fancy" href="/docimages/users/list-users.png"><img src="/docimages/users/thumbnails/list-users.png"></a>\r\n\r\n<h3>Permissions management</h3>\r\n<a class="image-fancy" href="/docimages/roles/role-assing-group.png"><img src="/docimages/roles/thumbnails/role-assing-group.png"></a>\r\n<a class="image-fancy" href="/docimages/roles/role-edit.png"><img src="/docimages/roles/thumbnails/role-edit.png"></a>\r\n<a class="image-fancy" href="/docimages/roles/role-assign-policy.png"><img src="/docimages/roles/thumbnails/role-assign-policy.png"></a>\r\n<a class="image-fancy" href="/docimages/roles/new-role.png"><img src="/docimages/roles/thumbnails/new-role.png"></a>\r\n<a class="image-fancy" href="/docimages/roles/roles.png"><img src="/docimages/roles/thumbnails/roles.png"></a>\r\n\r\n<h3>Javascript code generation for integration</h3>\r\n<a class="image-fancy" href="/docimages/html/code-html.png"><img src="/docimages/html/thumbnails/code-html.png"></a>\r\n\r\n<h3>Departments managing screenshots</h3>\r\n<a class="image-fancy" href="/docimages/departaments/edit-departament.png"><img src="/docimages/departaments/thumbnails/edit-departament.png"></a>\r\n<a class="image-fancy" href="/docimages/departaments/new-departament.png"><img src="/docimages/departaments/thumbnails/new-departament.png"></a>\r\n<a class="image-fancy" href="/docimages/departaments/departament-add-icon.png"><img src="/docimages/departaments/thumbnails/departament-add-icon.png"></a>\r\n<a class="image-fancy" href="/docimages/departaments/startchat-departament.png"><img src="/docimages/departaments/thumbnails/startchat-departament.png"></a>\r\n<a class="image-fancy" href="/docimages/departaments/departaments-list.png"><img src="/docimages/departaments/thumbnails/departaments-list.png"></a>\r\n\r\n<h3>Administrator logging</h3>\r\n<a class="image-fancy" href="/docimages/login/login.png"><img src="/docimages/login/thumbnails/login.png"></a>\r\n\r\n<h3>Application install process</h3>\r\n<a class="image-fancy" href="/docimages/install/install-start.png"><img src="/docimages/install/thumbnails/install-start.png"></a>\r\n<a class="image-fancy" href="/docimages/install/step-2.png"><img src="/docimages/install/thumbnails/step-2.png"></a>\r\n<a class="image-fancy" href="/docimages/install/step-3.png"><img src="/docimages/install/thumbnails/step-3.png"></a>\r\n<a class="image-fancy" href="/docimages/install/step-4.png"><img src="/docimages/install/thumbnails/step-4.png"></a>\r\n<a class="image-fancy" href="/docimages/install/already-installed.png"><img src="/docimages/install/thumbnails/already-installed.png"></a>\r\n\r\n<h3>Application from user side</h3>\r\n<a class="image-fancy" href="/docimages/userside/start-chat.png"><img src="/docimages/userside/thumbnails/start-chat.png"></a>\r\n<a class="image-fancy" href="/docimages/userside/user-chat.png"><img src="/docimages/userside/thumbnails/user-chat.png"></a>\r\n\r\n<h3>Desktop client screenshots</h3>\r\n<a class="image-fancy" href="/docimages/dekstop-client/main-window/try-icon.png"><img src="/docimages/dekstop-client/main-window/thumbnails/try-icon.png"></a>\r\n<a class="image-fancy" href="/docimages/dekstop-client/main-window/main-window.png"><img src="/docimages/dekstop-client/main-window/thumbnails/main-window.png"></a>\r\n<a class="image-fancy" href="/docimages/dekstop-client/logging/login-as-dialog.png"><img src="/docimages/dekstop-client/logging/thumbnails/login-as-dialog.png"></a>\r\n<a class="image-fancy" href="/docimages/dekstop-client/logging/login-as-menu.png"><img src="/docimages/dekstop-client/logging/thumbnails/login-as-menu.png"></a>\r\n<a class="image-fancy" href="/docimages/dekstop-client/logging/login-dialog.png"><img src="/docimages/dekstop-client/logging/thumbnails/login-dialog.png"></a>\r\n<a class="image-fancy" href="/docimages/dekstop-client/chat-rooms/mdi-window.png"><img src="/docimages/dekstop-client/chat-rooms/thumbnails/mdi-window.png"></a>\r\n<a class="image-fancy" href="/docimages/dekstop-client/chat-rooms/chat-transfer.png"><img src="/docimages/dekstop-client/chat-rooms/thumbnails/chat-transfer.png"></a>\r\n<a class="image-fancy" href="/docimages/dekstop-client/chat-rooms/separate-window.png"><img src="/docimages/dekstop-client/chat-rooms/thumbnails/separate-window.png"></a>\r\n<a class="image-fancy" href="/docimages/dekstop-client/chat-rooms/chat-as-tab.png"><img src="/docimages/dekstop-client/chat-rooms/thumbnails/chat-as-tab.png"></a>\r\n<a class="image-fancy" href="/docimages/dekstop-client/chat-rooms/closed-chats-menu.png"><img src="/docimages/dekstop-client/chat-rooms/thumbnails/closed-chats-menu.png"></a>\r\n<a class="image-fancy" href="/docimages/dekstop-client/chat-rooms/active-chats-menu.png"><img src="/docimages/dekstop-client/chat-rooms/thumbnails/active-chats-menu.png"></a>\r\n<a class="image-fancy" href="/docimages/dekstop-client/chat-rooms/transfered-chats-menu.png"><img src="/docimages/dekstop-client/chat-rooms/thumbnails/transfered-chats-menu.png"></a>\r\n<a class="image-fancy" href="/docimages/dekstop-client/chat-rooms/pending-chats-menu.png"><img src="/docimages/dekstop-client/chat-rooms/thumbnails/pending-chats-menu.png"></a>\r\n<a class="image-fancy" href="/docimages/dekstop-client/chat-rooms/chat-rooms-expanded.png"><img src="/docimages/dekstop-client/chat-rooms/thumbnails/chat-rooms-expanded.png"></a>\r\n<a class="image-fancy" href="/docimages/dekstop-client/chat-rooms/chat-rooms-general.png"><img src="/docimages/dekstop-client/chat-rooms/thumbnails/chat-rooms-general.png"></a>\r\n<a class="image-fancy" href="/docimages/dekstop-client/chat-rooms/transfered-chat-tooltop.png"><img src="/docimages/dekstop-client/chat-rooms/thumbnails/transfered-chat-tooltop.png"></a>\r\n<a class="image-fancy" href="/docimages/dekstop-client/chat-rooms/pending-chat-tooltip.png"><img src="/docimages/dekstop-client/chat-rooms/thumbnails/pending-chat-tooltip.png"></a>\r\n<a class="image-fancy" href="/docimages/dekstop-client/chat-rooms/chat-rooms-menu.png"><img src="/docimages/dekstop-client/chat-rooms/thumbnails/chat-rooms-menu.png"></a>\r\n\r\n<script type="text/javascript">	\r\n		$(function(){			\r\n				$(''.image-fancy'').fancyzoom({Speed:0,showoverlay:false});\r\n			});\r\n</script>', 2, 60, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `lh_article_static`
--

CREATE TABLE IF NOT EXISTS `lh_article_static` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `mtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `lh_article_static`
--

INSERT INTO `lh_article_static` (`id`, `name`, `content`, `mtime`) VALUES
(2, 'Frontpage bottom article', '', 0),
(3, 'Frontpage introduction article hjhj', '<p><img alt="" src="/var/media/images/Desert.jpg" style="width: 124px; height: 93px;" />hfghfgh &nbsp;Ųį&scaron;weręėčė c</p>\r\n\r\n<p>ghfghfgh &scaron;&scaron;į&Scaron;&Scaron;&Scaron;&Scaron;</p>\r\n\r\n<p>fghfghfghfgh</p>\r\n\r\n<p>ųų&scaron;&scaron;&scaron;&scaron;</p>\r\n\r\n<p>ghfgh</p>\r\n', 1359144280),
(4, 'New static', '<p>All should work now</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><iframe allowfullscreen="" frameborder="0" height="315" src="http://www.youtube.com/embed/SKq9IUO8J2E" width="420"></iframe></p>\r\n\r\n<p>asd</p>\r\n', 1358762344);

-- --------------------------------------------------------

--
-- Table structure for table `lh_forgotpasswordhash`
--

CREATE TABLE IF NOT EXISTS `lh_forgotpasswordhash` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `hash` varchar(100) NOT NULL,
  `created` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `hash` (`hash`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

-- --------------------------------------------------------

--
-- Table structure for table `lh_group`
--

CREATE TABLE IF NOT EXISTS `lh_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `lh_group`
--

INSERT INTO `lh_group` (`id`, `name`) VALUES
(1, 'Administrators'),
(2, 'Registered users'),
(3, 'Non Registered Users'),
(4, 'Newspaper Group Member');

-- --------------------------------------------------------

--
-- Table structure for table `lh_grouprole`
--

CREATE TABLE IF NOT EXISTS `lh_grouprole` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`role_id`,`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `lh_grouprole`
--

INSERT INTO `lh_grouprole` (`id`, `group_id`, `role_id`) VALUES
(1, 1, 1),
(2, 2, 2),
(5, 4, 2),
(7, 4, 4),
(9, 2, 5),
(8, 3, 5),
(10, 4, 5);

-- --------------------------------------------------------

--
-- Table structure for table `lh_groupuser`
--

CREATE TABLE IF NOT EXISTS `lh_groupuser` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`),
  KEY `user_id` (`user_id`),
  KEY `group_id_2` (`group_id`,`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;

--
-- Dumping data for table `lh_groupuser`
--

INSERT INTO `lh_groupuser` (`id`, `group_id`, `user_id`) VALUES
(28, 1, 1),
(3, 2, 4),
(4, 2, 5),
(5, 2, 6),
(6, 2, 7),
(7, 2, 8),
(8, 2, 10),
(27, 2, 12),
(29, 2, 13),
(2, 3, 2),
(36, 3, 18),
(26, 4, 11),
(34, 4, 16);

-- --------------------------------------------------------

--
-- Table structure for table `lh_oid_associations`
--

CREATE TABLE IF NOT EXISTS `lh_oid_associations` (
  `server_url` blob NOT NULL,
  `handle` varchar(255) NOT NULL,
  `secret` blob NOT NULL,
  `issued` int(11) NOT NULL,
  `lifetime` int(11) NOT NULL,
  `assoc_type` varchar(64) NOT NULL,
  PRIMARY KEY (`server_url`(100),`handle`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lh_oid_map`
--

CREATE TABLE IF NOT EXISTS `lh_oid_map` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `open_id` blob NOT NULL,
  `user_id` int(11) NOT NULL,
  `open_id_type` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `lh_oid_nonces`
--

CREATE TABLE IF NOT EXISTS `lh_oid_nonces` (
  `server_url` varchar(2047) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `salt` char(40) NOT NULL,
  UNIQUE KEY `server_url` (`server_url`(100),`timestamp`,`salt`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lh_role`
--

CREATE TABLE IF NOT EXISTS `lh_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `lh_role`
--

INSERT INTO `lh_role` (`id`, `name`) VALUES
(1, 'Administrators'),
(2, 'Registered users'),
(4, 'Newspaper group role'),
(5, 'Non Registered Role');

-- --------------------------------------------------------

--
-- Table structure for table `lh_rolefunction`
--

CREATE TABLE IF NOT EXISTS `lh_rolefunction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `module` varchar(100) NOT NULL,
  `function` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `lh_rolefunction`
--

INSERT INTO `lh_rolefunction` (`id`, `role_id`, `module`, `function`) VALUES
(1, 1, '*', '*'),
(2, 2, 'lhuser', 'selfedit'),
(9, 2, 'lhpn', 'use_registered'),
(10, 4, 'lhsystem', 'use_newspaper'),
(11, 4, 'lhpnadmin', 'use_newspaper'),
(12, 4, 'lhabstract', 'use_newspaper');

-- --------------------------------------------------------

--
-- Table structure for table `lh_shop_base_setting`
--

CREATE TABLE IF NOT EXISTS `lh_shop_base_setting` (
  `identifier` varchar(100) NOT NULL,
  `value` varchar(100) NOT NULL,
  `explain` varchar(100) NOT NULL,
  PRIMARY KEY (`identifier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lh_shop_base_setting`
--

INSERT INTO `lh_shop_base_setting` (`identifier`, `value`, `explain`) VALUES
('credit_price', '0.65', 'Credit price'),
('main_currency', 'EUR', 'Shop base currency'),
('max_downloads', '2', 'How many downloads can be done using download URL');

-- --------------------------------------------------------

--
-- Table structure for table `lh_shop_basket_image`
--

CREATE TABLE IF NOT EXISTS `lh_shop_basket_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `variation_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `session_id` (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lh_shop_basket_session`
--

CREATE TABLE IF NOT EXISTS `lh_shop_basket_session` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `session_hash_crc32` bigint(20) NOT NULL,
  `session_hash` varchar(40) NOT NULL,
  `mtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lh_shop_image_variation`
--

CREATE TABLE IF NOT EXISTS `lh_shop_image_variation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `credits` int(11) NOT NULL,
  `position` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `lh_shop_image_variation`
--

INSERT INTO `lh_shop_image_variation` (`id`, `width`, `height`, `name`, `credits`, `position`, `type`) VALUES
(1, 800, 800, 'Small', 3, 20, 0),
(3, 480, 480, 'Extra small', 1, 10, 0),
(4, 1414, 1414, 'Medium', 4, 30, 0),
(5, 1825, 1825, 'Large', 5, 40, 0),
(6, 0, 0, 'Original', 11, 60, 1);

-- --------------------------------------------------------

--
-- Table structure for table `lh_shop_order`
--

CREATE TABLE IF NOT EXISTS `lh_shop_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_time` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `basket_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `payment_gateway` varchar(100) NOT NULL,
  `currency` varchar(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lh_shop_order_item`
--

CREATE TABLE IF NOT EXISTS `lh_shop_order_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `image_variation_id` int(11) NOT NULL,
  `hash` varchar(40) NOT NULL,
  `credit_price` decimal(10,4) NOT NULL,
  `credits` int(11) NOT NULL,
  `download_count` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lh_shop_payment_setting`
--

CREATE TABLE IF NOT EXISTS `lh_shop_payment_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `identifier` varchar(50) NOT NULL,
  `param` varchar(50) NOT NULL,
  `value` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `identifier` (`identifier`,`param`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lh_shop_user_credit`
--

CREATE TABLE IF NOT EXISTS `lh_shop_user_credit` (
  `user_id` int(11) NOT NULL,
  `credits` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lh_shop_user_credit_order`
--

CREATE TABLE IF NOT EXISTS `lh_shop_user_credit_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `credits` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `payment_gateway` varchar(100) NOT NULL,
  `currency` varchar(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lh_system_config`
--

CREATE TABLE IF NOT EXISTS `lh_system_config` (
  `identifier` varchar(50) NOT NULL,
  `value` text NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `explain` varchar(250) NOT NULL,
  `hidden` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`identifier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lh_system_config`
--

INSERT INTO `lh_system_config` (`identifier`, `value`, `type`, `explain`, `hidden`) VALUES
('allowed_file_types', '''jpg'',''gif'',''png'',''png'',''bmp'',''ogv'',''swf'',''flv'',''mpeg'',''avi'',''mpg'',''wmv''', 0, 'List of allowed file types to upload', 0),
('full_image_quality', '93', 0, 'Full image quality', 0),
('max_comment_length', '1000', 0, 'Maximum comment length', 0),
('max_photo_size', '5120', 0, 'Maximum photo size in kilobytes', 0),
('notice_duration_timeout', '300', 0, 'Maximum editing time of notice in seconds.', 0),
('thumbnail_quality_default', '93', 0, 'Converted small thumbnail image quality', 0),
('thumbnail_scale_algorithm', 'croppedThumbnail', 0, 'It can be "scale" or "croppedThumbnail" - makes perfect squares, or "croppedThumbnailTop" makes perfect squares, image cropped from top', 0),
('watch_mail_every_x_hours', '24', 0, 'Send watch resume every x hours', 0);

-- --------------------------------------------------------

--
-- Table structure for table `lh_users`
--

CREATE TABLE IF NOT EXISTS `lh_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) NOT NULL,
  `password` varchar(40) NOT NULL,
  `email` varchar(100) NOT NULL,
  `lastactivity` int(11) NOT NULL,
  `disabled` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `lh_users`
--

INSERT INTO `lh_users` (`id`, `username`, `password`, `email`, `lastactivity`, `disabled`) VALUES
(1, 'adminasdasd', 'ac9abaf59707a046485dbdc37995e6bb459ab0d1', 'admin@coral.lt', 0, 0),
(18, 'anonymous', '358c9ceeda66dfa7bddefbd9e4f1993fbbf7e652', 'anonymous@gmail.com', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `lh_users_profile`
--

CREATE TABLE IF NOT EXISTS `lh_users_profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `surname` varchar(150) NOT NULL,
  `intro` text NOT NULL,
  `photo` varchar(100) NOT NULL,
  `variations` text NOT NULL,
  `filepath` varchar(200) NOT NULL,
  `website` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `lh_user_fb`
--

CREATE TABLE IF NOT EXISTS `lh_user_fb` (
  `user_id` int(11) NOT NULL,
  `fb_user_id` bigint(20) NOT NULL,
  `name` varchar(150) NOT NULL,
  `link` varchar(250) NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `fb_user_id` (`fb_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lh_user_oauth`
--

CREATE TABLE IF NOT EXISTS `lh_user_oauth` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `twitter_user_id` int(11) NOT NULL,
  `user_name` varchar(200) NOT NULL,
  `oauth_token` varchar(200) NOT NULL,
  `oauth_token_secret` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `twitter_user_id` (`twitter_user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
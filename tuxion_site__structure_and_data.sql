-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 08, 2012 at 08:57 AM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `tuxion_site`
--

-- --------------------------------------------------------

--
-- Table structure for table `tx__account_user_groups`
--

CREATE TABLE IF NOT EXISTS `tx__account_user_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `tx__account_user_info`
--

CREATE TABLE IF NOT EXISTS `tx__account_user_info` (
  `user_id` int(11) NOT NULL COMMENT 'cms_users::id',
  `avatar_image_id` int(11) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `preposition` varchar(255) DEFAULT NULL,
  `family_name` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `claim_key` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tx__backend_configurations`
--

CREATE TABLE IF NOT EXISTS `tx__backend_configurations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `theme` varchar(255) NOT NULL,
  `template` varchar(255) NOT NULL,
  `view` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tx__backend_configurations`
--

INSERT INTO `tx__backend_configurations` (`id`, `name`, `theme`, `template`, `view`) VALUES
(1, 'cms', 'backend', 'backend', 'app'),
(2, 'account', 'backend', 'backend', 'accounts');

-- --------------------------------------------------------

--
-- Table structure for table `tx__cms_components`
--

CREATE TABLE IF NOT EXISTS `tx__cms_components` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `tx__cms_components`
--

INSERT INTO `tx__cms_components` (`id`, `name`, `title`) VALUES
(1, 'cms', 'Core Component'),
(3, 'account', 'Accounts'),
(7, 'text', 'Text'),
(12, 'sevendays', '7 days in my life'),
(13, 'tuxion', 'Tuxion'),
(14, 'map', 'Maps'),
(15, 'faq', 'FAQ'),
(18, 'calendar', 'Calendar');

-- --------------------------------------------------------

--
-- Table structure for table `tx__cms_component_modules`
--

CREATE TABLE IF NOT EXISTS `tx__cms_component_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `com_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `thumbnail` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tx__cms_component_module_info`
--

CREATE TABLE IF NOT EXISTS `tx__cms_component_module_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lang_id` int(11) NOT NULL,
  `com_module_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tx__cms_component_views`
--

CREATE TABLE IF NOT EXISTS `tx__cms_component_views` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `com_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `thumbnail` int(11) DEFAULT NULL,
  `is_config` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

-- --------------------------------------------------------

--
-- Table structure for table `tx__cms_component_view_info`
--

CREATE TABLE IF NOT EXISTS `tx__cms_component_view_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lang_id` int(11) NOT NULL,
  `com_view_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=36 ;

--
-- Dumping data for table `tx__cms_component_view_info`
--

INSERT INTO `tx__cms_component_view_info` (`id`, `lang_id`, `com_view_id`, `title`, `description`) VALUES
(33, 1, 26, 'Kalender', ''),
(34, 1, 27, 'Evenementenbeheer', ''),
(35, 1, 28, 'Instructies', '');

-- --------------------------------------------------------

--
-- Table structure for table `tx__cms_config`
--

CREATE TABLE IF NOT EXISTS `tx__cms_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  `site_id` int(10) unsigned NOT NULL,
  `autoload` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `option_id` (`key`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `tx__cms_modules_to_modulepage`
--

CREATE TABLE IF NOT EXISTS `tx__cms_modules_to_modulepage` (
  `module_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  PRIMARY KEY (`module_id`,`page_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tx__cms_templates`
--

CREATE TABLE IF NOT EXISTS `tx__cms_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Table structure for table `tx__cms_themes`
--

CREATE TABLE IF NOT EXISTS `tx__cms_themes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Table structure for table `tx__cms_users`
--

CREATE TABLE IF NOT EXISTS `tx__cms_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `level` int(3) NOT NULL DEFAULT '1',
  `session` char(32) DEFAULT NULL,
  `ipa` varchar(15) DEFAULT NULL,
  `dt_last_login` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=187 ;

-- --------------------------------------------------------

--
-- Table structure for table `tx__language_info`
--

CREATE TABLE IF NOT EXISTS `tx__language_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL,
  `in_language_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `language_id` (`language_id`),
  KEY `in_language_id` (`in_language_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `tx__language_info`
--

INSERT INTO `tx__language_info` (`id`, `language_id`, `in_language_id`, `title`) VALUES
(1, 1, 1, 'Nederlands'),
(2, 1, 2, 'Dutch'),
(3, 2, 1, 'Engels'),
(4, 2, 2, 'English');

-- --------------------------------------------------------

--
-- Table structure for table `tx__language_languages`
--

CREATE TABLE IF NOT EXISTS `tx__language_languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `valuta_symbol` char(1) NOT NULL,
  `decimal_mark` char(1) NOT NULL,
  `thousands_sep` char(1) NOT NULL,
  `code` char(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lft` (`lft`),
  UNIQUE KEY `rgt` (`rgt`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tx__language_languages`
--

INSERT INTO `tx__language_languages` (`id`, `lft`, `rgt`, `valuta_symbol`, `decimal_mark`, `thousands_sep`, `code`) VALUES
(1, 1, 2, '€', ',', '.', 'nl-NL'),
(2, 3, 4, '$', '.', ',', 'EN');

-- --------------------------------------------------------

--
-- Table structure for table `tx__media_images`
--

CREATE TABLE IF NOT EXISTS `tx__media_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tx__menu_items`
--

CREATE TABLE IF NOT EXISTS `tx__menu_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `page_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `tx__menu_item_info`
--

CREATE TABLE IF NOT EXISTS `tx__menu_item_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` longtext,
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`),
  KEY `language_id` (`language_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `tx__menu_menus`
--

CREATE TABLE IF NOT EXISTS `tx__menu_menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `tx__menu_menus`
--

INSERT INTO `tx__menu_menus` (`id`, `title`) VALUES
(1, 'Main menu');

-- --------------------------------------------------------

--
-- Table structure for table `tx__page_layouts`
--

CREATE TABLE IF NOT EXISTS `tx__page_layouts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `size` int(11) DEFAULT NULL,
  `split` char(1) DEFAULT NULL,
  `content_type` char(1) DEFAULT NULL,
  `content_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lft` (`lft`),
  UNIQUE KEY `rgt` (`rgt`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `tx__page_layout_info`
--

CREATE TABLE IF NOT EXISTS `tx__page_layout_info` (
  `layout_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`layout_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tx__page_modules`
--

CREATE TABLE IF NOT EXISTS `tx__page_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `com_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `optset_id` int(11) DEFAULT NULL,
  `access_level` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `com_id` (`com_id`),
  KEY `optset_id` (`optset_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `tx__page_modules_to_collections`
--

CREATE TABLE IF NOT EXISTS `tx__page_modules_to_collections` (
  `module_id` int(11) NOT NULL,
  `collection_id` int(11) NOT NULL,
  PRIMARY KEY (`module_id`,`collection_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tx__page_modules_to_pages`
--

CREATE TABLE IF NOT EXISTS `tx__page_modules_to_pages` (
  `module_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `layout_id` int(11) NOT NULL,
  PRIMARY KEY (`module_id`,`page_id`,`layout_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tx__page_module_collections`
--

CREATE TABLE IF NOT EXISTS `tx__page_module_collections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tx__page_options_link`
--

CREATE TABLE IF NOT EXISTS `tx__page_options_link` (
  `option_id` int(11) NOT NULL,
  `optset_id` int(11) NOT NULL,
  PRIMARY KEY (`option_id`,`optset_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tx__page_option_sets`
--

CREATE TABLE IF NOT EXISTS `tx__page_option_sets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tx__page_pages`
--

CREATE TABLE IF NOT EXISTS `tx__page_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `view_id` int(11) NOT NULL,
  `theme_id` int(11) NOT NULL DEFAULT '11',
  `template_id` int(11) NOT NULL DEFAULT '12',
  `layout_id` int(11) DEFAULT NULL,
  `optset_id` int(11) DEFAULT NULL,
  `keywords` varchar(2000) DEFAULT NULL,
  `access_level` tinyint(3) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `p_from` timestamp NULL DEFAULT NULL,
  `p_to` timestamp NULL DEFAULT NULL,
  `trashed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tx__tuxion_items`
--

CREATE TABLE IF NOT EXISTS `tx__tuxion_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dt_created` datetime NOT NULL,
  `dt_last_modified` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` tinyint(4) NOT NULL,
  `image_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `text` text,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `tx__tuxion_items`
--

INSERT INTO `tx__tuxion_items` (`id`, `dt_created`, `dt_last_modified`, `user_id`, `category_id`, `image_id`, `title`, `description`, `text`) VALUES
(1, '2012-03-18 00:00:00', '0000-00-00 00:00:00', 1, 1, 0, 'Tuxion, aangenaam', '<h2 class="first">\n	Wie wij zijn</h2>\n<p>\n	Wij zijn een jong, creatief team gespecialiseerd in het ontwikkelen van webapplicaties. Wij zijn altijd op zoek naar uitdagende projecten, zowel op technisch vlak als in design.</p>\n<h2>\n	Wat wij doen</h2>\n<ul>\n	<li>\n		<a class="btn_diensten" href="http://www.tuxion.nl/aangenaam/?section=diensten">Website-ontwikkeling</a> en <a class="btn_portfolio" href="http://www.tuxion.nl/aangenaam/?section=portfolio">design</a></li>\n	<li>\n		Ontwikkelen <a class="btn_diensten" href="http://www.tuxion.nl/aangenaam/?section=diensten">webapplicaties</a></li>\n</ul>\n<h2>\n	En u?</h2>\n<p>\n	Wij zijn benieuwd naar uw ideeÃ«n. <a class="btn_contact" href="http://www.tuxion.nl/aangenaam/?#contact">Vertel ons iets over uw project!</a></p>\n', '<h2 class="first">\n	Over Tuxion</h2>\n<p>\n	Tuxion is een uniek, innovatief webbureau gevestigd in Rotterdam. Onze passie is het creÃ«ren van fraaie, toegankelijke websites en webapplicaties. Websites worden gebouwd volgens de webstandaarden van <a href="http://www.w3.org/" target="_blank" title="Website van het World Wide Web Consortium">W3C</a> en de <a href="http://www.webrichtlijnen.nl/" target="_blank" title="Het kwaliteitsmodel Webrichtlijnen: een duurzaam toegankelijk web voor iedereen. Op webrichtlijnen.nl">webrichtlijnen van de overheid</a>. Wij werken persoonlijk, professioneel en snel.</p>\n<p>\n	Klanten werken graag met ons samen, want:</p>\n<ul>\n	<li>\n		<b>Wij besparen tijd en geld.</b> Waar mogelijk gebruiken wij bestaande code en passen wij deze aan uw wensen aan. Dit bespaart ons tijd en dat ziet u zodoende direct terug in het projectbudget.<br />\n		Â </li>\n	<li>\n		<b>Wij focussen op innovatie.</b> Doordat wij bouwen op bestaande code, kunnen wij constant innoveren. Dit resulteert in betere websites met de nieuwste webtechnlogieÃ«n.<br />\n		Â </li>\n	<li>\n		<b>Wij zijn persoonlijk en betrokken.</b> Wij geloven in sterke, open communicatie. U heeft bij Tuxion daarom Ã©Ã©n contactpersoon die al uw vragen beantwoordt en uw opdrachten snel en deskundig afhandelt. Ook na de oplevering van uw project kunt u rekenen op onze uitstekende dienstverlening.</li>\n</ul>\n<h2 class="first">\n	Diensten</h2>\n<h3>\n	Websites volgens webstandaarden</h3>\n<p>\n	Onze websites zijn herkenbaar door hun sprekende designs en codering volgens webstandaarden van het <a href="http://www.w3.org/" target="_blank" title="Website van het World Wide Web Consortium">W3C</a>.</p>\n<h3>\n	Webapplicaties die werken</h3>\n<p>\n	Dagelijks werken wij aan interessante webapplicaties, die gebruiksvriendelijk zijn en perfect werken. U kunt hierbij denken aan boekhoudsoftware, voorraadsystemen, software in bibliotheken en <a href="http://www.rijksoverheid.nl/onderwerpen/elektronisch-patientendossier#ref-minvws" target="_blank" title="Elektronisch patiÃ«ntendossier">EPD</a>-infrastructuur. Het gehele proces wordt door ons verzorgd: van het uitwerken van een concept tot de technische realisatie daarvan. Dit geeft u het grote voordeel dat techniek en ontwerp naadloos in elkaar overvloeien. Daarnaast zijn wij in elke fase direct aanspreekbaar, waardoor wij snel kunnen inspelen op uw specifieke wensen.</p>\n<h3>\n	Beheer</h3>\n<p>\n	Wenst u een site-update? Geen probleem, wij helpen u graag. Stuur uw wensen naar ons op en wij zorgen er voor dat uw website up-to-date blijft. Zodat u zich volledig kunt richten op wat voor u belangrijk is.</p>\n'),
(2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 2, 0, 'Test 2', '<p>\n	Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.</p>\n', ''),
(3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 3, 0, 'Test 3', '<p>\n	Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.</p>\n', ''),
(4, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 4, 0, 'VB 4', '<p>\n	Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.</p>\n', ''),
(5, '2012-05-04 18:18:39', '0000-00-00 00:00:00', 70, 0, 2, 'Test B2', '<p>\n	Omschr.2</p>\n', '<p>\n	Tekst.2</p>\n');

-- --------------------------------------------------------

--
-- Table structure for table `tx__tuxion_item_categories`
--

CREATE TABLE IF NOT EXISTS `tx__tuxion_item_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `color` varchar(15) DEFAULT NULL COMMENT 'color code',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `tx__tuxion_item_categories`
--

INSERT INTO `tx__tuxion_item_categories` (`id`, `lft`, `rgt`, `title`, `color`) VALUES
(1, 1, 2, 'Tuxion', 'blue'),
(2, 3, 4, 'Actueel', 'yellow'),
(3, 5, 6, '3', 'purple'),
(4, 7, 8, '4', 'cyan');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

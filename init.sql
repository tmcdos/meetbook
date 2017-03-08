# HeidiSQL Dump 
#
# --------------------------------------------------------
# Database:             meeting
# Server version:       5.5.44-MariaDB-log
# Server OS:            Linux
# Target-Compatibility: Same as source server (MySQL 5.5.44-MariaDB-log)
# max_allowed_packet:   10485760
# HeidiSQL version:     3.2 Revision: 1129
# --------------------------------------------------------

/*!40100 SET CHARACTER SET cp1251*/;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0*/;


DROP TABLE IF EXISTS `calendar`;

#
# Table structure for table 'calendar'
#

CREATE TABLE `calendar` (
  `ID` smallint(8) unsigned NOT NULL AUTO_INCREMENT,
  `USER_ID` mediumint(8) unsigned NOT NULL,
  `TITLE` varchar(100) NOT NULL,
  `MAIL` varchar(50) DEFAULT NULL COMMENT 'Author receives notification emails',
  `MULTI_BOOK` tinyint(3) unsigned NOT NULL COMMENT 'Prospect can book several time slots in same calendar',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `name` (`USER_ID`,`TITLE`)
) ENGINE=MyISAM /*!40100 DEFAULT CHARSET=utf8*/;



#
# Dumping data for table 'calendar'
#

LOCK TABLES `calendar` WRITE;
/*!40000 ALTER TABLE `calendar` DISABLE KEYS*/;
INSERT INTO `calendar` (`ID`, `USER_ID`, `TITLE`, `MAIL`, `MULTI_BOOK`) VALUES
	(1,14604,'null',NULL,0),
/*!40000 ALTER TABLE `calendar` ENABLE KEYS*/;
UNLOCK TABLES;


DROP TABLE IF EXISTS `def_mail`;

#
# Table structure for table 'def_mail'
#

CREATE TABLE `def_mail` (
  `ID` tinyint(3) unsigned NOT NULL COMMENT '1=Invitation, 2=Book confirmation, 3=Cancel confirmation',
  `SUBJ` varchar(100) NOT NULL,
  `BODY` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM /*!40100 DEFAULT CHARSET=utf8 COMMENT='Standard mail templates'*/;



#
# Dumping data for table 'def_mail'
#

LOCK TABLES `def_mail` WRITE;
/*!40000 ALTER TABLE `def_mail` DISABLE KEYS*/;
INSERT INTO `def_mail` (`ID`, `SUBJ`, `BODY`) VALUES
	(1,'Agents Meeting Invitation','<div align=\"center\">\r\n    <br>\r\n    <a href=\"\"><img src=\"{PREP}/files/logo.gif\" alt=\"\"></a>\r\n    <br>\r\n    <img src=\"{PREP}/images/smallspacer.gif\" width=\"480\" height=\"10\"><br>\r\n    <img src=\"{PREP}/images/hr.gif\" width=\"480\"><br>\r\n    <img src=\"{PREP}/images/smallspacer.gif\" width=\"480\" height=\"10\">\r\n  </div>\r\n  <h2 align=\"center\">Agents Meeting Invitation</h2>\r\n  <p align=\"justify\">&nbsp;&nbsp;&nbsp;&nbsp;Dear {CLIENT},<br><br>\r\n    This is a personal invitation. \r\n    Please book up your individual meeting with {INVITER} at the most convenient time for you.<br><br>\r\n    If you need more than one meeting, please write us at <b>{MY_EMAIL}</b>.\r\n    <br><br> \r\n    To book your meeting, please open the following hyperlink in your browser\r\n    <br><br> \r\n    <a href=\"{BOOK}\">{BOOK}</a>\r\n    <br><br>\r\n    For more information please contact your sales contact person.\r\n  </p>\r\n  <br>\r\n  <p align=\"justify\">\r\n    <strong>Best Regards<br>YourCompanyName Team</strong>\r\n  </p>\r\n  <br><br>\r\n  <p align=\"right\">\r\n    <a href=\"\"><img src=\"{PREP}/files/FB.png\" width=\"23\" height=\"23\"></a>\r\n    <a href=\"\"><img src=\"{PREP}/files/TW.png\" width=\"23\" height=\"23\"></a>  \r\n    <a href=\"\"><img src=\"{PREP}/files/YT.png\" width=\"23\" height=\"23\"></a>\r\n  </p>\r\n  <div align=\"center\"><a href=\"\"><img src=\"{PREP}/files/your_footer_mipim.jpg\" width=\"480\" height=\"31\" alt=\"\"></a></div>'),
	(2,'Meeting bookmark confirmation','<div align=\"center\">\r\n    <br>\r\n    <a href=\"\"><img src=\"{PREP}/files/logo.gif\" alt=\"\"></a>\r\n    <br>\r\n    <img src=\"{PREP}/images/smallspacer.gif\" width=\"480\" height=\"10\"><br>\r\n    <img src=\"{PREP}/images/hr.gif\" width=\"480\"><br>\r\n    <img src=\"{PREP}/images/smallspacer.gif\" width=\"480\" height=\"10\">\r\n  </div>\r\n  <h2 align=\"center\">Meeting Bookmark Confirmation</h2>\r\n  <p align=\"justify\">&nbsp;&nbsp;&nbsp;&nbsp;Dear {CLIENT},<br><br>\r\n    This is automated reply from the YourCompanyName booking system.\r\n    You have successfully booked up an individual meeting with {CALENDAR}.<br><br>\r\n    Your booked slot is on the <strong>{DATUM}</strong> from <strong>{BEG_TIME} to {END_TIME}</strong> at Head Office of the company (<a href=\"http://goo.gl/maps/\">see map</a>).<br><br>\r\n    For more information please consult with your sales responsible person.\r\n  </p>\r\n  <br>\r\n  <p align=\"justify\">\r\n    <strong>Best Regards<br>YourCompanyName Team</strong>\r\n  </p>\r\n  <br><br>\r\n  <p align=\"right\">\r\n    <a href=\"\"><img src=\"{PREP}/files/FB.png\" width=\"23\" height=\"23\"></a>\r\n    <a href=\"\"><img src=\"{PREP}/files/TW.png\" width=\"23\" height=\"23\"></a>  \r\n    <a href=\"\"><img src=\"{PREP}/files/YT.png\" width=\"23\" height=\"23\"></a>\r\n  </p>\r\n  <div align=\"center\"><a href=\"\"><img src=\"{PREP}/files/your_footer_mipim.jpg\" width=\"480\" height=\"31\" alt=\"\"></a></div>'),
	(3,'Meeting cancellation','<div align=\"center\">\r\n    <br>\r\n    <a href=\"\"><img src=\"{PREP}/files/logo.gif\" alt=\"\"></a>\r\n    <br>\r\n    <img src=\"{PREP}/images/smallspacer.gif\" width=\"480\" height=\"10\"><br>\r\n    <img src=\"{PREP}/images/hr.gif\" width=\"480\"><br>\r\n    <img src=\"{PREP}/images/smallspacer.gif\" width=\"480\" height=\"10\">\r\n  </div>\r\n  <h2 align=\"center\">Meeting Cancellation Notification</h2>\r\n  <p align=\"justify\">&nbsp;&nbsp;&nbsp;&nbsp;Dear {CLIENT},<br><br>\r\n    This is automated reply from the YourCompanyName booking system.\r\n    You have cancelled your individual meeting with {CALENDAR}.<br><br>\r\n    If you want to change the appointment, please book another time slot. Remember that meetings are on the first come, first served base.\r\n    <br><br>\r\n    <strong><a href=\"{BOOK}\">{BOOK}</a></strong>\r\n    <br><br>\r\n    For more information please consult with your sales responsible person.\r\n  </p>\r\n  <br>\r\n  <p align=\"justify\">\r\n    <strong>Best Regards<br>YourCompanyName Team</strong>\r\n  </p>\r\n  <br><br>\r\n  <p align=\"right\">\r\n    <a href=\"\"><img src=\"{PREP}/files/FB.png\" width=\"23\" height=\"23\"></a>\r\n    <a href=\"\"><img src=\"{PREP}/files/TW.png\" width=\"23\" height=\"23\"></a>  \r\n    <a href=\"\"><img src=\"{PREP}/files/YT.png\" width=\"23\" height=\"23\"></a>\r\n  </p>\r\n  <div align=\"center\"><a href=\"\"><img src=\"{PREP}/files/your_footer_mipim.jpg\" width=\"480\" height=\"31\" alt=\"\"></a></div>');
/*!40000 ALTER TABLE `def_mail` ENABLE KEYS*/;
UNLOCK TABLES;


DROP TABLE IF EXISTS `menu_admin`;

#
# Table structure for table 'menu_admin'
#

CREATE TABLE `menu_admin` (
  `ID` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `MENU` varchar(30) NOT NULL,
  `SCRIPT` varchar(20) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  /*!40100 DEFAULT CHARSET=utf8*/;



#
# Dumping data for table 'menu_admin'
#

LOCK TABLES `menu_admin` WRITE;
/*!40000 ALTER TABLE `menu_admin` DISABLE KEYS*/;
INSERT INTO `menu_admin` (`ID`, `MENU`, `SCRIPT`) VALUES
	(1,'Calendars','calendar'),
	(2,'Prospects','candidat'),
	(3,'Time slots','period'),
	(4,'Email templates','email');
/*!40000 ALTER TABLE `menu_admin` ENABLE KEYS*/;
UNLOCK TABLES;


DROP TABLE IF EXISTS `period`;

#
# Table structure for table 'period'
#

CREATE TABLE `period` (
  `ID` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `CAL_ID` smallint(5) unsigned NOT NULL,
  `DATUM` date NOT NULL,
  `BEG_HOUR` time NOT NULL,
  `END_HOUR` time NOT NULL,
  `CLIENT_ID` mediumint(8) unsigned DEFAULT NULL COMMENT 'Index into PROSPECT',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `uniq` (`CAL_ID`,`DATUM`,`BEG_HOUR`)
) ENGINE=MyISAM  /*!40100 DEFAULT CHARSET=cp1251 COMMENT='Времеви слотове за срещи'*/;



DROP TABLE IF EXISTS `prospect`;

#
# Table structure for table 'prospect'
#

CREATE TABLE `prospect` (
  `ID` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `USER_ID` mediumint(8) unsigned NOT NULL,
  `NAME` varchar(100) NOT NULL,
  `EMAIL` varchar(65) NOT NULL,
  `CNT_EMAIL` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'How many times has been invited',
  `VIEW_ALL` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Whether to see all calendars, or only those for which has been invited',
  `LONG_ID` char(40) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `dealer` (`USER_ID`,`NAME`)
) ENGINE=MyISAM /*!40100 DEFAULT CHARSET=utf8 COMMENT='Prospects to be invited for a meeting'*/;



DROP TABLE IF EXISTS `temp_cmd`;

#
# Table structure for table 'temp_cmd'
#

CREATE TABLE `temp_cmd` (
  `CMD` varchar(16) NOT NULL,
  `TXT` varchar(50) NOT NULL,
  `FLG_1` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `FLG_2` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `FLG_3` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`CMD`)
) ENGINE=MyISAM /*!40100 DEFAULT CHARSET=utf8 COMMENT='For which templates is valid the given template command'*/;



#
# Dumping data for table 'temp_cmd'
#

LOCK TABLES `temp_cmd` WRITE;
/*!40000 ALTER TABLE `temp_cmd` DISABLE KEYS*/;
INSERT INTO `temp_cmd` (`CMD`, `TXT`, `FLG_1`, `FLG_2`, `FLG_3`) VALUES
	('CLIENT','full name of the prospect',1,1,1),
	('INVITER','my full name as defined in my login details',1,0,0),
	('CALENDAR','the name of booking calendar',0,1,1),
	('MY_EMAIL','my email as defined in my login details',1,1,1),
	('BOOK','individual URL for booking',1,1,1),
	('DATUM','date for the booked meeting',0,1,1),
	('BEG_TIME','beginning of the chosen time slot',0,1,1),
	('END_TIME','ending of the chosen time slot',0,1,1);
/*!40000 ALTER TABLE `temp_cmd` ENABLE KEYS*/;
UNLOCK TABLES;


DROP TABLE IF EXISTS `template`;

#
# Table structure for table 'template'
#

CREATE TABLE `template` (
  `USER_ID` mediumint(8) unsigned NOT NULL,
  `TIP` tinyint(3) unsigned NOT NULL COMMENT '1=invitation, 2=confirm BOOK, 3=confirm CANCEL',
  `SUBJECT` varchar(250) NOT NULL,
  `BODY` text NOT NULL,
  PRIMARY KEY (`USER_ID`,`TIP`)
) ENGINE=MyISAM /*!40100 DEFAULT CHARSET=utf8 COMMENT='Шаблони за писмата'*/;



#
# Dumping data for table 'template'
#

LOCK TABLES `template` WRITE;
/*!40000 ALTER TABLE `template` DISABLE KEYS*/;
INSERT INTO `template` (`USER_ID`, `TIP`, `SUBJECT`, `BODY`) VALUES
	(14604,1,'tst','bl bla bla test'),
/*!40000 ALTER TABLE `template` ENABLE KEYS*/;
UNLOCK TABLES;


DROP TABLE IF EXISTS `user`;

#
# Table structure for table 'user'
#

CREATE TABLE `user` (
  `ID` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `LOGIN` varchar(16) CHARACTER SET cp1251 COLLATE cp1251_bin NOT NULL,
  `PASS` varchar(16) CHARACTER SET cp1251 COLLATE cp1251_bin NOT NULL,
  `NAME` varchar(50) NOT NULL,
  `EMAIL` varchar(64) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`LOGIN`,`PASS`),
) ENGINE=MyISAM /*!40100 DEFAULT CHARSET=cp1251*/;



#
# Dumping data for table 'user'
#

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS*/;
INSERT INTO `user` (`ID`, `LOGIN`, `PASS`, `NAME`, `EMAIL`) VALUES
	(1,'admin','admin','IVO GELOV','ivo_gelov@mail.com'),
/*!40000 ALTER TABLE `user` ENABLE KEYS*/;
UNLOCK TABLES;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS*/;

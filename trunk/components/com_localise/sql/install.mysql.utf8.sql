-- $Id: install.mysql.utf8.sql 29 2009-11-13 19:10:45Z chdemko $

CREATE TABLE IF NOT EXISTS `#__localise` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_id` int(11) NOT NULL,
  `path` varchar(255) NOT NULL,
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_path` (`path`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


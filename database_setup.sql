-- Create syntax for TABLE 'messages'
CREATE TABLE `messages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `message` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'numbers'
CREATE TABLE `numbers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `number` varchar(15) DEFAULT NULL,
  `name` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` text NOT NULL,
  `password` text NOT NULL,
  `cookie` text NOT NULL,
  `isAdmin` BOOLEAN NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `path` text NOT NULL,
  `virtual_path` text NOT NULL,
  `location` text NOT NULL,
  `owner` text NOT NULL,
  `rights` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `cataloges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `virtual_path` text NOT NULL,
  `location` text NOT NULL,
  `owner` text NOT NULL,
  `rights` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
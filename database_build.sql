CREATE TABLE `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `lastname` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime DEFAULT NULL,
  `status` set('0','1') NOT NULL DEFAULT '1' COMMENT '0 = inactive,\n1= active\n(Add more if necessary)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `admin_permissions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `admin_user_id` int(11) NOT NULL,
  `password` varchar(255) DEFAULT '2444666666',
  PRIMARY KEY (`id`),
  KEY `admin user foreign key` (`admin_user_id`),
  CONSTRAINT `admin user foreign key` FOREIGN KEY (`admin_user_id`) REFERENCES `admin_users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `sections` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `admin_user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime DEFAULT NULL,
  `is_enabled` set('0','1') NOT NULL DEFAULT '0' COMMENT '0 = disabled,\n1= enabled\n',
  PRIMARY KEY (`id`),
  KEY `admin user foreign key` (`admin_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `menu_sections` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `admin_user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_enabled` set('0','1') NOT NULL DEFAULT '0' COMMENT '0 = disabled,\n1= enabled\n',
  `section_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `section foreign key` (`section_id`),
  KEY `admin user foreign key` (`admin_user_id`),
  CONSTRAINT `section foreign key` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `news` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `admin_user_id` int(11) NOT NULL,
  `news_title` varchar(255) NOT NULL,
  `news_text` text NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_enabled` set('0','1') NOT NULL DEFAULT '0' COMMENT '0 = disabled,\n1= enabled\n',
  PRIMARY KEY (`id`),
  KEY `admin user foreign key` (`admin_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `rankings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT 'Ranking',
  `tops` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_enabled` set('0','1') DEFAULT '0' COMMENT '0 = disabled,\\n1= enabled\\n',
  `admin_user_id` int(11) DEFAULT NULL,
  `start_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `end_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `admin user foreign key` (`admin_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `nickname` varchar(50),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_index` (`name`,`lastname`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `rankings_results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ranking_id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `player_score` int(11) NOT NULL,
  `player_name_display` set('NAME', 'COMBINED', 'NICKNAME') NOT NULL DEFAULT 'NAME',
  PRIMARY KEY (`id`),
  KEY `ranking foreign key` (`ranking_id`),
  CONSTRAINT `ranking foreign key` FOREIGN KEY (`ranking_id`) REFERENCES `rankings` (`id`) ON UPDATE CASCADE,
  KEY `users foreign key` (`users_id`),
  CONSTRAINT `users foreign key` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `images` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL DEFAULT '',
  `in_gallery` set('0','1') DEFAULT '0',
  `is_enabled` set('0','1') DEFAULT '0',
  `admin_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime DEFAULT NULL,
  `image_order` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `admin user foreign key` (`admin_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `promotions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL,
  `message` varchar(100) NOT NULL,
  `promo_format` json DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `admin_user_id` int(11) NOT NULL,
  `updated` datetime DEFAULT NULL,
  `is_enabled` set('0','1') NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_default` set('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `admin user foreign key` (`admin_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

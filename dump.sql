-- --------------------------------------------------------
-- Сервер:                       localhost
-- Версія сервера:               5.7.36 - MySQL Community Server (GPL)
-- ОС сервера:                   Linux
-- HeidiSQL Версія:              11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for laminas_blog
CREATE DATABASE IF NOT EXISTS `laminas_blog` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `laminas_blog`;

-- Dumping structure for таблиця laminas_blog.comment
CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `content` mediumtext NOT NULL,
  `author` varchar(128) NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- Dumping data for table laminas_blog.comment: ~7 rows (приблизно)
/*!40000 ALTER TABLE `comment` DISABLE KEYS */;
INSERT INTO `comment` (`id`, `post_id`, `content`, `author`, `date_created`) VALUES
	(1, 1, 'Excellent post!', 'Oleg Krivtsov', '2016-08-09 19:20:00'),
	(3, 10, '--ignore-platform-reqs', 'Roma', '2021-10-14 17:26:22'),
	(4, 11, 'file', 'Roma', '2021-11-08 21:10:59'),
	(5, 11, '$this-layout()-setTemplate(\'layout/users_layout\');', 'Roma', '2021-11-10 20:39:57'),
	(6, 13, 'xzcxz', 'Roma', '2021-11-21 13:17:26'),
	(7, 14, 'Fjhfff', 'Roma', '2021-11-21 13:58:56'),
	(8, 14, 'Hello', 'admin@example.com', '2021-11-21 14:20:57');
/*!40000 ALTER TABLE `comment` ENABLE KEYS */;

-- Dumping structure for таблиця laminas_blog.index
CREATE TABLE IF NOT EXISTS `index` (
  `C1` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table laminas_blog.index: ~120 rows (приблизно)
/*!40000 ALTER TABLE `index` DISABLE KEYS */;
INSERT INTO `index` (`C1`) VALUES
	('-- --------------------------------------------------------'),
	('-- Хост:                         localhost'),
	('-- Версия сервера:               5.7.35 - MySQL Community Server (GPL)'),
	('-- Операционная система:         Linux'),
	('-- HeidiSQL Версия:              11.3.0.6295'),
	('-- --------------------------------------------------------'),
	(NULL),
	('/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;'),
	('/*!40101 SET NAMES utf8 */;'),
	('/*!50503 SET NAMES utf8mb4 */;'),
	('/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;'),
	('/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE=\'NO_AUTO_VALUE_ON_ZERO\' */;'),
	('/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;'),
	(NULL),
	(NULL),
	('-- Дамп структуры базы данных laminas_blog'),
	('CREATE DATABASE IF NOT EXISTS `laminas_blog` /*!40100 DEFAULT CHARACTER SET utf8 */;'),
	('USE `laminas_blog`;'),
	(NULL),
	('-- Дамп структуры для таблица laminas_blog.comment'),
	('CREATE TABLE IF NOT EXISTS `comment` ('),
	('  `id` int(11) NOT NULL AUTO_INCREMENT,'),
	('  `post_id` int(11) NOT NULL,'),
	('  `content` mediumtext NOT NULL,'),
	('  `author` varchar(128) NOT NULL,'),
	('  `date_created` datetime NOT NULL,'),
	('  PRIMARY KEY (`id`)'),
	(') ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;'),
	(NULL),
	('-- Дамп данных таблицы laminas_blog.comment: ~1 rows (приблизительно)'),
	('/*!40000 ALTER TABLE `comment` DISABLE KEYS */;'),
	('REPLACE INTO `comment` (`id`, `post_id`, `content`, `author`, `date_created`) VALUES'),
	('	(1, 1, \'Excellent post!\', \'Oleg Krivtsov\', \'2016-08-09 19:20:00\'),'),
	('	(3, 10, \'--ignore-platform-reqs\', \'Roma\', \'2021-10-14 17:26:22\');'),
	('/*!40000 ALTER TABLE `comment` ENABLE KEYS */;'),
	(NULL),
	('-- Дамп структуры для таблица laminas_blog.post'),
	('CREATE TABLE IF NOT EXISTS `post` ('),
	('  `id` int(11) NOT NULL AUTO_INCREMENT,'),
	('  `title` mediumtext NOT NULL,'),
	('  `content` mediumtext NOT NULL,'),
	('  `status` int(11) NOT NULL,'),
	('  `date_created` datetime NOT NULL,'),
	('  PRIMARY KEY (`id`)'),
	(') ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;'),
	(NULL),
	('-- Дамп данных таблицы laminas_blog.post: ~5 rows (приблизительно)'),
	('/*!40000 ALTER TABLE `post` DISABLE KEYS */;'),
	('REPLACE INTO `post` (`id`, `title`, `content`, `status`, `date_created`) VALUES'),
	('	(1, \'A Free Book about Zend Framework\', \'I\\\'m pleased to announce that now you can read my new book "Using Zend Framework 3" absolutely for free! Moreover, the book is an open-source project hosted on GitHub, so you are encouraged to contribute.\', 2, \'2016-08-09 18:49:00\'),'),
	('	(2, \'Getting Started with Magento Extension Development - Book Review\', \'Recently, I needed some good resource to start learning Magento e-Commerce system for one of my current web projects. For this project, I was required to write an extension module that would implement a customer-specific payment method.\', 2, \'2016-08-10 18:51:00\'),'),
	('	(3, \'Twitter Bootstrap - Making a Professionaly Looking Site\', \'Twitter Bootstrap (shortly, Bootstrap) is a popular CSS framework allowing to make your website professionally looking and visually appealing, even if you don\\\'t have advanced designer skills.\', 2, \'2016-08-11 13:01:00\'),'),
	('	(10, \'A JavaScript library for building user interfaces\', \'Declarative\\r\\nReact makes it painless to create interactive UIs. Design simple views for each state in your application, and React will efficiently update and render just the right components when your data changes.\\r\\n\\r\\nDeclarative views make your code more predictable and easier to debug.\\r\\n\\r\\nComponent-Based\\r\\nBuild encapsulated components that manage their own state, then compose them to make complex UIs.\\r\\n\\r\\nSince component logic is written in JavaScript instead of templates, you can easily pass rich data through your app and keep state out of the DOM.\\r\\n\\r\\nLearn Once, Write Anywhere\\r\\nWe donâ€™t make assumptions about the rest of your technology stack, so you can develop new features in React without rewriting existing code.\\r\\n\\r\\nReact can also render on the server using Node and power mobile apps using React Native.\', 2, \'2021-10-13 20:40:42\'),'),
	('	(11, \'Пінгвіни Мадагаскару\', \'Анімаційний серіал «Пінгвіни Мадагаскару» розповідає про самопроголошених командос, яких звуть Ковальський, Шкіпер, Ріко і Рядовий. Ці бойові пінгвіни щодня виконують надсекретні операції на території нью-йоркського зоопарку, де знаходиться їх штаб. Правда, цим справа не обмежується, і вони сміливо пробираються в метро і підземні комунікації. Коли елітний загін уже впевнений в тому, що місто знаходиться під їх контролем, в зоопарку з\\\'являється король Джуліан — лемур з манією величі і бажанням поневолити світ.\', 2, \'2021-10-17 08:11:32\');'),
	('/*!40000 ALTER TABLE `post` ENABLE KEYS */;'),
	(NULL),
	('-- Дамп структуры для таблица laminas_blog.post_tag'),
	('CREATE TABLE IF NOT EXISTS `post_tag` ('),
	('  `id` int(11) NOT NULL AUTO_INCREMENT,'),
	('  `post_id` int(11) NOT NULL,'),
	('  `tag_id` int(11) NOT NULL,'),
	('  PRIMARY KEY (`id`)'),
	(') ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;'),
	(NULL),
	('-- Дамп данных таблицы laminas_blog.post_tag: ~6 rows (приблизительно)'),
	('/*!40000 ALTER TABLE `post_tag` DISABLE KEYS */;'),
	('REPLACE INTO `post_tag` (`id`, `post_id`, `tag_id`) VALUES'),
	('	(5, 3, 4),'),
	('	(35, 2, 2),'),
	('	(36, 2, 3),'),
	('	(40, 1, 1),'),
	('	(41, 1, 2),'),
	('	(42, 1, 9);'),
	('/*!40000 ALTER TABLE `post_tag` ENABLE KEYS */;'),
	(NULL),
	('-- Дамп структуры для таблица laminas_blog.tag'),
	('CREATE TABLE IF NOT EXISTS `tag` ('),
	('  `id` int(11) NOT NULL AUTO_INCREMENT,'),
	('  `name` varchar(128) DEFAULT NULL,'),
	('  PRIMARY KEY (`id`)'),
	(') ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;'),
	(NULL),
	('-- Дамп данных таблицы laminas_blog.tag: ~9 rows (приблизительно)'),
	('/*!40000 ALTER TABLE `tag` DISABLE KEYS */;'),
	('REPLACE INTO `tag` (`id`, `name`) VALUES'),
	('	(1, \'ZF3\'),'),
	('	(2, \'book\'),'),
	('	(3, \'magento\'),'),
	('	(4, \'bootstrap\'),'),
	('	(13, \'React\'),'),
	('	(14, \'makes\'),'),
	('	(15, \'Пінгвіни\'),'),
	('	(16, \'Мадагаскару\'),'),
	('	(17, \'Фільм\');'),
	('/*!40000 ALTER TABLE `tag` ENABLE KEYS */;'),
	(NULL),
	('-- Дамп структуры для таблица laminas_blog.user'),
	('CREATE TABLE IF NOT EXISTS `user` ('),
	('  `id` int(11) NOT NULL AUTO_INCREMENT,'),
	('  `email` varchar(128) NOT NULL,'),
	('  `full_name` varchar(512) NOT NULL,'),
	('  `password` varchar(256) NOT NULL,'),
	('  `status` int(11) NOT NULL,'),
	('  `date_created` datetime NOT NULL,'),
	('  `pwd_reset_token` varchar(100) DEFAULT NULL,'),
	('  `pwd_reset_token_creation_date` datetime DEFAULT NULL,'),
	('  PRIMARY KEY (`id`),'),
	('  UNIQUE KEY `email_idx` (`email`)'),
	(') ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;'),
	(NULL),
	('-- Дамп данных таблицы laminas_blog.user: ~0 rows (приблизительно)'),
	('/*!40000 ALTER TABLE `user` DISABLE KEYS */;'),
	('REPLACE INTO `user` (`id`, `email`, `full_name`, `password`, `status`, `date_created`, `pwd_reset_token`, `pwd_reset_token_creation_date`) VALUES'),
	('	(1, \'Drakyla60@gmail.com\', \'Roma Volkov 1\', \'$2y$10$Klu9Eharvo4gtIJYWxYd.OjGcFH1D7G54TZwMCjt9ZZpIaxqLS2Bm\', 1, \'2021-10-20 20:19:07\', \'\', \'2021-10-23 09:14:16\');'),
	('/*!40000 ALTER TABLE `user` ENABLE KEYS */;'),
	(NULL),
	('/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, \'\') */;'),
	('/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;'),
	('/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;'),
	('/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;');
/*!40000 ALTER TABLE `index` ENABLE KEYS */;

-- Dumping structure for таблиця laminas_blog.permission
CREATE TABLE IF NOT EXISTS `permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `description` varchar(1024) NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_idx` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Dumping data for table laminas_blog.permission: ~5 rows (приблизно)
/*!40000 ALTER TABLE `permission` DISABLE KEYS */;
INSERT INTO `permission` (`id`, `name`, `description`, `date_created`) VALUES
	(1, 'user.manage', 'Manage users', '2021-11-04 16:14:05'),
	(2, 'permission.manage', 'Manage permissions', '2021-11-04 16:14:05'),
	(3, 'role.manage', 'Manage roles', '2021-11-04 16:14:05'),
	(4, 'profile.any.view', 'View anyone\'s profile', '2021-11-04 16:14:05'),
	(5, 'profile.own.view', 'View own profile', '2021-11-04 16:14:05');
/*!40000 ALTER TABLE `permission` ENABLE KEYS */;

-- Dumping structure for таблиця laminas_blog.post
CREATE TABLE IF NOT EXISTS `post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` int(11) DEFAULT NULL,
  `title` mediumtext NOT NULL,
  `content` mediumtext NOT NULL,
  `description` mediumtext,
  `status` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL,
  `date_deleted` datetime DEFAULT NULL,
  `image` varchar(512) DEFAULT 'default.jpg',
  `count_views` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `post_author_id_index` (`author_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- Dumping data for table laminas_blog.post: ~8 rows (приблизно)
/*!40000 ALTER TABLE `post` DISABLE KEYS */;
INSERT INTO `post` (`id`, `author_id`, `title`, `content`, `description`, `status`, `date_created`, `date_updated`, `date_deleted`, `image`, `count_views`) VALUES
	(1, 3, 'A Free Book about Zend Framework', 'I\'m pleased to announce that now you can read my new book "Using Zend Framework 3" absolutely for free! Moreover, the book is an open-source project hosted on GitHub, so you are encouraged to contribute.', NULL, 2, '2016-08-09 18:49:00', NULL, NULL, NULL, 0),
	(2, 3, 'Getting Started with Magento Extension Development - Book Review', 'Recently, I needed some good resource to start learning Magento e-Commerce system for one of my current web projects. For this project, I was required to write an extension module that would implement a customer-specific payment method.', NULL, 2, '2016-08-10 18:51:00', NULL, NULL, NULL, 0),
	(3, 2, 'Twitter Bootstrap - Making a Professionaly Looking Site', 'Twitter Bootstrap (shortly, Bootstrap) is a popular CSS framework allowing to make your website professionally looking and visually appealing, even if you don\'t have advanced designer skills.', NULL, 1, '2016-08-11 13:01:00', NULL, NULL, NULL, 0),
	(10, 4, 'A JavaScript library for building user interfaces 111', 'Declarative\r\nReact makes it painless to create interactive UIs. Design simple views for each state in your application, and React will efficiently update and render just the right components when your data changes.\r\n\r\nDeclarative views make your code more predictable and easier to debug.\r\n\r\nComponent-Based\r\nBuild encapsulated components that manage their own state, then compose them to make complex UIs.\r\n\r\nSince component logic is written in JavaScript instead of templates, you can easily pass rich data through your app and keep state out of the DOM.\r\n\r\nLearn Once, Write Anywhere\r\nWe donâ€™t make assumptions about the rest of your technology stack, so you can develop new features in React without rewriting existing code.\r\n\r\nReact can also render on the server using Node and power mobile apps using React Native.', NULL, 2, '2021-10-13 20:40:42', NULL, NULL, NULL, 0),
	(11, 2, 'Пінгвіни Мадагаскару', 'Анімаційний серіал «Пінгвіни Мадагаскару» розповідає про самопроголошених командос, яких звуть Ковальський, Шкіпер, Ріко і Рядовий. Ці бойові пінгвіни щодня виконують надсекретні операції на території нью-йоркського зоопарку, де знаходиться їх штаб. Правда, цим справа не обмежується, і вони сміливо пробираються в метро і підземні комунікації. Коли елітний загін уже впевнений в тому, що місто знаходиться під їх контролем, в зоопарку з\'являється король Джуліан — лемур з манією величі і бажанням поневолити світ.', NULL, 2, '2021-10-17 08:11:32', NULL, NULL, NULL, 0),
	(12, 3, 'Тестовий пост 348', 'Тестовий контент\r\n', 'Не стоит сомневаться, что уже в недалеком будущем появятся высокоинтеллектуальные, а может и разумные роботы. В настоящей статье поднимаются вопросы, как [хотя бы теоретически] обеспечить их включение в социум и желательно без катастрофических последствий. Если вам интересна эта тема, добро пожаловать под кат', 2, '2021-11-21 08:36:55', '2021-11-21 08:36:55', NULL, '1637483811_cereal-guy-meme-j7jbtsiyuvhe4k6l.jpg', NULL),
	(13, 2, '$userThe PHP development team announces the immediate availability of PHP 8.0.13. This is a security release.  All PHP 8.0 users are encouraged to upgrade to this version.', 'The PHP development team announces the immediate availability of PHP 8.0.13. This is a security release.\r\n\r\nAll PHP 8.0 users are encouraged to upgrade to this version.\r\n\r\nFor source downloads of PHP 8.0.13 please visit our downloads page, Windows source and binaries can be found on windows.php.net/download/. The list of changes is recorded in the ChangeLog.', 'The PHP development team announces the immediate availability of PHP 8.0.13. This is a security release.  All PHP 8.0 users are encouraged to upgrade to this version.  For source downloads of PHP 8.0.13 please visit our downloads page, Windows source and binaries can be found on windows.php.net/download/. The list of changes is recorded in the ChangeLog.', 1, '2021-11-21 08:42:11', '2021-11-21 17:47:26', NULL, '1637484127_cartoon-grumpy-cat-meme-pozn37mc754aa8b6.jpg', 1),
	(14, 2, 'The PHP development team announces the immediate availability of PHP 8.0.13. This is a security release.  All PHP 8.0 users are encouraged to upgrade to this version.  For source downloads of PHP 8.0.13 please visit our downloads page, Windows source and binaries can be found on windows.php.net/download/. The list of changes is recorded in the ChangeLog.', 'The PHP development team announces the immediate availability of PHP 8.0.13. This is a security release.\r\n\r\nAll PHP 8.0 users are encouraged to upgrade to this version.\r\n\r\nFor source downloads of PHP 8.0.13 please visit our downloads page, Windows source and binaries can be found on windows.php.net/download/. The list of changes is recorded in the ChangeLog.', 'Опис Поста', 1, '2021-11-21 08:44:20', '2021-11-21 17:39:37', NULL, '1637513133_cereal-guy-meme-j7jbtsiyuvhe4k6l.jpg', 23);
/*!40000 ALTER TABLE `post` ENABLE KEYS */;

-- Dumping structure for таблиця laminas_blog.post_tag
CREATE TABLE IF NOT EXISTS `post_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;

-- Dumping data for table laminas_blog.post_tag: ~14 rows (приблизно)
/*!40000 ALTER TABLE `post_tag` DISABLE KEYS */;
INSERT INTO `post_tag` (`id`, `post_id`, `tag_id`) VALUES
	(5, 3, 4),
	(35, 2, 2),
	(36, 2, 3),
	(40, 1, 1),
	(41, 1, 2),
	(42, 1, 9),
	(43, 12, 18),
	(44, 12, 19),
	(45, 12, 20),
	(46, 13, 21),
	(47, 13, 22),
	(48, 13, 23),
	(49, 14, 13),
	(50, 14, 14);
/*!40000 ALTER TABLE `post_tag` ENABLE KEYS */;

-- Dumping structure for таблиця laminas_blog.role
CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `description` varchar(1024) NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_idx` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Dumping data for table laminas_blog.role: ~2 rows (приблизно)
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` (`id`, `name`, `description`, `date_created`) VALUES
	(3, 'Administrator', 'A person who manages users, roles, etc.', '2021-11-04 16:15:55'),
	(4, 'Guest', 'A person who can log in and view own profile.', '2021-11-04 16:15:55');
/*!40000 ALTER TABLE `role` ENABLE KEYS */;

-- Dumping structure for таблиця laminas_blog.role_hierarchy
CREATE TABLE IF NOT EXISTS `role_hierarchy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_role_id` int(11) NOT NULL,
  `child_role_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_role_id` (`parent_role_id`),
  KEY `child_role_id` (`child_role_id`),
  CONSTRAINT `role_hierarchy_ibfk_1` FOREIGN KEY (`parent_role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `role_hierarchy_ibfk_2` FOREIGN KEY (`child_role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table laminas_blog.role_hierarchy: ~0 rows (приблизно)
/*!40000 ALTER TABLE `role_hierarchy` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_hierarchy` ENABLE KEYS */;

-- Dumping structure for таблиця laminas_blog.role_permission
CREATE TABLE IF NOT EXISTS `role_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  KEY `permission_id` (`permission_id`),
  CONSTRAINT `role_permission_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `role_permission_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permission` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- Dumping data for table laminas_blog.role_permission: ~5 rows (приблизно)
/*!40000 ALTER TABLE `role_permission` DISABLE KEYS */;
INSERT INTO `role_permission` (`id`, `role_id`, `permission_id`) VALUES
	(4, 3, 1),
	(5, 3, 2),
	(6, 3, 3),
	(7, 3, 4),
	(8, 4, 5);
/*!40000 ALTER TABLE `role_permission` ENABLE KEYS */;

-- Dumping structure for таблиця laminas_blog.tag
CREATE TABLE IF NOT EXISTS `tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

-- Dumping data for table laminas_blog.tag: ~15 rows (приблизно)
/*!40000 ALTER TABLE `tag` DISABLE KEYS */;
INSERT INTO `tag` (`id`, `name`) VALUES
	(1, 'ZF3'),
	(2, 'book'),
	(3, 'magento'),
	(4, 'bootstrap'),
	(13, 'React'),
	(14, 'makes'),
	(15, 'Пінгвіни'),
	(16, 'Мадагаскару'),
	(17, 'Фільм'),
	(18, 'Переміщення поля'),
	(19, 'Відокремлення класу'),
	(20, 'Вбудовування класу'),
	(21, 'Теги'),
	(22, 'для'),
	(23, 'поста');
/*!40000 ALTER TABLE `tag` ENABLE KEYS */;

-- Dumping structure for таблиця laminas_blog.user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(128) NOT NULL,
  `full_name` varchar(512) NOT NULL,
  `password` varchar(256) NOT NULL,
  `status` int(11) NOT NULL,
  `avatar` varchar(255) DEFAULT 'no-avatar.png',
  `date_created` datetime NOT NULL,
  `date_deleted` datetime DEFAULT NULL,
  `date_updated` datetime DEFAULT NULL,
  `pwd_reset_token` varchar(100) DEFAULT NULL,
  `pwd_reset_token_creation_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_idx` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- Dumping data for table laminas_blog.user: ~5 rows (приблизно)
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`, `email`, `full_name`, `password`, `status`, `avatar`, `date_created`, `date_deleted`, `date_updated`, `pwd_reset_token`, `pwd_reset_token_creation_date`) VALUES
	(2, 'admin@example.com', 'Admin', '$2y$10$Imo1uvINsdlhPjy7iI9KvenaZX9FDDZk6nG5TdmfOPio8JtQtuo26', 1, '1636572448_cereal-guy-meme-j7jbtsiyuvhe4k6l.jpg', '2021-11-04 16:15:55', NULL, '2021-11-10 19:27:29', NULL, NULL),
	(3, 'Drakyla60@gmail.com', 'Roma Volkov', '$2y$10$b2H/zmO5zftLhOjzeS90Uui0bZdv13mhh1WEEAytQ1V6rnXUQwcAa', 1, '1636200045__images_rewards_16x9_2020_11_Holiday_Cards_Wallpapers_16x9_ACV2.jpg', '2021-11-04 17:27:31', NULL, '2021-11-04 17:27:31', NULL, NULL),
	(4, 'genry@gmail.com', 'genry', '$2y$10$nvEe0wWyl52BMHpPFxX9DOHqryIIL2EUzvNr5.SATe3q9iJp0y892', 1, '1636568979_happy-guy-meme-face-wta7bhargllym5bh.jpg', '2021-11-04 21:19:01', NULL, '2021-11-04 21:19:01', NULL, NULL),
	(5, 'jonny@gmail.com', 'jonny', '$2y$10$P/m4dMNe7MJVU.9hfhFNJerx6LT353FYbnuUxhz0H1PWaiOmKIEq6', 1, '1636573464_reptile-lizard-rango-meme-gmhulm9m04v9px0u.jpg', '2021-11-05 09:52:07', NULL, '2021-11-05 09:52:07', NULL, NULL),
	(6, 'Drakyla60@gmail.co', 'Roma', '$2y$10$0NnLs1SOdg.ZU0c/9neP4e7UKJ3TQxy.vnoMqB4sToFWDwRs7FSau', 1, '1636573220_cartoon-grumpy-cat-meme-pozn37mc754aa8b6.jpg', '2021-11-09 19:07:38', NULL, '2021-11-09 19:07:38', NULL, NULL);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;

-- Dumping structure for таблиця laminas_blog.user_role
CREATE TABLE IF NOT EXISTS `user_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `user_role_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_role_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- Dumping data for table laminas_blog.user_role: ~5 rows (приблизно)
/*!40000 ALTER TABLE `user_role` DISABLE KEYS */;
INSERT INTO `user_role` (`id`, `user_id`, `role_id`) VALUES
	(1, 2, 3),
	(5, 4, 4),
	(7, 3, 3),
	(9, 6, 4),
	(10, 5, 4);
/*!40000 ALTER TABLE `user_role` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

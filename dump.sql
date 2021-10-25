-- --------------------------------------------------------
-- Хост:                         localhost
-- Версия сервера:               5.7.35 - MySQL Community Server (GPL)
-- Операционная система:         Linux
-- HeidiSQL Версия:              11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Дамп структуры базы данных laminas_blog
CREATE DATABASE IF NOT EXISTS `laminas_blog` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `laminas_blog`;

-- Дамп структуры для таблица laminas_blog.comment
CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `content` mediumtext NOT NULL,
  `author` varchar(128) NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы laminas_blog.comment: ~2 rows (приблизительно)
/*!40000 ALTER TABLE `comment` DISABLE KEYS */;
REPLACE INTO `comment` (`id`, `post_id`, `content`, `author`, `date_created`) VALUES
	(1, 1, 'Excellent post!', 'Oleg Krivtsov', '2016-08-09 19:20:00'),
	(3, 10, '--ignore-platform-reqs', 'Roma', '2021-10-14 17:26:22');
/*!40000 ALTER TABLE `comment` ENABLE KEYS */;

-- Дамп структуры для таблица laminas_blog.index
CREATE TABLE IF NOT EXISTS `index` (
  `C1` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы laminas_blog.index: ~120 rows (приблизительно)
/*!40000 ALTER TABLE `index` DISABLE KEYS */;
REPLACE INTO `index` (`C1`) VALUES
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

-- Дамп структуры для таблица laminas_blog.permission
CREATE TABLE IF NOT EXISTS `permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `description` varchar(1024) NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_idx` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы laminas_blog.permission: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `permission` DISABLE KEYS */;
/*!40000 ALTER TABLE `permission` ENABLE KEYS */;

-- Дамп структуры для таблица laminas_blog.post
CREATE TABLE IF NOT EXISTS `post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` mediumtext NOT NULL,
  `content` mediumtext NOT NULL,
  `status` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы laminas_blog.post: ~5 rows (приблизительно)
/*!40000 ALTER TABLE `post` DISABLE KEYS */;
REPLACE INTO `post` (`id`, `title`, `content`, `status`, `date_created`) VALUES
	(1, 'A Free Book about Zend Framework', 'I\'m pleased to announce that now you can read my new book "Using Zend Framework 3" absolutely for free! Moreover, the book is an open-source project hosted on GitHub, so you are encouraged to contribute.', 2, '2016-08-09 18:49:00'),
	(2, 'Getting Started with Magento Extension Development - Book Review', 'Recently, I needed some good resource to start learning Magento e-Commerce system for one of my current web projects. For this project, I was required to write an extension module that would implement a customer-specific payment method.', 2, '2016-08-10 18:51:00'),
	(3, 'Twitter Bootstrap - Making a Professionaly Looking Site', 'Twitter Bootstrap (shortly, Bootstrap) is a popular CSS framework allowing to make your website professionally looking and visually appealing, even if you don\'t have advanced designer skills.', 2, '2016-08-11 13:01:00'),
	(10, 'A JavaScript library for building user interfaces', 'Declarative\r\nReact makes it painless to create interactive UIs. Design simple views for each state in your application, and React will efficiently update and render just the right components when your data changes.\r\n\r\nDeclarative views make your code more predictable and easier to debug.\r\n\r\nComponent-Based\r\nBuild encapsulated components that manage their own state, then compose them to make complex UIs.\r\n\r\nSince component logic is written in JavaScript instead of templates, you can easily pass rich data through your app and keep state out of the DOM.\r\n\r\nLearn Once, Write Anywhere\r\nWe donâ€™t make assumptions about the rest of your technology stack, so you can develop new features in React without rewriting existing code.\r\n\r\nReact can also render on the server using Node and power mobile apps using React Native.', 2, '2021-10-13 20:40:42'),
	(11, 'Пінгвіни Мадагаскару', 'Анімаційний серіал «Пінгвіни Мадагаскару» розповідає про самопроголошених командос, яких звуть Ковальський, Шкіпер, Ріко і Рядовий. Ці бойові пінгвіни щодня виконують надсекретні операції на території нью-йоркського зоопарку, де знаходиться їх штаб. Правда, цим справа не обмежується, і вони сміливо пробираються в метро і підземні комунікації. Коли елітний загін уже впевнений в тому, що місто знаходиться під їх контролем, в зоопарку з\'являється король Джуліан — лемур з манією величі і бажанням поневолити світ.', 2, '2021-10-17 08:11:32');
/*!40000 ALTER TABLE `post` ENABLE KEYS */;

-- Дамп структуры для таблица laminas_blog.post_tag
CREATE TABLE IF NOT EXISTS `post_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы laminas_blog.post_tag: ~6 rows (приблизительно)
/*!40000 ALTER TABLE `post_tag` DISABLE KEYS */;
REPLACE INTO `post_tag` (`id`, `post_id`, `tag_id`) VALUES
	(5, 3, 4),
	(35, 2, 2),
	(36, 2, 3),
	(40, 1, 1),
	(41, 1, 2),
	(42, 1, 9);
/*!40000 ALTER TABLE `post_tag` ENABLE KEYS */;

-- Дамп структуры для таблица laminas_blog.role
CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `description` varchar(1024) NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_idx` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы laminas_blog.role: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
/*!40000 ALTER TABLE `role` ENABLE KEYS */;

-- Дамп структуры для таблица laminas_blog.role_hierarchy
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

-- Дамп данных таблицы laminas_blog.role_hierarchy: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `role_hierarchy` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_hierarchy` ENABLE KEYS */;

-- Дамп структуры для таблица laminas_blog.role_permission
CREATE TABLE IF NOT EXISTS `role_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  KEY `permission_id` (`permission_id`),
  CONSTRAINT `role_permission_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `role_permission_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы laminas_blog.role_permission: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `role_permission` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_permission` ENABLE KEYS */;

-- Дамп структуры для таблица laminas_blog.tag
CREATE TABLE IF NOT EXISTS `tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы laminas_blog.tag: ~9 rows (приблизительно)
/*!40000 ALTER TABLE `tag` DISABLE KEYS */;
REPLACE INTO `tag` (`id`, `name`) VALUES
	(1, 'ZF3'),
	(2, 'book'),
	(3, 'magento'),
	(4, 'bootstrap'),
	(13, 'React'),
	(14, 'makes'),
	(15, 'Пінгвіни'),
	(16, 'Мадагаскару'),
	(17, 'Фільм');
/*!40000 ALTER TABLE `tag` ENABLE KEYS */;

-- Дамп структуры для таблица laminas_blog.user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(128) NOT NULL,
  `full_name` varchar(512) NOT NULL,
  `password` varchar(256) NOT NULL,
  `status` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `pwd_reset_token` varchar(100) DEFAULT NULL,
  `pwd_reset_token_creation_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_idx` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы laminas_blog.user: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
REPLACE INTO `user` (`id`, `email`, `full_name`, `password`, `status`, `date_created`, `pwd_reset_token`, `pwd_reset_token_creation_date`) VALUES
	(1, 'Drakyla60@gmail.com', 'Roma Volkov 1', '$2y$10$Klu9Eharvo4gtIJYWxYd.OjGcFH1D7G54TZwMCjt9ZZpIaxqLS2Bm', 1, '2021-10-20 20:19:07', '', '2021-10-23 09:14:16');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;

-- Дамп структуры для таблица laminas_blog.user_role
CREATE TABLE IF NOT EXISTS `user_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `user_role_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_role_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы laminas_blog.user_role: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `user_role` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_role` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

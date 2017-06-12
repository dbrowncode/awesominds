Set foreign_Key_checks = 0;
drop table if exists course cascade;
drop table if exists users cascade;
drop table if exists question cascade;
drop table if exists score cascade;
Set foreign_Key_checks = 1;

CREATE TABLE `course` (
  `courseid` varchar(9) NOT NULL,
  `name` tinytext NOT NULL,
  PRIMARY KEY (`courseid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `question` (
  `questionid` int(11) NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `chapter` int(11) NOT NULL,
  `courseid` varchar(9) NOT NULL,
  PRIMARY KEY (`questionid`)
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=latin1;

CREATE TABLE `score` (
  `scoreid` int(11) NOT NULL AUTO_INCREMENT,
  `chapter` int(11) NOT NULL,
  `courseid` varchar(9) NOT NULL,
  `c_number` varchar(11) NOT NULL,
  `high_score` int(11) NOT NULL,
  `total_score` int(11) NOT NULL,
  PRIMARY KEY (`scoreid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `play_name` varchar(50) NOT NULL,
  `c_number` varchar(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `hash` varchar(32) NOT NULL,
  `avatarnum` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

Set foreign_Key_checks = 0;
drop table if exists course cascade;
drop table if exists users cascade;
drop table if exists question cascade;
drop table if exists score cascade;
Set foreign_Key_checks = 1;

CREATE TABLE `users` (
  `c_number` varchar(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `play_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `hash` varchar(32) NOT NULL,
  `avatarnum` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `laston` datetime DEFAULT NULL,
  `isInstructor` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`c_number`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `course` (
  `courseid` varchar(9) NOT NULL,
  `name` tinytext NOT NULL,
  `instructor` varchar(11) DEFAULT NULL,
  `c_number` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`courseid`),
  KEY `user_course_fk_idx` (`c_number`),
  CONSTRAINT `user_course_fk` FOREIGN KEY (`c_number`) REFERENCES `users` (`c_number`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `question` (
  `questionid` int(11) NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `chapter` int(11) NOT NULL,
  `courseid` varchar(9) NOT NULL,
  PRIMARY KEY (`questionid`),
  KEY `question_course_fk_idx` (`courseid`),
  CONSTRAINT `question_course_fk` FOREIGN KEY (`courseid`) REFERENCES `course` (`courseid`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

CREATE TABLE `score` (
  `scoreid` int(11) NOT NULL AUTO_INCREMENT,
  `chapter` int(11) NOT NULL,
  `courseid` varchar(9) NOT NULL,
  `c_number` varchar(11) NOT NULL,
  `high_score` int(11) NOT NULL,
  `total_score` int(11) NOT NULL,
  PRIMARY KEY (`scoreid`),
  KEY `user_score_fk_idx` (`c_number`),
  KEY `user_coursse_fk_idx` (`courseid`),
  CONSTRAINT `user_coursse_fk` FOREIGN KEY (`courseid`) REFERENCES `course` (`courseid`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `user_score_fk` FOREIGN KEY (`c_number`) REFERENCES `users` (`c_number`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


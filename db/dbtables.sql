Set foreign_Key_checks = 0;
drop table if exists course cascade;
drop table if exists chapter cascade;
drop table if exists users cascade;
drop table if exists question cascade;
drop table if exists score cascade;
drop table if exists invite cascade;
Set foreign_Key_checks = 1;

CREATE TABLE `users` (
  `c_number` varchar(11) NOT NULL,
  `first_name` varchar(50),
  `last_name` varchar(50),
  `play_name` varchar(50) NOT NULL,
  `email` varchar(100),
  `password` varchar(100),
  `hash` varchar(32),
  `avatarnum` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `laston` datetime DEFAULT NULL,
  `isInstructor` tinyint(1) NOT NULL DEFAULT 0,
  `user_volume` decimal(2,1) NOT NULL DEFAULT 0.2,
  PRIMARY KEY (`c_number`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `invite` (
  `inviteid` int(11) NOT NULL AUTO_INCREMENT,
  `invitecode` varchar(40) NOT NULL,
  `email_sentto` varchar(100) UNIQUE NOT NULL,
  `c_number_sentby` varchar(11) NOT NULL,
  PRIMARY KEY (`inviteid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

CREATE TABLE `course` (
  `courseid` varchar(9) NOT NULL,
  `name` tinytext NOT NULL,
  `instructor` varchar(11) DEFAULT NULL,
  `c_number` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`courseid`),
  KEY `user_course_fk_idx` (`c_number`),
  CONSTRAINT `user_course_fk` FOREIGN KEY (`c_number`) REFERENCES `users` (`c_number`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `chapter` (
  `courseid` varchar(9) NOT NULL,
  `chapterid` int(11) NOT NULL,
  `chaptername` tinytext NOT NULL,
  `date_start` varchar(99) NOT NULL,
  `date_end` varchar(99) NOT NULL,
  PRIMARY KEY (`courseid`, `chapterid`),
  KEY `chapter_course_fk_idx` (`courseid`),
  CONSTRAINT `chapter_course_fk` FOREIGN KEY (`courseid`) REFERENCES `course` (`courseid`) ON DELETE NO ACTION ON UPDATE NO ACTION
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
  `game_mode` int(11) NOT NULL DEFAULT 0,
  `times_played` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`scoreid`),
  KEY `user_score_fk_idx` (`c_number`),
  KEY `user_coursse_fk_idx` (`courseid`),
  CONSTRAINT `user_coursse_fk` FOREIGN KEY (`courseid`) REFERENCES `course` (`courseid`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `user_score_fk` FOREIGN KEY (`c_number`) REFERENCES `users` (`c_number`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

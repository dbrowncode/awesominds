CREATE TABLE `awesominds`.`question` (
  `questionid` INT NOT NULL AUTO_INCREMENT,
  `question` TEXT(65000) NOT NULL,
  `chapter` INT NOT NULL,
  `courseid` INT NOT NULL,
  PRIMARY KEY (`questionid`));
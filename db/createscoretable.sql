CREATE TABLE `awesominds`.`score` (
  `scoreid` INT NOT NULL AUTO_INCREMENT,
  `chapter` INT NOT NULL,
  `courseid` INT NOT NULL,
  `c_number` varchar(11) NOT NULL,
  `high_score` INT NOT NULL,
  `total_score` INT NOT NULL,
  PRIMARY KEY (`scoreid`));

<?php
  Require("../../conn.php");
  //TODO change db to handle proper course codes
  //force values to be certain way
  $insertCourseID = (int)$_POST['courseID'];
  $insertCourseName = $_POST['courseName'];
  $stmt = $dbcon->prepare("INSERT INTO course (courseid, name) VALUES(:courseid, :name)");
  $stmt->bindParam(':courseid', $insertCourseID);
  $stmt->bindParam(':name', $insertCourseName);
  if($stmt->execute()){
    echo $insertCourseID .':'. $insertCourseName." successfully added to database.";
  }else{
    echo "error inserting course into databse.";
  }
 
?>

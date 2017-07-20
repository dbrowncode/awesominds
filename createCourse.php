<?php
  Require("../../conn.php");
  include('redir-notinstructor.php');

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    $insertCourseID = strtoupper(test_input($_POST['courseID']));
    $insertCourseName = test_input($_POST['courseName']);
    $stmt = $dbcon->prepare("INSERT INTO course (courseid, name) VALUES(:courseid, :name)");
    $stmt->bindParam(':courseid', $insertCourseID);
    $stmt->bindParam(':name', $insertCourseName);
    if($stmt->execute()){
      echo $insertCourseID .':'. $insertCourseName." successfully added to database.";
    }else{
      echo "Error inserting course into database.";
    }
  }

?>

<?php
  Require("../../conn.php;");
  //TODO get variables from JQUERY call.


  $stmt = $dbconn->prepare("INSERT INTO 'course' (courseid, name) VALUES null, :courseID, :name)");
  $stmt->bindParam(':courseID', $insertCourseID);
  $stmt->bindParam(':name', $insertCourseName);
  if($stmt->execute()){
    echo "$insertCourseID: $insertCourseName successfully added to database.";
  }else{
    echo "error inserting course into databse.";
  }
?>

<?php
  Require("../../conn.php");
  include('redir-notinstructor.php');

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    $insertCourseID = strtoupper(test_input($_POST['courseID']));
    $insertCourseName = test_input($_POST['courseName']);
    $stmt = $dbcon->prepare("INSERT INTO course (courseid, name, regcode) VALUES(:courseid, :name, :regcode)");
    $stmt->bindParam(':courseid', $insertCourseID);
    $stmt->bindParam(':name', $insertCourseName);
    $stmt->bindParam(':regcode', sha1(uniqid($insertCourseID, true)));
    if($stmt->execute()){
      $stmt2 = $dbcon->prepare("INSERT INTO usercoursereg (courseid, c_number) VALUES(:courseid, :c_number)");
      $stmt2->bindParam(':courseid', $insertCourseID);
      $stmt2->bindParam(':c_number', $_SESSION['c_number']);
      $stmt2->execute();
      echo $insertCourseID .':'. $insertCourseName." successfully added to database.";
    }else{
      echo "Error inserting course into database.";
    }
  }

?>

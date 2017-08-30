<?php
  Require("../../conn.php");
  include('redir-notinstructor.php');

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    $courseid = strtoupper(test_input($_POST['courseid']));
    $chapterid = test_input($_POST['chapterid']);
    $chaptername = test_input($_POST['chaptername']);
    $date_start = test_input($_POST['date_start']);
    $date_end = test_input($_POST['date_end']);

    $stmt = $dbcon->prepare("INSERT INTO chapter (courseid, chapterid, chaptername, date_start, date_end) VALUES (:courseid, :chapterid, :chaptername, :date_start, :date_end)");

    $stmt->bindParam(':courseid', $courseid);
    $stmt->bindParam(':chapterid', $chapterid);
    $stmt->bindParam(':chaptername', $chaptername);
    $stmt->bindParam(':date_start', $date_start);
    $stmt->bindParam(':date_end', $date_end);

    if($stmt->execute()){
      echo $chapterid .':'. $chaptername." successfully added to database.";
    }else{
      echo "Error inserting chapter into database.";
      print_r($stmt->errorInfo());
    }
  }

?>

<?php
  require('../../conn.php');
  session_start();
  $query = $dbcon->prepare("INSERT INTO score (chapter, courseid, c_number, high_score, total_score) VALUES (:chapter, :courseid, :c_number, :high_score, :total_score)");

  $query->bindParam(':chapter', $_POST["chapter"]);
  $query->bindParam(':courseid', $_POST["courseid"]);
  $query->bindParam(':c_number', $_SESSION["c_number"]);
  $query->bindParam(':high_score', $_POST["high_score"]);
  $query->bindParam(':total_score', $_POST["total_score"]);
  $result = $query->execute();

  if($result){
    echo json_encode($result);
  } else {
    echo json_encode($query->errorInfo());
  }
?>

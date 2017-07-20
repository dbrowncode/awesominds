<?php
  require('../../conn.php');
  include('redir-notinstructor.php');
  
  $query = $dbcon->prepare("UPDATE score SET high_score = :high_score, total_score = :total_score WHERE courseid = :courseid AND chapter = :chapter AND c_number = :c_number");

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

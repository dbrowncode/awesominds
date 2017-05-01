<?php
  require('../../conn.php');
  //A POST request with "courseid" and "chapter" parameters will return an array of all questions for that course and chapter
  $query = $dbcon->prepare("SELECT question FROM question WHERE courseid = :courseid AND chapter = :chapter");
  $query->bindParam(':courseid', $_POST["courseid"]);
  $query->bindParam(':chapter', $_POST["chapter"]);
  $query->execute();

  $result = $query->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($result);
?>

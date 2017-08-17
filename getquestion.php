<?php
  require('../../conn.php');
  include('redir-notloggedin.php');

  //A GET request with "courseid" and "chapter" parameters will return an array of all questions for that course and chapter
  $query = $dbcon->prepare("SELECT questionid, question FROM question WHERE courseid = :courseid AND chapter = :chapter");
  $query->bindParam(':courseid', $_GET["courseid"]);
  $query->bindParam(':chapter', $_GET["chapter"]);
  $query->execute();

  $result = $query->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($result);
?>

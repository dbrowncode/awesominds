<?php
  require('../../conn.php');
  session_start();
  $query = $dbcon->prepare("SELECT * FROM score WHERE courseid = :courseid AND chapter = :chapter AND c_number = :c_number");
  $query->bindParam(':courseid', $_GET["courseid"]);
  $query->bindParam(':chapter', $_GET["chapter"]);
  $query->bindParam(':c_number', $_SESSION["c_number"]);
  $query->execute();

  $result = $query->fetchAll(PDO::FETCH_ASSOC);

  echo json_encode($result[0]);
?>

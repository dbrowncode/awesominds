<?php
//get all scores for a particular course and chapter (currently regardless of game mode)
  require('../../conn.php');
  session_start();
  $query = $dbcon->prepare("SELECT * FROM score WHERE courseid = :courseid AND chapter = :chapter ORDER BY total_score DESC");
  $query->bindParam(':courseid', $_GET["courseid"]);
  $query->bindParam(':chapter', $_GET["chapter"]);
  $query->execute();

  $result = $query->fetchAll(PDO::FETCH_ASSOC);

  echo json_encode($result);
?>

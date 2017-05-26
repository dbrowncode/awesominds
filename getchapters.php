<?php
  require('../../conn.php');
  //TODO: change this to query the course table when it exists
  $query = $dbcon->prepare("SELECT DISTINCT chapter FROM question WHERE courseid = :courseid");
  $query->bindParam(':courseid', $_GET["courseid"]);
  $query->execute();

  $result = $query->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($result);
?>

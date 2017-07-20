<?php
  require('../../conn.php');
  include('redir-notloggedin.php');

  $query = $dbcon->prepare("SELECT * FROM score WHERE courseid = :courseid AND chapter = :chapter AND c_number = :c_number AND game_mode = :game_mode");
  $query->bindParam(':courseid', $_GET["courseid"]);
  $query->bindParam(':chapter', $_GET["chapter"]);
  $query->bindParam(':c_number', $_SESSION["c_number"]);
  $query->bindParam(':game_mode', $_GET["game_mode"]);
  $query->execute();

  $result = $query->fetchAll(PDO::FETCH_ASSOC);

  echo json_encode($result[0]);
?>

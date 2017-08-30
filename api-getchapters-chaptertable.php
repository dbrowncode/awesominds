<?php
  require('../../conn.php');
  include('redir-notloggedin.php');

  $query = $dbcon->prepare("SELECT * FROM chapter WHERE courseid = :courseid ORDER BY chapterid");
  $query->bindParam(':courseid', $_GET["courseid"]);
  $query->execute();

  $result = $query->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($result);
?>

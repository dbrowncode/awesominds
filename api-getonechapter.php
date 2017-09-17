<?php
  require('../../conn.php');
  include('redir-notloggedin.php');

  $query = $dbcon->prepare("SELECT * FROM chapter WHERE chapterid = :chapter");
  $query->bindParam(':chapter', $_GET["chapter"]);
  $query->execute();

  $result = $query->fetch(PDO::FETCH_ASSOC);
  echo json_encode($result);
?>

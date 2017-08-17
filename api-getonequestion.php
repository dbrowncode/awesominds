<?php
  require('../../conn.php');
  include('redir-notloggedin.php');

  $query = $dbcon->prepare("SELECT question FROM question WHERE questionid = :questionid");
  $query->bindParam(':questionid', $_GET["qid"]);
  $query->execute();

  $result = $query->fetch(PDO::FETCH_ASSOC);
  echo json_encode($result);
?>

<?php
  require('../../conn.php');
  include('redir-notinstructor.php');

  $query = $dbcon->prepare("DELETE FROM question WHERE questionid = :questionid");
  $query->bindParam(':questionid', $_POST["questionid"]);
  $query->execute();

  $result = $query->fetch(PDO::FETCH_ASSOC);
  echo json_encode($result);
?>

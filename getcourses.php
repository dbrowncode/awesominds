<?php
  require('../../conn.php');
  include('redir-notloggedin.php');

  $query = $dbcon->prepare("SELECT * FROM course");
  $query->execute();

  $result = $query->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($result);
?>

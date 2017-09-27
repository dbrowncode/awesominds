<?php
  require('../../conn.php');
  include('redir-notloggedin.php');

  $query = $dbcon->prepare("SELECT * FROM course WHERE courseid IN (SELECT DISTINCT courseid FROM usercoursereg WHERE c_number = :c_number)");
  $query->bindParam(':c_number', $_SESSION['c_number']);
  $query->execute();

  $result = $query->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($result);
?>

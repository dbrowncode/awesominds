<?php
  require('../../conn.php');
  include('redir-notinstructor.php');
  
  $query = $dbcon->prepare("UPDATE users SET isInstructor = 1 WHERE c_number = :c_number");

  $query->bindParam(':c_number', test_input($_POST["cnumber"]));

  $result = $query->execute();

  if($result){
    echo json_encode($query->rowCount());
  } else {
    echo json_encode($query->errorInfo());
  }
?>

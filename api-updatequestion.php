<?php
require('../../conn.php');
include('redir-notinstructor.php');

$stmt = $dbcon->prepare('UPDATE question SET question = :question WHERE questionid = :questionid');
$stmt->bindParam(':question', json_encode($_POST["questionBank"]));
$stmt->bindParam(':questionid', $_POST["questionid"]);

if($stmt->execute()){
  echo 'Question saved';
}else{
  print_r($stmt->errorInfo());
}

?>

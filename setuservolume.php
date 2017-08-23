<?php
  require('../../conn.php');
  include('redir-notloggedin.php');

  if(isset($_POST["user_volume"])){

    $_SESSION["user_volume"] = $_POST["user_volume"];

    $query = $dbcon->prepare("UPDATE users SET user_volume = :user_volume WHERE c_number = :c_number");

    $query->bindParam(':user_volume', $_SESSION["user_volume"]);
    $query->bindParam(':c_number', $_SESSION["c_number"]);

    $result = $query->execute();

    echo json_encode($_SESSION);
  }else{
    http_response_code(404);
  }
?>

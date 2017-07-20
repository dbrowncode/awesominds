<?php
  include('redir-notloggedin.php');
  if(isset($_POST["course"])){
    $_SESSION["course"] = $_POST["course"];
    echo json_encode($_SESSION);
  }else{
    http_response_code(404);
  }
?>

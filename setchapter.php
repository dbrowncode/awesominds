<?php
  include('redir-notloggedin.php');
  if(isset($_POST["chapterid"])){
    $_SESSION["chapterid"] = $_POST["chapterid"];
    echo json_encode($_SESSION);
  }else{
    http_response_code(404);
  }
?>

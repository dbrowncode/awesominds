<?php
// place this file outside of the web root, ideally 2 levels above index.php if you don't want to edit all the links to it in the other files.
// fill in your database credentials here
  // $dbname = 'mysql:dbname=awesominds;host=localhost';
  // $user = ' ';
  // $password = ' ';

  function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  try{
    $dbcon = new PDO($dbname,$user,$password);
  }catch(PDOException $e){
    echo 'Connection Failed' . $e->getMessage();
  }
?>

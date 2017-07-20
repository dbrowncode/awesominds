<?php
session_start();
if(!$_SESSION['logged_in'] || !$_SESSION['active'] || !$_SESSION['isInstructor']){
  header("location: index.php");
}
?>

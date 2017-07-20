<?php
session_start();
if(!$_SESSION['logged_in'] || !$_SESSION['active']){
  header("location: index.php");
}
?>

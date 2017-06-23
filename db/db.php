<?php
/* Database connection settings */
// place this file outside of the web root, ideally 2 levels above index.php if you don't want to edit all the links to it in the other files.
// fill in your database credentials here
// TODO: replace all mysqli code in the login/registration system with PDO statements and use conn.php, and delete this file
$host = 'localhost';
$user = '';
$pass = '';
$db = 'awesominds';
$mysqli = new mysqli($host,$user,$pass,$db) or die($mysqli->error);

<?php
require('../../conn.php');
include('redir-notinstructor.php');
require('insert.php');

$questionBank = $_POST["questionBank"];
$index = 0; //placeholder, maybe not needed here
$insertChapter = $_POST["chapter"];
$courseid = $_POST["courseid"];
insertIntoDB($questionBank, $insertChapter, $courseid, $dbcon, $index);
echo $index . " questions uploaded for chapter " . $insertChapter . " on course: " . $courseid;
?>

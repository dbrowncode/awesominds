<?php

$target_file = basename($_FILES["fileToUpload"]["name"]);
include 'readQuestions.php';

if(!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    exit;  //try detect AJAX request, simply exist if no Ajax
}

if(!isset($_FILES['fileToUpload']) || !is_uploaded_file($_FILES['fileToUpload']['tmp_name'])){
   die('file is Missing!');
}

//on succesful upload call appropriate functions
if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
	read_doc($filename, $temp_file);
	tmpToDb($temp_file,$dbcon);
} else {
	//this will print the file to be uploaded array
	//for debug purposes, TODO remove once finished debugging.
	print_r($_FILES);
  echo "Sorry, there was an error uploading your file.";
}

?>

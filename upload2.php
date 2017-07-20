<?php
Require('../../conn.php');
include('redir-notinstructor.php');
include 'readQuestions.php';
include 'parseDoc.php';
include 'insert.php';

$temp_file = tempnam(sys_get_temp_dir(), 'qs');
$target_file = basename($_FILES["fileToUpload"]["name"]);
$filetype = pathinfo($target_file, PATHINFO_EXTENSION);
$goodToParse = 1;
$removeFile = 0;
$errorUpload = 0;

if(!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    exit;  //try detect AJAX request, simply exist if no Ajax
}

if(!isset($_FILES['fileToUpload']) || !is_uploaded_file($_FILES['fileToUpload']['tmp_name'])){
   die('file is Missing!');
}

switch($filetype){
    case 'doc':
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)){
            read_doc($target_file, $temp_file);
        } else {
            $errorUpload = 1;
        }
        break;
    case 'txt':
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)){
            $temp_file = $target_file;
            $removeFile = 1;
        } else {
            $errorUpload = 1;
        }
        break;
    default:
        die($filetype . "  formats are not currently supported");
        $goodToUpload = 0;
        break;
}
//on succesful upload call appropriate functions

if($errorUpload){
    die("Error uploading File");
}
if(!$gootToParse){
    tmpToDb($temp_file,$dbcon);
    if($removeFile){
        unlink($temp_file);
    }
}
?>

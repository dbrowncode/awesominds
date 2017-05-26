<?php
$target_dir = "";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$FileType = pathinfo($target_file,PATHINFO_EXTENSION);
include 'readQuestions.php';
// Check if file is a file
if(isset($_POST["submit"])) {
    $check = filesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not cool.";
        $uploadOk = 0;
    }
}

// Check if file already exists
//can combine many of these checks to minimze code.
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}

// Allow certain file formats
// Can use this to call different file formats should we go that route
if($FileType != "doc") {
	//ungracious exit of php script.
    echo "Sorry, only .doc file types at this point.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
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
}

?>

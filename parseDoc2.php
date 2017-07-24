<?php
  require 'vendor/autoload.php';
	//takes the uploaded doc file and converts it to tmp file
	//parameters: .doc file to be uploaded, path and name of tmpFile.
  function getEl($elements) {
    foreach ($elements as $key => $value) {
      if(method_exists($value, 'getElements')){
        getEl($value->getElements());
      } else {
        // print_r($value->getText());
        $lines = explode(chr(0x0D),$value->getText());
        foreach ($lines as $line) {
          echo($line . '<br>');
        }
      }
    }
  }
	function read_doc($file) {
    echo "Reading contents from `{$file}`<br>";
    $phpWord = \PhpOffice\PhpWord\IOFactory::load($file, 'MsDoc');
    $sections = $phpWord->getSections();

    foreach ($sections as $key => $value) {
      getEl($value->getElements());
    }
	}
?>

<?php
    //takes the tmp file and parses through it line by line looking for key words
	//loads into JSON then stores in db
	//parameters: file to be parsed <might want to add courseid too, dpeneding on how that's being entered>
    function tmpToDB($temp_file,$dbcon){
      //TODO get from actual source
      //must call session_start() before you can access $_SESSION
      session_start();
      $courseid = $_SESSION["course"];
      $questionBank = array();
      $arrayFilled = array();
		  $index = 0;
		  $questionFile = fopen($temp_file, "r") or die("file not found");
		  //iterate over question document, check if it is a question or an answer. add to appropriate array.
      while(!feof($questionFile)){
        $line = fgets($questionFile);
        //this conversion seems to deal with some windows screwdigglies
        $line =iconv("Windows-1252","UTF-8//IGNORE",$line);
        $line = trim($line);

        //chapter number and name
        if(preg_match("/CHAPTER \d+|CHAPTER \d+:/i",$line,$match)){
          $lineArray =  explode(" ", $line);
          $insertChapter = str_replace(":",'',$lineArray[1]);
          $chapterName = preg_replace("/CHAPTER \d+|CHAPTER \d+:/i","",$line);
	  		}

        //question text
        if(preg_match("/^\d+\)/",$line)){
          $question =  preg_replace("/^\d+\) |^\d+\)/","",$line);
          //this might not always be right conversion string to use, but it works for now
          $question = trim($question);
          $questionBank["question"] = $question;
          $arrayFilled[0] = 1;
	  		}

        //Choice array
        //possibility for more/less than 4 answers
        if(preg_match("/^[A-Z]\)/i", $line, $matches)){
          $choice = trim(preg_replace("/\)/","",$matches[0]));
          $choices[$choice] = trim(preg_replace("/[A-Z]\) |[A-Z]\)/i","",$line));
          $arrayFilled[1] = 1;
        }
		  	//Answer
        if(preg_match("/^ANSWER/i",$line,$match)){
          $answer = preg_replace("/ANSWER |ANSWER:/i","",$line);
          $arrayFilled[2] = 1;
        }
        //If all values set, load into db
        //clear all arrays
        if($arrayFilled[0] == 1 && $arrayFilled[1] == 1 && $arrayFilled[2] == 1){
          $questionBank["question"] = trim($question);
  				$questionBank["choices"] = $choices;
          $questionBank["answer"] = trim($answer);
          unset($choices);
          insertIntoDB($questionBank, $insertChapter, $courseid, $dbcon, $index);
          unset($arrayFilled);
          $arrayFilled = array();
	  		}
		}
		//close db connection
		$dbcon=null;
		fclose($questionFile);
		echo $index . " questions uploaded for chapter " . $insertChapter . " on course: " . $courseid;
	}
?>

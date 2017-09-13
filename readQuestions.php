<?php
  //takes the tmp file and parses through it line by line looking for key words
	//loads into JSON then stores in db
	//parameters: file to be parsed, db connection
  function tmpToDB($temp_file,$dbcon){
    //must call session_start() before you can access $_SESSION
    session_start();
    $courseid = $_SESSION["course"];
    $insertChapter = $_SESSION["chapterid"];
    $questionBank = array();
    $arrayFilled = array();
	  $index = 0;
	  $questionFile = fopen($temp_file, "r") or die("file not found");
    $allQuestions = array();
    $allQuestionsIndex = 0;
    $hasAnswerKey = false;
    $allAnswers = array();
    $allAnswersIndex = 0;

    while(!feof($questionFile)){ //initial read to check if theres a separate answer key
      $line = fgets($questionFile);
      $line = iconv("Windows-1252","UTF-8//IGNORE",$line);
      $line = trim($line);
      $line = preg_replace('/[^\PC\s]/u', "\n", $line);
      if(preg_match("/^ANSWER KEY/i", strtoupper($line), $matches)){
        $hasAnswerKey = true;
        continue;
      }
      if($hasAnswerKey){
        if(preg_match("/^\d+\)|^\d+\./",$line)){ //found an answer
          $answer = strtoupper(preg_replace("/\d+\)|\d+\./","",$line));
          if(preg_match("/[A-Z]/i", $answer, $answermatches)){
            $answer = $answermatches[0];
            $allAnswers[$allAnswersIndex++] = trim($answer);
          }
	  		}
      }
    }
    fclose($questionFile);

	  //iterate over question document again, check if it is a question or an answer. add to appropriate array.
    $questionFile = fopen($temp_file, "r") or die("file not found");
    while(!feof($questionFile)){
      $line = fgets($questionFile);
      $line = iconv("Windows-1252","UTF-8//IGNORE",$line);
      $line = trim($line);
      $line = preg_replace('/[^\PC\s]/u', "\n", $line);

      //question text
      if(!$arrayFilled[0]){
        if(preg_match("/^\d+\)|^\d+\./",$line)){ //found a question
          $question = preg_replace("/^\d+\) |^\d+\)|^\d+\.|^\d+\. /","",$line);
          $question = trim($question);
          $arrayFilled[0] = 1;
	  		}
      }

      //Choice array
      //possibility for more/less than 4 answers
      if($arrayFilled[0] && !$arrayFilled[2]){
        if(preg_match("/^[A-Z]\)|^[A-Z]\./i", $line, $matches)){
          $lineArray =  explode("\n", $line);
          foreach ($lineArray as $k=>$value) {
            if(preg_match("/^[A-Z]\)|^[A-Z]\./i", $value, $valmatches)){
              if(strlen($value)>strlen($valmatches[0])){
                // choice text likely on same line
                $choice = strtoupper(trim(preg_replace("/\)|\./","",$valmatches[0])));
                $choices[$choice] = trim(preg_replace("/[A-Z]\) |[A-Z]\)|^[A-Z]\.|^[A-Z]\. /i","",$value));
              } else {
                //choice text on next line
                $choice = strtoupper(trim(preg_replace("/\)|\./","",$valmatches[0])));
                $choices[$choice] = trim(preg_replace("/[A-Z]\) |[A-Z]\)|^[A-Z]\.|^[A-Z]\. /i","",$lineArray[$k+1]));
              }
            }
          }
          $arrayFilled[1] = 1;
        }
      }

      if($arrayFilled[0] && $arrayFilled[1] && !$arrayFilled[2]){
        $questionBank["choices"] = $choices;
        if(!isset($questionBank["question"])){
          $questionBank["question"] = trim($question);
          $allQuestions[$allQuestionsIndex++] = $questionBank;
        }
        $allQuestions[$allQuestionsIndex-1]["choices"] = $choices;
		  	//Answer
        if(!$hasAnswerKey){
          if(preg_match("/^ANSWER:|^ANS:/i",$line,$match)){
            $answer = strtoupper(preg_replace("/ANSWER |ANSWER:|ANS |ANS:/i","",$line));
            if(preg_match("/[A-Z]/i", $answer, $answermatches)){
              $answer = $answermatches[0];
              $allQuestions[$allQuestionsIndex-1]["answer"] = trim($answer);
              unset($choices);
              $questionBank = array();
              $arrayFilled = array();
            }
          }
        }else{
          if(preg_match("/^\d+\)|^\d+\./",$line)){ //found a question
            unset($choices);
            $questionBank = array();
            $arrayFilled = array();
            $question = preg_replace("/^\d+\) |^\d+\)|^\d+\.|^\d+\. /","",$line);
            $question = trim($question);
            $arrayFilled[0] = 1;
  	  		}
        }
      }
		}

    //loop through the (hopefully now populated) allQuestions array and insert them into the db
    $i = 0;
    foreach ($allQuestions as $key => $value) {
      if($hasAnswerKey) $value["answer"] = $allAnswers[$i++]; //add answers from answer key if needed
      insertIntoDB($value, $insertChapter, $courseid, $dbcon, $index);
    }

		//close db connection
		$dbcon=null;
		fclose($questionFile);
		echo $index . " questions uploaded for chapter " . $insertChapter . " on course: " . $courseid;
	}
?>

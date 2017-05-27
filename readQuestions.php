<?php
    //takes the tmp file and parses through it line by line looking for key words
	//loads into JSON then stores in db
	//parameters: file to be parsed <might want to add courseid too, dpeneding on how that's being entered>
    function tmpToDB($temp_file,$dbcon){
        //TODO get from actual source
        $courseid = 150;
        
        $questionBank = array();
        $arrayFilled = array();
		$index = 0;		
		$questionFile = fopen($temp_file, "r") or die("file not found");
		//iterate over question document, check if it is a question or an answer. add to appropriate array.	
        while(!feof($questionFile)){
            $line = fgets($questionFile);
            //find chapter number
            if(preg_match("/Chapter \d+ |Chapter \d+:/",$line,$match)){    
				$lineArray =  explode(" ", $line);
                $insertChapter = str_replace(":",'',$lineArray[1]);
                $chapterName = preg_replace("/Chapter \d+|Chapter \d+:/","",$line);
			}
		
            //check if it's a question
            if(is_numeric(substr($line,0,1))){
                $question =  preg_replace("/^\d+\) /","",$line);
                $questionBank["question"] = $question;
                $arrayFilled[0] = 1;
			}
			//check if it's a possible answer operates under the assumption a b c and d are the only choices.
			//can probably spruce this up a little nicer. cascading switch statement maybe?
            if(preg_match("/^[ABCDabcd]\) /", $line, $matches)){
                $choice = preg_replace("/\)/","",$matches[0]);
                $choices[$choice] = preg_replace("/^[ABCDabcd]\) /","",$line);
                $arrayFilled[1] = 1;
			}
			//assign questions, choices and answers to question bank.
            if(preg_match("/Answer |Answer:/",$line,$match)){
                $answer = preg_replace("/Answer |Answer:/","",$line);
                $arrayFilled[2] = 1;
            }
            if($arrayFilled[0] == 1 && $arrayFilled[1] == 1 && $arrayFilled[2] == 1){
	            $questionBank["question"] = $question;
				$questionBank["choices"] = $choices;
                $questionBank["answer"] = $answer;
				$insertQuestion = json_encode($questionBank);
                insertIntoDB($insertQuestion, $insertChapter, $courseid, $dbcon, $index);
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

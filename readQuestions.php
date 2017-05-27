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
            $line . '<br>';
            if(preg_match("/CHAPTER \d+|CHAPTER \d+ |CHAPTER \d+:/",strToUpper($line),$match)){    
                $lineArray =  explode(" ", $line);
                $insertChapter = str_replace(":",'',$lineArray[1]);
                $chapterName = preg_replace("/CHAPTER \d+|CHAPTER \d+:/","",strToUpper($line));
			}
		
            //check if it's a question
            if(is_numeric(substr($line,0,1))){
                $question =  preg_replace("/^\d+\) |^\d+\)/","",$line);
                //this might not always be right conversion string to use, but it works for now
                $question = trim(iconv("Windows-1252","UTF-8//IGNORE",$question));
                $questionBank["question"] = $question;
                $arrayFilled[0] = 1;
                $questionBank["question"] . '<br>';
			}
			
            //check if it's a possible answer operates under the assumption a b c d or e are only answers and are uppperCase
            if(preg_match("/[ABCDE]\) |[A-Z]\)/", strToUpper($line), $matches)){
                $choice = trim(preg_replace("/\)/","",$matches[0]));
                $choices[$choice] = trim(preg_replace("/[A-Z]\) |[A-Z]\)/","",strToUpper($line)));
                $arrayFilled[1] = 1;
            }
			//assign questions, choices and answers to question bank.
            if(preg_match("/ANSWER |ANSWER:/",strToUpper($line),$match)){
                $answer = preg_replace("/Answer |Answer:/","",$line);
                $arrayFilled[2] = 1;
            }
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

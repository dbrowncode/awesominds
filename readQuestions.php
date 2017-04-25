<?php
	// grab filename from get request.
	// ?argument1=yadayada
	//$filename = $_GET['argument1'];
    //$filename .='.doc';
 	$filename = $target_file;
	
	//create temporary file for use
	$temp_file = tempnam(sys_get_temp_dir(), 'qs');
	//grab the doc file and convert it to .txt
	function read_doc($file, $tmp) {
		//open file
		$fileHandle = fopen($file, "r");
		//store whole contents on a single line.
		$line = @fread($fileHandle, filesize($file));   
		//create array of strings based on null character for delimiter and $line string
		$lines = explode(chr(0x0D),$line);
		$outtext = "";
		foreach($lines as $thisline){
			//find the position of the null characters in the string
			$pos = strpos($thisline, chr(0x00));
			//if not null character or string is empty continue
			if (($pos !== FALSE)||(strlen($thisline)==0)){
			} 
			//otherwise add newline to string
			else{
				$outtext .= $thisline."\n";
			}
		}
		fclose($fileHandle); 		
		//create name for new .txt file and write the newly created string to it.
		$tempFile = fopen($tmp,"r+");
		//matche all characters and add to new string 
		$outtext = preg_replace("/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/","",$outtext);
		fputs($tempFile, $outtext);
		//$newfile = "questionFile.txt";
		//file_put_contents($newfile, $outtext);
		rewind($tempFile);
		return $tempFile;
	}
	$scrapeQuestions = read_doc($filename, $temp_file); 
	$index = 1;
	$questionBank = array();
	$questionFile = fopen($temp_file, "r") or die("file not found");
	//iterate over question document, check if it is a question or an answer. add to appropriate array.
	$newQuestion = array($index => '');		
	while(!feof($questionFile)){	
		$line = fgets($questionFile);
		//check if it's a question
		if(is_numeric(substr($line,0,1))){
			$question = substr($line,3);
			$question = trim($question, ' \0\t\n\x0b\r');
			$questionBank["question$index"] = $question;
		}
		//check if it's a possible answer operates under the assumption a b c and d are the only choices.
		//can probably spruce this up a little nicer. cascading switch statement maybe?
		if(strtoupper(substr($line,0,2)) == 'A)' | strtoupper(substr($line,0,2)) == 'B)' | strtoupper(substr($line,0,2)) == 'C)' | strtoupper(substr($line,0,2)) == 'D)'){
			$choice = strtoupper(substr($line,0,1));
			$choice = trim($choice, ' \0\t\n\x0b\r');
			$choices[$choice] = substr($line,3);
			$questionBank["choices$index"] = $choices;			
		}
		//assign questions, choices and answers to question bank.
		if(strtoupper(substr($line,0,6)) == "ANSWER"){
			$answer= substr($line,7,8);
			$answer = trim($answer, ' \0\t\n\x0b\r');
			//php seems to require this before it recognizes A as A.
			$a = ord($answer);
			$a = chr($a);
			$questionBank["question$index"] = $question;
			$questionBank["choices$index"] = $choices;
			//to match text string use
			//$questionBank["answer$index"] = $choices[$a];
			//to match letter use
			$questionBank["answer$index"] = $answer;
			$index+=1;
		}
	}
	//close file
	fclose($questionFile);
	
	//currently don't have permissions on windows box, may change with linux.
	//unlink($temp_file);
	unlink($target_file);
	//don't currently have permissions to delete the file after we're done with it. This can be remedied with linux user permissions.
	//unlink($scrapeQuestions);
	//var_dump($questionBank);
	$qbjson = json_encode($questionBank);
	echo $qbjson;

?>

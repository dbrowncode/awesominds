<?php
	// grab filename from get request.
	// ?argument1=yadayada
	//$filename = $_GET['argument1'];
    //$filename .='.doc';
 	$filename = "gerrig_2ce_tif_sec_ch01.doc";
	//grab the doc file and convert it to .txt
	function read_doc($file) {
		$fileHandle = fopen($file, "r");
		$line = @fread($fileHandle, filesize($file));   
		$lines = explode(chr(0x0D),$line);
		$outtext = "";
		foreach($lines as $thisline)
		  {
			$pos = strpos($thisline, chr(0x00));
			if (($pos !== FALSE)||(strlen($thisline)==0))
			  {
			  } else {
				$outtext .= $thisline."\n";
			  }
		  }
		$outtext = preg_replace("/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/","",$outtext);
		$newfile = "questionFile.txt";
		file_put_contents($newfile, $outtext);
		return $newfile;
	}
	$scrapeQuestions = read_doc($filename); 
	$index = 1;
	$questionBank = array();
	$questionFile = fopen("questionFile.txt", "r") or die("file not found");
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
			$questionBank["answer$index"] = $choices[$a];
			//to match letter use
			//$questionBank["answer$index"] = $answer;
			$index+=1;
			//echo $a;
			//echo '<br>';
		}
	}
	//don't currently have permissions to delete the file after we're done with it. This can be remedied with linux user permissions.
	//unlink($scrapeQuestions);
	//var_dump($questionBank);
	$qbjson = json_encode($questionBank);
	echo $qbjson;

?>

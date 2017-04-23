<?php
	// grab filename from get request.
	// ?argument1=yadayada
	//$filename = $_GET['argument1'];
    //$filename .='.doc';
	//convert document to .doc
	//reliant on document being .txt type.
	$index = 0;
	$questionBank = array();
	$questionFile = fopen("questions.txt", "r") or die("file not found");
	//iterate over question document, check if it is a question or an answer. add to appropriate array.
	$newQuestion = array($index => '');		
	while(!feof($questionFile)){	
		$line = fgets($questionFile);
		//check if it's a question
		if(is_numeric(substr($line,0,1))){
			$question = substr($line,3);
			$question = trim($question, ' \0\t\n\x0b\r');
		}
		//check if it's a possible answer
		if(strtoupper(substr($line,0,2)) == 'A)' | strtoupper(substr($line,0,2)) == 'B)' | strtoupper(substr($line,0,2)) == 'C)' | strtoupper(substr($line,0,2)) == 'D)'){
			$choice = strtoupper(substr($line,0,1));
			$choice = trim($choice, ' \0\t\n\x0b\r');
			$choices[$choice] = substr($line,3);

		}
		//assign questions, choices and answers to question bank.
		if(strtoupper(substr($line,0,7)) == "ANSWER:"){
			$answer= substr($line,8,8);
			$answer = trim($answer, ' \0\t\n\x0b\r');
			$a = ord($answer);
			$a = chr($a);
			echo $choices[$a];
			echo '<br>';
			$questionBank["question$index"] = $question;
			$questionBank["choices$index"] = $choices;
			$questionBank["answer$index"] = $answer;
			$index+=1;		
		}
	}
	//var_dump($questionBank);
	$qbjson = json_encode($questionBank);
	//echo $qbjson;

?>

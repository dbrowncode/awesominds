<?php
	// grab filename from get request.
	// ?argument1=yadayada
	//$filename = $_GET['argument1'];
    //$filename .='.doc';
	//convert document to .doc
	$index = 0;
	$questionBank = array();
	$questionFile = fopen("questions.txt", "r") or die("file not found");
	//iterate over question document, check if it is a question or an answer. add to appropriate array.
	$newQuestion = array($index => '');
	
	$answer = '';
	$q='';
	while(!feof($questionFile)){	
		$line = fgets($questionFile);
			//check if it's a question
		if(is_numeric(substr($line,0,1))){
			$q = substr($line,3);
			//$newQuestion[$index] = $q;
		}
		//check if it's a possible answer
		if(strtoupper(substr($line,0,2)) == 'A)' | strtoupper(substr($line,0,2)) == 'B)' | strtoupper(substr($line,0,2)) == 'C)' | strtoupper(substr($line,0,2)) == 'D)'){
			$i = strtoupper(substr($line,0,2));
			$answers[$i] = substr($line,3);
		}
		//find the correct answer add all components to array
		if(strtoupper(substr($line,0,7)) == "ANSWER:"){
			$answer = substr($line,8);
			$questionBank["question$index"] = $q;
			$questionBank["answers$index"] = $answers;
			$questionBank["answer$index"] = $answer;
			$index+=1;		
		}
	}
	$qbjson = json_encode($questionBank);
	echo $qbjson;

?>

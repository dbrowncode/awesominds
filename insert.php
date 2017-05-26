<?php
function insertIntoDB(&$questionBank,$insertChapter,$courseid,$dbcon, &$index){
    $insertQuestion = json_encode($questionBank);
	$stmt = $dbcon->prepare('INSERT INTO question (questionid, question, chapter, courseid) VALUES (null, :question, :chapter, :courseid)');
	$stmt->bindParam(':question', $insertQuestion);
	$stmt->bindParam(':chapter', $insertChapter);
	$stmt->bindParam(':courseid', $courseid);
	if($stmt->execute()){
        $index += 1;
        unset($questionBank); 
        $questionBank = array();
    }else{
        print_r($stmt->errorInfo());
    
    }

}
?>

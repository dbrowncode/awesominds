<?php
function insertIntoDB($insertQuestion,$insertChapter,$courseid,$dbcon, &$index){
	$stmt = $dbcon->prepare("INSERT INTO question (questionid, question, chapter, courseid) VALUES (null, :question, :chapter, :courseid)");
	$stmt->bindParam(':question', $insertQuestion);
	$stmt->bindParam(':chapter', $insertChapter);
	$stmt->bindParam(':courseid', $courseid);
	if($stmt->execute()){
		$index += 1;
    }else{
        print_r($stmt->errorInfo());
    
    }

}
?>

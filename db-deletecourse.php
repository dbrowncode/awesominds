<?php
  require('../../conn.php');
  include('redir-notinstructor.php');

  $dbcon->setAttribute(PDO::ATTR_EMULATE_PREPARES, 1);

  //due to foreign key constraints, delete score, then question, then course?
  if($_POST["deletingCourse"] == "true"){
    $sqltext = array("DELETE FROM usercoursereg WHERE courseid = :courseid;", "DELETE FROM score WHERE courseid = :courseid;", "DELETE FROM question WHERE courseid = :courseid;", "DELETE FROM chapter WHERE courseid = :courseid", "DELETE FROM course WHERE courseid = :courseid;");
  } else {
    $sqltext = array("DELETE FROM score WHERE courseid = :courseid AND chapter = :chapter;", "DELETE FROM question WHERE courseid = :courseid AND chapter = :chapter;", "DELETE FROM chapter WHERE courseid = :courseid AND chapterid = :chapter;");
  }

  $output = [];
  $output[] = $_POST;
  $output[] = $sqltext;
  foreach ($sqltext as $key => $value) {
    $query = $dbcon->prepare($value);
    $query->bindParam(':courseid', $_POST["courseid"]);

    if($_POST["deletingCourse"] == "false"){
      $query->bindParam(':chapter', $_POST["chapter"]);
    }
    $result = $query->execute();
    if($result){
      // echo json_encode($result);
      $output[] = $result;
    } else {
      // echo json_encode($query->errorInfo());
      $output[] = $query->errorInfo();
    }
  }

  echo json_encode($output);
?>

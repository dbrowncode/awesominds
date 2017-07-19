<?php
//get all scores for a particular course and chapter (currently regardless of game mode)
  require('../../conn.php');
  session_start();
  $query = $dbcon->prepare("SELECT u.first_name, u.last_name, u.c_number, u.play_name, s.total_score
                            FROM users u, score s
                            WHERE s.courseid = :courseid
                            AND s.chapter = :chapter
                            AND u.c_number = s.c_number");
  $query->bindParam(':courseid', $_GET["courseid"]);
  $query->bindParam(':chapter', $_GET["chapter"]);
  $query->execute();

  $result = $query->fetchAll(PDO::FETCH_ASSOC);

  echo json_encode($result);
?>

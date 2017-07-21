<?php
//get all scores for a particular course and chapter (currently regardless of game mode)
  require('../../conn.php');
  include('redir-notinstructor.php');

  $query = $dbcon->prepare("SELECT u.first_name, u.last_name, u.c_number, u.play_name, s.total_score, s.chapter, s.game_mode, s.times_played
                            FROM users u, score s
                            WHERE s.courseid = :courseid
                            AND u.c_number = s.c_number");
  $query->bindParam(':courseid', $_GET["courseid"]);
  $query->execute();

  $result = $query->fetchAll(PDO::FETCH_ASSOC);

  echo json_encode($result);
?>

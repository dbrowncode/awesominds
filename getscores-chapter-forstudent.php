<?php
//get all scores for a particular course and chapter (currently regardless of game mode)
  require('../../conn.php');
  include('redir-notloggedin.php');

  $query = $dbcon->prepare("SELECT u.play_name, s.high_score, s.total_score, s.game_mode, s.times_played
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

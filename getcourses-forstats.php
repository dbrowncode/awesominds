<?php
  require('../../conn.php');
  include('redir-notloggedin.php');

  $query = $dbcon->prepare("SELECT DISTINCT courseid, name
                            FROM (SELECT s.chapter, c.courseid, c.name
                                  FROM course c
                                  INNER JOIN score s ON c.courseid = s.courseid) AS tmp");

  // $query = $dbcon->prepare("SELECT c.courseid, c.name
  //                                 FROM course c
  //                                 INNER JOIN score s ON c.courseid = s.courseid");
  $query->execute();

  $result = $query->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($result);
?>

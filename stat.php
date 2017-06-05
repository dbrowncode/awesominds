<?php
require '../../db.php';

$sql = "SELECT game_id, game_score, high_score FROM score";
$result = $mysqli->query($sql);

if (mysqli_num_rows($result) > 0) {
  while($row = $result->fetch_assoc()){
    echo "game_id: " . $row["game_id"]. " - Game Score: " . $row["game_score"]. " " . $row["high_score"]. "<br>";
  }
    // // output data of each row
    // while($row = mysqli_fetch_assoc($result)) {
    //     echo "game_id: " . $row["game_id"]. " - Game Score: " . $row["game_score"]. " " . $row["high_score"]. "<br>";
    // }
} else {
    echo "0 results";
}
?>

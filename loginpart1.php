<?php
/* User login process, checks if user exists and whether they are instructor or not*/
require '../../db.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
  $c_number = $mysqli->escape_string($_POST['cnumber']);
  $result = $mysqli->query("SELECT * FROM users WHERE c_number='$c_number'");
  if ( $result->num_rows == 0 ){ // User doesn't exist
    $_SESSION['message'] = '<small class="error">User with Camosun ID "'.$c_number.'" doesn\'t exist. Try again or <a href="signup.php">create an account</a>.</small>';
  } else { // User exists
    $user = $result->fetch_assoc();
    $_SESSION['message'] = 'Logging in as ' . $user['c_number'] .'. <a href="">Cancel</a>';
    $_SESSION['c_number'] = $user['c_number'];

    if($user['isInstructor']){
      $_SESSION['loginpart2'] = 2;
    } else {
      $_SESSION['loginpart2'] = 1;

      $avatarKeys = range(1, 18);
      unset($avatarKeys[array_search($user['avatarnum'],$avatarKeys)]);
      shuffle($avatarKeys);
      $avatarKeys = array_slice($avatarKeys, 0, 2);
      array_push($avatarKeys, $user['avatarnum']);
      shuffle($avatarKeys);
      $_SESSION['avatarKeys'] = $avatarKeys;

      $names = array($_POST['name1'], $_POST['name2'], $_POST['name3'], substr($user['play_name'], 0, -3));
      shuffle($names);
      $_SESSION['names'] = $names;
    }
  }
}
header("location: index.php");

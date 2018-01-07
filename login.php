<?php
/* User login process, checks if user exists and password is correct */

$c_number = $mysqli->escape_string($_POST['cnumber']);
$result = $mysqli->query("SELECT * FROM users WHERE c_number='$c_number'");
if ( $result->num_rows == 0 ){ // User doesn't exist
  $_SESSION['message'] = "User with that Camosun I.D doesn't exist!";
  header("location: error.php");
}
else { // User exists
  $user = $result->fetch_assoc();

  if ( password_verify($_POST['password'], $user['password']) ) { //if password matches, put user's info into the session and log in
    $_SESSION['c_number'] = $user['c_number'];
    $_SESSION['play_name'] = $user['play_name'];
    $_SESSION['avatarnum'] = $user['avatarnum'];
    $_SESSION['active'] = $user['active'];
    $_SESSION['isInstructor'] = $user['isInstructor'];
    $_SESSION['user_volume'] = $user['user_volume'];

    // This is how we'll know the user is logged in
    $_SESSION['logged_in'] = true;

    header("location: index.php");
  }
  else {
    $_SESSION['message'] = "You have entered wrong password, try again!";
    header("location: error.php");
  }
}

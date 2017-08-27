<?php
/* User login process part 2, checks password and logs user in if correct */
require '../../db.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
  $c_number = $_SESSION['c_number'];
  $result = $mysqli->query("SELECT * FROM users WHERE c_number='$c_number'");
  if ( $result->num_rows == 0 ){ // User doesn't exist; shouldn't happen if reached part 2 but keeping the logic here just in case
      $_SESSION['message'] = '<small class="error">User with Camosun ID "'.$c_number.'" doesn\'t exist. Try again or <a href="signup.php">create an account</a>.</small>';
  } else { // User exists
    $user = $result->fetch_assoc();

    if($user['isInstructor']){ //instructor, check password
      if ( password_verify($_POST['password'], $user['password']) ) {
        $_SESSION['c_number'] = $user['c_number'];
        $_SESSION['play_name'] = $user['play_name'];
        $_SESSION['avatarnum'] = $user['avatarnum'];
        $_SESSION['active'] = $user['active'];
        $_SESSION['isInstructor'] = $user['isInstructor'];
        $_SESSION['user_volume'] = $user['user_volume'];

        // This is how we'll know the user is logged in
        $_SESSION['logged_in'] = true;
      } else {
        $_SESSION['message'] = 'Incorrect password, try again!<br>Logging in as ' . $user['c_number'] .'. <a href="">Cancel</a>';
        $_SESSION['loginpart2'] = 2;
      }
    } else { //not an instructor, do the student login process
      //check avatar and name of student to log them in
      if( $_POST['avatarSelect'] == $user['avatarnum'] && $_POST['nameSelect'] == substr($user['play_name'], 0, -3)){
        $_SESSION['c_number'] = $user['c_number'];
        $_SESSION['play_name'] = $user['play_name'];
        $_SESSION['avatarnum'] = $user['avatarnum'];
        $_SESSION['active'] = $user['active'];
        $_SESSION['isInstructor'] = $user['isInstructor'];
        $_SESSION['user_volume'] = $user['user_volume'];

        // This is how we'll know the user is logged in
        $_SESSION['logged_in'] = true;
      } else {
        $_SESSION['message'] = 'Incorrect login, try again!';
      }

    }
  }
}
header("location: index.php");

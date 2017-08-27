<?php
/* Registration process, inserts user info into the database
   and sends account confirmation email message
 */

// Escape all $_POST variables to protect against SQL injections
$play_name = $mysqli->escape_string($_POST['fakename']);
$c_number = $mysqli->escape_string($_POST['cnumber']);
$avatarnum = $_POST['avatarnum'];

// Check if user with that c number already exists
$result = $mysqli->query("SELECT * FROM users WHERE c_number='$c_number'") or die($mysqli->error());

// We know user id exists if the rows returned are more than 0
if ( $result->num_rows > 0 ) {
  $_SESSION['message'] = 'User with this Camosun ID already exists!';
  header("location: error.php");
} else { // id doesn't already exist in a database, proceed...
  // add last 2 digits of C number to the display name
  $play_name .= ' ' . substr($c_number, -2);

  // Set session variables to be used on profile.php page
  $_SESSION['email'] = $email;
  $_SESSION['c_number'] = $c_number;
  $_SESSION['first_name'] = $first_name;
  $_SESSION['last_name'] = $last_name;
  $_SESSION['play_name'] = $play_name;
  $_SESSION['avatarnum'] = $avatarnum;

  // active and isInstructor are 0 by DEFAULT (no need to include it here)
  $sql = "INSERT INTO users (play_name, c_number, avatarnum, active) "
       . "VALUES ('$play_name', '$c_number', '$avatarnum', 1)";

  // Add user to the database
  if ( $mysqli->query($sql) ){
    $_SESSION['active'] = 1; //student account doesn't need verification
    $_SESSION['logged_in'] = true; // So we know the user has logged in
  } else {
    $_SESSION['message'] = 'Registration failed!';
  }
  header("location: index.php");
}

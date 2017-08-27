<?php
/* Registration process, inserts user info into the database */

// Escape all $_POST variables to protect against SQL injections
$play_name = $mysqli->escape_string($_POST['fakename']);
$c_number = $mysqli->escape_string($_POST['cnumber']);
$avatarnum = $_POST['avatarnum'];

// Check if user with that c number already exists
$result = $mysqli->query("SELECT * FROM users WHERE c_number='$c_number'") or die($mysqli->error());

// We know user id exists if the rows returned are more than 0
if ( $result->num_rows > 0 ) {
  $_SESSION['message'] = 'User with this Camosun ID already exists!';
} else { // id doesn't already exist in a database, proceed...
  // add last 2 digits of C number to the display name
  $play_name .= ' ' . substr($c_number, -2);

  // Set session variables to be used on profile.php page
  $_SESSION['c_number'] = $c_number;
  $_SESSION['play_name'] = $play_name;
  $_SESSION['avatarnum'] = $avatarnum;

  if(isset($_SESSION['invitecode'])){ //register as instructor and consume invite
    $query = $dbcon->prepare("SELECT invitecode FROM invite WHERE invitecode = :invitecode");
    $query->bindParam(':invitecode', $_SESSION["invitecode"]);
    $query->execute();
    if($query->fetch(PDO::FETCH_ASSOC)){ //code exists, save it in session for now
      $password = $mysqli->escape_string(password_hash($_POST['password'], PASSWORD_BCRYPT));
      $hash = $mysqli->escape_string( md5( rand(0,1000) ) );
      $sql = "INSERT INTO users (play_name, c_number, avatarnum, active, isInstructor, password, hash) "
           . "VALUES ('$play_name', '$c_number', '$avatarnum', 1, 1, '$password', '$hash')";
    } else {
      $_SESSION['message'] = 'Invalid invite code!';
      header("location: index.php");
    }

  } else { //register as student
    $sql = "INSERT INTO users (play_name, c_number, avatarnum, active) "
         . "VALUES ('$play_name', '$c_number', '$avatarnum', 1)";
  }

  // Add user to the database and log them in
  if ( $mysqli->query($sql) ){
    $_SESSION['active'] = 1; //accounts no longer need email verification
    $_SESSION['logged_in'] = 1; // So we know the user has logged in
    if(isset($_SESSION['invitecode'])){
      $inviteCode = $_SESSION['invitecode'];
      $_SESSION['isInstructor'] = 1;
      $mysqli->query("DELETE FROM invite WHERE invitecode='$inviteCode'");
      unset($_SESSION['invitecode']);
    }
  } else {
    $_SESSION['message'] = 'Registration failed!';
  }
}
header("location: index.php");

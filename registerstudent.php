<?php
/* Registration process, inserts user info into the database */

// Escape all $_POST variables to protect against SQL injections
$play_name = $mysqli->escape_string($_POST['fakename']);
$c_number = $mysqli->escape_string($_POST['cnumber']);
$avatarnum = $_POST['avatarnum'];

// Check if user with that c number already exists
$result = $mysqli->query("SELECT * FROM users WHERE c_number='$c_number'");

// We know user id exists if the rows returned are more than 0
if ( $result->num_rows > 0 ) {
  $_SESSION['message'] = 'User with this Camosun ID already exists! Please log in.';
  header("location: index.php");
} else { // id doesn't already exist in a database, proceed...
  // add last 2 digits of C number to the display name
  $play_name .= ' ' . substr($c_number, -2);

  // Set session variables to be used on profile.php page
  $_SESSION['c_number'] = $c_number;
  $_SESSION['play_name'] = $play_name;
  $_SESSION['avatarnum'] = $avatarnum;

  if(isset($_SESSION['invitecode'])){ //register as instructor and consume invite
    $query = $dbcon->prepare("SELECT * FROM invite WHERE invitecode = :invitecode");
    $query->bindParam(':invitecode', $_SESSION["invitecode"]);
    $query->execute();
    if($result = $query->fetch(PDO::FETCH_ASSOC)){ //code exists
      $password = $mysqli->escape_string(password_hash($_POST['password'], PASSWORD_BCRYPT));
      $hash = $mysqli->escape_string( md5( rand(0,1000) ) );
      $email = $result['email_sentto'];
      $sql = "INSERT INTO users (play_name, c_number, avatarnum, email, active, isInstructor, password, hash) "
           . "VALUES ('$play_name', '$c_number', '$avatarnum', '$email', 1, 1, '$password', '$hash')";
    } else { // code does not exist
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
    if(isset($_SESSION['invitecode'])){ //if we got this far with an invite code, it was valid and used to register an instructor. delete the invite
      $inviteCode = $_SESSION['invitecode'];
      $_SESSION['isInstructor'] = 1;
      $mysqli->query("DELETE FROM invite WHERE invitecode='$inviteCode'");
      unset($_SESSION['invitecode']);
    }
    header("location: index.php");
  } else {
    $_SESSION['message'] = 'Registration failed!';
  }
}
// header("location: index.php");

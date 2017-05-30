<?php
/* User login process, checks if user exists and password is correct */

// Escape email to protect against SQL injections
//$email = $mysqli->escape_string($_POST['email']);
//$result = $mysqli->query("SELECT * FROM users WHERE email='$email'");
$c_number = $mysqli->escape_string($_POST['cnumber']);
$result = $mysqli->query("SELECT * FROM users WHERE c_number='$c_number'");
if ( $result->num_rows == 0 ){ // User doesn't exist
    $_SESSION['message'] = "User with that Camosun I.D doesn't exist!";
    header("location: error.php");
}
else { // User exists
    $user = $result->fetch_assoc();

    if ( password_verify($_POST['password'], $user['password']) ) {

	$_SESSION['c_number'] = $user['c_number'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
        $_SESSION['active'] = $user['active'];

        // This is how we'll know the user is logged in
        $_SESSION['logged_in'] = true;

        header("location: questiongame.php");
    }
    else {
        $_SESSION['message'] = "You have entered wrong password, try again!";
        header("location: error.php");
    }
}

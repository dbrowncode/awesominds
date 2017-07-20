<?php
/* Registration process, inserts user info into the database
   and sends account confirmation email message
 */

// Escape all $_POST variables to protect against SQL injections
$first_name = $mysqli->escape_string($_POST['firstname']);
$last_name = $mysqli->escape_string($_POST['lastname']);
$play_name = $mysqli->escape_string($_POST['fakename']);
$c_number = $mysqli->escape_string($_POST['cnumber']);
$email = $mysqli->escape_string($_POST['email']);
$password = $mysqli->escape_string(password_hash($_POST['password'], PASSWORD_BCRYPT));
$hash = $mysqli->escape_string( md5( rand(0,1000) ) );
$avatarnum = $_POST['avatarnum'];

// Check if user with that email already exists
$result = $mysqli->query("SELECT * FROM users WHERE c_number='$c_number'") or die($mysqli->error());

// We know user id exists if the rows returned are more than 0
if ( $result->num_rows > 0 ) {

    $_SESSION['message'] = 'User with this Camosun ID already exists!';
    header("location: error.php");

}
else { // id doesn't already exist in a database, proceed...

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
    $sql = "INSERT INTO users (first_name, last_name, play_name, c_number, email, password, hash, avatarnum) "
            . "VALUES ('$first_name','$last_name', '$play_name', '$c_number', '$email','$password', '$hash', '$avatarnum')";

    // Add user to the database
    if ( $mysqli->query($sql) ){

        $_SESSION['active'] = 0; //0 until user activates their account with verify.php
        $_SESSION['logged_in'] = true; // So we know the user has logged in
        $_SESSION['message'] =

                 "Confirmation link has been sent to $email, please verify
                 your account by clicking on the link in the message!";

        // Send registration confirmation link (verify.php)
        $to      = $email;
        $subject = 'Account Verification (Awesominds)';
        $headers = "From: Awesominds Registration <noreply@gbl.cs.camosun.bc.ca>" . "\r\n" .
                   "Reply-To: noreply@gbl.cs.camosun.bc.ca" . "\r\n" .
                   "X-Mailer: PHP/" . phpversion();
        $message_body = '
        Hello '.$first_name.',

        Thank you for signing up!

        Please click this link to activate your account:

        http://gbl.cs.camosun.bc.ca/awesominds/verify.php?email='.$email.'&hash='.$hash;

        mail( $to, $subject, $message_body, $headers );

        header("location: index.php");

    }

    else {
        $_SESSION['message'] = 'Registration failed!';
        header("location: error.php");
    }

}

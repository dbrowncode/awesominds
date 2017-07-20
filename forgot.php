<?php
/* Reset your password form, sends reset.php password link */
require '../../db.php';
session_start();

// Check if form submitted with method="post"
if ( $_SERVER['REQUEST_METHOD'] == 'POST' )
{
    $c_number = $mysqli->escape_string($_POST['c_number']);
    $result = $mysqli->query("SELECT * FROM users WHERE c_number='$c_number'");

    if ( $result->num_rows == 0 ) // User doesn't exist
    {
        $_SESSION['message'] = "User with that ID doesn't exist!";
        header("location: error.php");
    }
    else { // User exists (num_rows != 0)

        $user = $result->fetch_assoc(); // $user becomes array with user data

        $email = $user['email'];
        $hash = $user['hash'];
        $first_name = $user['first_name'];

        // Session message to display on success.php
        $_SESSION['message'] = "<p>Please check your email for a confirmation link to complete your password reset!</p>";

        // Send registration confirmation link (reset.php)
        $to      = $email;
        $subject = 'Password Reset Link ( Awesominds )';
        $headers = "From: Awesominds Registration <noreply@gbl.cs.camosun.bc.ca>" . "\r\n" .
                   "Reply-To: noreply@gbl.cs.camosun.bc.ca" . "\r\n" .
                   "X-Mailer: PHP/" . phpversion();
        $message_body = '
        Hello '.$first_name.',

        You have requested password reset!

        Please click this link to reset your password:

        http://gbl.cs.camosun.bc.ca/awesominds/reset.php?c_number='.$c_number.'&hash='.$hash;

        mail($to, $subject, $message_body, $headers);

        header("location: success.php");
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Reset Your Password</title>
  <?php include 'css/css.html'; ?>
</head>

<body>

  <div class="form">

    <h1>Reset Your Password</h1>

    <form action="forgot.php" method="post">
     <div class="field-wrap">
      <label>
        Camosun ID<span class="req">*</span>
      </label>
      <input type="text" required autocomplete="off" name="c_number"/>
    </div>
    <button class="button button-block"/>Reset</button>
    </form>
  </div>

<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src="js/index.js"></script>
</body>

</html>

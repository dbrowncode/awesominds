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
        header("location: index.php");
    }
    else { // User exists (num_rows != 0)

        $user = $result->fetch_assoc(); // $user becomes array with user data

        $email = $user['email'];
        $hash = $user['hash'];

        // Session message to display on success.php
        $_SESSION['message'] = "<p>Please check your email for a link to complete your password reset!</p>";

        // Send registration confirmation link (reset.php)
        $to      = $email;
        $subject = 'Password Reset Link ( Awesominds )';
        $headers = "From: Awesominds Registration <noreply@gbl.cs.camosun.bc.ca>" . "\r\n" .
                   "Reply-To: noreply@gbl.cs.camosun.bc.ca" . "\r\n" .
                   "X-Mailer: PHP/" . phpversion();
        $message_body = '

        A password reset has been requested on your Awesominds account.

        Please click this link to reset your password:

        http://gbl.cs.camosun.bc.ca/awesominds/reset.php?c_number='.$c_number.'&hash='.$hash;

        mail($to, $subject, $message_body, $headers);

        header("location: index.php");
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Reset Your Password - Awesominds</title>
  <?php include 'css/css.html'; ?>
</head>

<body>
  <?php include 'inst-nav2.php'; ?>
  <div class="container text-center">
    <h2>Reset Your Password</h2>
    <p>Enter your Camosun ID to receive a password reset link via the email address you registered from.</p>
    <form action="forgot.php" method="post">
      <div class="form-group container" id="loginPart1" style="max-width: 400px;">
        <label for="cnumberInput" class="form-label"><b>Camosun ID*</b></label>
        <div class="input-group">
          <input class="form-control" type="text" required autocomplete="off" name="c_number" id="cnumberInput"/>
          <span class="input-group-btn"><button class="btn btn-primary" id="resetBtn">Submit</button></span>
        </div>
      </div>
    </form>
  </div>
</body>

</html>

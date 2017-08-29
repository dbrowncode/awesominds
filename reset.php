<?php
/* The password reset form, the link to this page is included
   from the forgot.php email message
*/
require '../../db.php';
session_start();

// Make sure email and hash variables aren't empty
if( isset($_GET['c_number']) && !empty($_GET['c_number']) AND isset($_GET['hash']) && !empty($_GET['hash']) ){
  $c_number = $mysqli->escape_string($_GET['c_number']);
  $hash = $mysqli->escape_string($_GET['hash']);

  // Make sure user email with matching hash exist
  $result = $mysqli->query("SELECT * FROM users WHERE c_number='$c_number' AND hash='$hash'");

  if ( $result->num_rows == 0 ) {
    $_SESSION['message'] = "You have entered invalid URL for password reset!";
    header("location: index.php");
  }
} else {
  $_SESSION['message'] = "Invalid password reset link!";
  header("location: index.php");
}
?>
<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Reset Your Password - Awesominds</title>
  <?php include 'css/css.html'; ?>
</head>

<body>
  <?php include 'inst-nav2.php' ?>
  <div class="container text-center">

    <h2>Choose Your New Password</h2>
    <?php if( isset($_SESSION['message']) AND !empty($_SESSION['message']) ){
      echo $_SESSION['message'];
      unset($_SESSION['message']);
    } ?>

    <form action="reset_password.php" method="post">
      <div class="form-group container" id="loginPart2" style="max-width: 400px;">
        <label for="passwordInput" class="form-label"><b>New Password*</b></label>
        <input class="form-control" type="password" required autocomplete="off" name="newpassword" id="passwordInput"/>
        <label for="passwordConfirm" class="form-label"><b>Confirm New Password*</b></label>
        <input class="form-control" type="password" required autocomplete="off" name="confirmpassword" id="passwordConfirm"/>
      </div>

      <!-- This input field is needed, to get the email of the user -->
      <input type="hidden" name="c_number" value="<?= $c_number ?>">
      <input type="hidden" name="hash" value="<?= $hash ?>">

      <button class="btn btn-primary"/>Change Password</button>
    </form>
  </div>
</body>
</html>

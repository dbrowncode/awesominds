<!doctype html>
<html lang="en">
<head>
<link href="https://fonts.googleapis.com/css?family=Roboto+Mono" rel="stylesheet">
  <meta charset="UTF-8" />
  <?php
    // get db connection and start session
    require '../../db.php';
    session_start();

    //if devvars exist, turn on dev mode
    $_SESSION['devmode'] = file_exists('js/devvars.js');

    //include the profile or login page as appropriate
    if($_SESSION['logged_in']){
      include('profileinner.php');
    }else{
      include('loginform.php');
    }
  ?>
</body>
</html>

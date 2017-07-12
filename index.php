<!doctype html>
<html lang="en">
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
</html>

<!doctype html>
<html lang="en">
  <?php
    session_start();
    //if devvars exist, turn on dev mode
    $_SESSION['devmode'] = file_exists('js/devvars.js');

    //store regcode if user got here from a course registration link
    if(isset($_GET['regcode'])) $_SESSION['regcode'] = $_GET['regcode'];

    //include the profile or login page as appropriate
    if($_SESSION['logged_in']){
      include('profileinner.php');
    }else{
      include('loginform2.php');
    }
  ?>
</html>

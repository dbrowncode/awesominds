<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
  <?php
    require '../../db.php';
    session_start();
    print_r($_SESSION);
    if($_SESSION['logged_in']){
      include('profileinner.php');      
    }else{
      include('loginform.php');
    }
  ?>
</body>
</html>

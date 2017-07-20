<!DOCTYPE html>
<html>
<head>
  <?php
    session_start();
    if(!$_SESSION['logged_in'] || !$_SESSION['active'] || !$_SESSION['isInstructor']){
      header("location: index.php");
    }
    include 'css/css.html';
  ?>
  <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
</head>
<body>
  <a href="index.php"><i class="fa fa-home fa-2x" aria-hidden="true"></i></a>

  <div class="formWrap form">
    <?php include 'inst-nav.php' ?>
    <div class='permission tab-content' id="grant">
      <form action="upload2.php" method="post" id='permissions'>

          <p>Grant instructor permissions to another account to let them access this area and add courses and questions.</p>
          <p>Instructor C number:<input type="text" name="instructorC" id="instructorC" required ="true" placeholder="CXXXXXXX"></p>

        <input type="submit" class='button button-block' value="Give Permissions" name'grantPermission'>
      </form>
    </div>
    <p id="output"></p>
  </div>

<!--jquery goes here-->
<script>
  $('#permissions').submit(function (e) {
    e.preventDefault();
    $.ajax({
      type: 'POST',
      url: 'setpermissions.php',
      data: { cnumber: $('#instructorC').val() },
      success: function(data) {
        if(data == 0){
          $('#output').html('Error: user does not exist or is already a valid instructor');
        }else{
          $('#output').html('User ' + $('#instructorC').val() + ' successfully granted permissions!');
        }

        console.log(data);
      },
      error: function(data) {
        //$('#output').html(data);
        console.log(data);
      },
    });
  });
</script>
</body>
</html>

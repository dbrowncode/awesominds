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
</head>
<body>
  <?php include 'inst-nav2.php' ?>
  <div class="container text-center">
    <div class="formWrap form">
      <h2>Give Permissions</h2><br>
      <div class='permission tab-content' id="grant">
        <form method="post" id='permissions'>

            <p>Grant instructor permissions to another active account to let them access the Instructor Options.</p>
            <p>Instructor C number: <input type="text" name="instructorC" id="instructorC" required ="true" placeholder="CXXXXXXX"></p>

          <input type="submit" class='button button-block' value="Give Permissions" name'grantPermission'>
        </form>
      </div>
      <p id="output"></p>
    </div>
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

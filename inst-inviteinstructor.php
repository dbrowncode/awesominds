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
    <h2>Invite Instructor</h2>
    <p>To invite another instructor to create their own courses/games, enter their email address here.<br>They will be sent a link that will let them create their own Awesominds instructor account.</p>
    <form method="post" autocomplete="off" id="inviteForm">
      <div class="form-group container" style="max-width: 400px;">
        <label for="emailInput" class="form-label"><b>Email Address*</b></label>
        <div class="input-group">
          <input class="form-control" type="email" required autocomplete="off" name="email" id="emailInput"/>
          <span class="input-group-btn"><button class="btn btn-primary" name="invite" id="inviteBtn">Send Invite</button></span>
        </div>
        <p id="output"></p>
      </div>
    </form>
  </div>

<script>
  $('#inviteForm').submit(function (e) {
    e.preventDefault();
    $.ajax({
      type: 'POST',
      url: 'api-inviteinstructor.php',
      data: { email: $('#emailInput').val() },
      success: function(data) {
        if(data == 1){
          $('#output').html('Invite sent!');
        } else if (~data.indexOf("Duplicate entry")){
          $('#output').html('User has already been invited');
        }
      }
    });
  });
</script>
</body>
</html>

<head>
<meta charset="UTF-8" />
<title>Create Account - Awesominds</title>
<?php
  require '../../db.php';
  require '../../conn.php';
  session_start();
  if($_SESSION["logged_in"]){
    header("location: index.php");
  }
  include 'css/css.html';
?>
</head>

<?php
if (isset($_GET['invitecode'])){  //check if invite code is valid for instructor registration
  $query = $dbcon->prepare("SELECT invitecode FROM invite WHERE invitecode = :invitecode");
  $query->bindParam(':invitecode', $_GET["invitecode"]);
  $query->execute();
  if($query->fetch(PDO::FETCH_ASSOC)){ //code exists, save it in session for now
    $_SESSION['invitecode'] = $_GET['invitecode'];
  }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  if (isset($_POST['register'])) { //user registering
    require 'registerstudent.php';
  }
}
?>
<body>
  <?php include 'inst-nav2.php' ?>
  <script src="js/displaynames.js"></script>
  <script>
    jQuery(document).ready(function($){
      $("#displayNameField").val(displayNames[Math.floor(Math.random() * displayNames.length)]);
  	  $("#random").click(function(){
        $("#displayNameField").val(displayNames[Math.floor(Math.random() * displayNames.length)]);
  	   });
  	});
  </script>
  <div class="container text-center" style="max-width: 400px;">
    <?php
      if (isset($_SESSION['invitecode'])){
        echo '<h2>Create Instructor Account</h2>';
      } else {
        echo '<h2>Create Account</h2>
        <p>Already registered? <a href="index.php">Log in</a></p>';
      }
      if( isset($_SESSION['message']) AND !empty($_SESSION['message']) ){
        echo $_SESSION['message'];
        unset($_SESSION['message']);
      }
    ?>
    <form action="signup.php" method="post" autocomplete="off" id="registerForm">
      <label for="cnumberInput" class="form-label"><b>Camosun ID*</b></label>
      <input class="form-control" type="text" required autocomplete="off" name="cnumber" id="cnumberInput" pattern="[C][0-9]{7}"
      <?php if (isset($_SESSION['c_number_signup'])){
        echo 'value="' .$_SESSION['c_number_signup'].'"';
        unset($_SESSION['c_number_signup']);
      } ?>title="C + 7 numbers, eg 'C0654321'"/><br>

      <label id="displayNameLabel" for="displayNameField"><b>Choose Display Name</b></label>
      <div class="input-group">
        <span class="input-group-btn"><button class="btn btn-success" type="button" id="random">Generate</button></span>
        <input type="text" class="form-control" required autocomplete="off" name='fakename' id="displayNameField" readonly placeholder="Display Name"/>
      </div>
      <p><small>Click 'Generate' until you find a name you like</small></p>

      <label><b>Choose Avatar</b></label>
      <div class="avatars">
      <?php
        $numImages = 18;
        for ($i=0; $i < $numImages; $i++) {
          echo '<img class="avatar-img img-thumbnail" src="assets/oppSmall/oppon' . ($i+1) . '.png" />';
        }
      ?>
      </div>
      <div class="btn-group" role="group">
        <button type="button" value="-" class="imgbtnminus btn btn-secondary" id="imgbtnminus"><i class="fa fa-chevron-down" aria-hidden="true"></i></button>
        <button type="button" value="+" class="imgbtnplus btn btn-secondary" id="imgbtnplus"><i class="fa fa-chevron-up" aria-hidden="true"></i></button>
      </div>
      <p><small>Click the <i class="fa fa-chevron-up" aria-hidden="true"></i> and <i class="fa fa-chevron-down" aria-hidden="true"></i> buttons until you find a character you like</small></p>
      <input type="hidden" name="avatarnum" value="1" />

      <?php
      if (isset($_SESSION['invitecode'])) {
        echo '<label for="passwordInput" class="form-label"><b>Password*<b></label>
              <input class="form-control" type="password" required autocomplete="off" name="password" id="passwordInput" pattern=".{8,}" title="Minimum 8 Characters"/><br>';
      } else {
        echo '<p><b>Remember your Display Name and Avatar!</b><br>You will need these to log in.</p>';
      }
      ?>
      <button type="submit" class="btn btn-primary" name="register" />Create Account</button>
    </form>
  </div>
</body>

<script>
jQuery(document).ready(function($){
  $(".avatar-img").hide();
  var val = 1;
  $('.avatar-img:eq(' + (val-1) + ')').show();

  $('#imgbtnplus').click(function() {
    if (val < $(".avatars img").length) {
      $('img:eq(' + val + ')').hide();
      val++;
      $('img:eq(' + val + ')').show();
      $('input[name="avatarnum"]').val(val);
    }
  });

  $('#imgbtnminus').click(function() {
    if (val > 1) {
      $('img:eq(' + val + ')').hide();
      val--;
      $('img:eq(' + val + ')').show();
      $('input[name="avatarnum"]').val(val);
    }
  });

  $('form:first *:input[type!=hidden]:first').focus();
});
</script>

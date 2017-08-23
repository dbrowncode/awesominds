<head>
<meta charset="UTF-8" />
<title>Create Account - Awesominds</title>
<?php
  require '../../db.php';
  require '../../conn.php';
  session_start();
  include 'css/css.html';
?>
</head>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  if (isset($_POST['register'])) { //user registering
    require 'register.php';
  }
}
?>
<body>
  <?php include 'inst-nav2.php' ?>
  <script src="js/displaynames.js"></script>
  <script>
    jQuery(document).ready(function($){
  	  $("#random").click(function(){
        $("#displayNameField").val(displayNames[Math.floor(Math.random() * displayNames.length)]);
        $("#displayNameLabel").addClass('active highlight');
  	   });
  	});
  </script>
  <div class="container text-center">
    <h2>Create Account</h2>
    <p>Already registered? <a href="index.php">Log in</a></p>
    <?php
      if( isset($_SESSION['message']) AND !empty($_SESSION['message']) ){
        echo $_SESSION['message'];
        unset($_SESSION['message']);
      }
    ?>
    <form action="signup.php" method="post" autocomplete="off" id="registerForm">
      <div class="top-row">
        <div class="field-wrap">
          <label>
            First Name<span class="req">*</span>
          </label>
          <input type="text" required autocomplete="off" name='firstname' />
        </div>

        <div class="field-wrap">
          <label>
            Last Name<span class="req">*</span>
          </label>
          <input type="text"required autocomplete="off" name='lastname' />
        </div>
      </div>

      <div class="smalltext">Choose Display Name - Click 'Generate' until you find a name you like</div>
      <div class="displayNameButton"><button class="fakename" type="button" name="buttonpassvalue" id="random">Generate</button></div>
      <div class="field-wrap">
        <label id="displayNameLabel">
          Display Name<span class="req"></span>
        </label>
        <input type="text"required autocomplete="off" name='fakename' id="displayNameField" readonly />
      </div>

      <div class="smalltext">Choose Avatar - Click the + and - buttons until you find a character you like</div>
      <div class="field-wrap avatars">
        <input type="button" value="+" class="imgbtnplus" style="width: 120px">
        <?php
          $numImages = 18;
          for ($i=0; $i < $numImages; $i++) {
            echo '<img class="avatar-img" src="assets/oppSmall/oppon' . ($i+1) . '.png" />';
          }
         ?>
        <input type="button" value="-" class="imgbtnminus" style="width: 120px">
        <input type="hidden" name="avatarnum" value="1" />

        <script>
          $(".avatar-img").hide();
          var val = 1;
          $('.avatar-img:eq(' + (val-1) + ')').show();

          $('.imgbtnplus').click(function() {
            if (val < $(".avatars img").length) {
              $('img:eq(' + val + ')').hide();
              val++;
              $('img:eq(' + val + ')').show();
              $('input[name="avatarnum"]').val(val+1);
              console.log(val);
            }
          });

          $('.imgbtnminus').click(function() {
            if (val > 1) {
              $('img:eq(' + val + ')').hide();
              val--;
              $('img:eq(' + val + ')').show();
              $('input[name="avatarnum"]').val(val+1);
              console.log(val);
            }
          });
        </script>
      </div>

      <div class="field-wrap">
        <label>
          Email Address<span class="req">*</span>
        </label>
        <input type="email"required autocomplete="off" name='email' />
      </div>

      <div class="field-wrap">
        <label>
          Camosun ID<span class="req">*</span>
        </label>
        <input type="text"required autocomplete="off" name='cnumber' pattern="[C][0-9]{7}" title="C + 7 numbers, eg 'C0654321'"/>
      </div>

      <div class="field-wrap">
        <label>
          Set A Password<span class="req">*</span>
        </label>
        <input type="password" required autocomplete="off" name='password' pattern=".{8,}" title="Minimum 8 Characters"/>
        <div class="smalltext">Minimum 8 Characters</div>
      </div>

      <button type="submit" class="button button-block" name="register" />Register</button>
    </form>
  </div>
</body>

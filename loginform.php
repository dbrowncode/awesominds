<head>
<meta charset="UTF-8" />
<title>Sign-Up/Login Form</title>
<?php
  /* Main page with two forms: sign up and log in */
  include 'css/css.html';
?>
<link rel="stylesheet" href="css/style.css">
</head>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (isset($_POST['login'])) { //user logging in

        require 'login.php';

    }

    elseif (isset($_POST['register'])) { //user registering

        require 'register.php';

    }
}
?>
<body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="js/displaynames.js"></script>
<script>
  jQuery(document).ready(function($){
	  $("#random").click(function(){
      $("#displayNameField").val(displayNames[Math.floor(Math.random() * displayNames.length)]);
      $("#displayNameLabel").addClass('active highlight');
	   });
	});
</script>

<div class="form">

      <ul class="tab-group">
        <li class="tab"><a href="#signup">Sign Up</a></li>
        <li class="tab active"><a href="#login">Log In</a></li>
      </ul>

      <div class="tab-content">

         <div id="login">
          <h1>Welcome Back!</h1>

          <form action="index.php" method="post" autocomplete="off">

            <div class="field-wrap">
            <label>
              Camosun ID<span class="req">*</span>
            </label>
            <input type="text" required autocomplete="off" name="cnumber"/>
          </div>

          <div class="field-wrap">
            <label>
              Password<span class="req">*</span>
            </label>
            <input type="password" required autocomplete="off" name="password"/>
          </div>

          <p class="forgot"><a href="forgot.php">Forgot Password?</a></p>

          <button class="button button-block" name="login" />Log In</button>

          </form>

        </div>

        <div id="signup">
          <h1>Sign Up to Play Game</h1>

          <form action="index.php" method="post" autocomplete="off">

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
              $numImages = 16;
              for ($i=0; $i < $numImages; $i++) {
                echo '<img class="avatar-img" src="assets/opp2/oppon' . ($i+1) . '.png" width=120/>';
              }
             ?>
            <input type="button" value="-" class="imgbtnminus" style="width: 120px">


            <input type="hidden" name="avatarnum" value="1" />

            <script>
              $("img").hide();
              var val = 0;
              $('img:eq(' + val + ')').show();

              $('.imgbtnplus').click(function() {
                if (val < $(".avatars img").length -1) {
                  $('img:eq(' + val + ')').hide();
                  val++;
                  $('img:eq(' + val + ')').show();
                  $('input[name="avatarnum"]').val(val+1);
                }
              });

              $('.imgbtnminus').click(function() {
                if (val > 0) {
                  $('img:eq(' + val + ')').hide();
                  val--;
                  $('img:eq(' + val + ')').show();
                  $('input[name="avatarnum"]').val(val+1);
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

      </div><!-- tab-content -->

</div> <!-- /form -->
  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

    <script src="js/index.js"></script>
</body>

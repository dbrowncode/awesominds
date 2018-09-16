<head>
<meta charset="UTF-8" />
<title>Log In - Awesominds</title>
<?php
  include 'css/css.html';
  if( $_SESSION['loginpart2'] == 1 ){
    echo '<link rel="stylesheet" href="css/image-picker.css">
          <script src="js/image-picker.min.js"></script>';
  }
?>
</head>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  if (isset($_POST['login'])) { //user logging in
    require 'login.php';
  }
}
?>
<body>
  <?php include 'inst-nav2.php' ?>
  <div class="container text-center">
    <h2>Log In</h2>
    <!-- <p>First time here? <a href="signup.php">Create an account</a></p> -->
    <?php
      if( isset($_SESSION['message']) AND !empty($_SESSION['message']) ){
        echo $_SESSION['message'];
        unset($_SESSION['message']);
      }
      if( isset($_SESSION['loginpart2']) ){
        if($_SESSION['loginpart2'] == 2){ //instructor
          echo '<form action="loginpart2.php" method="post" autocomplete="off" id="loginForm">
                  <div class="form-group container" id="loginPart2" style="max-width: 400px;">
                    <label for="passwordInput" class="form-label"><b>Password*</b></label>
                    <div class="input-group">
                      <input class="form-control" type="password" required autocomplete="off" name="password" id="passwordInput"/>
                      <span class="input-group-btn"><button class="btn btn-primary" name="login" id="loginBtn">Log In</button></span>
                    </div>
                    <small><a href="forgot.php">Forgot Password?</a></small>
                  </div>
                </form>';
        } else { //student
          echo '<form action="loginpart2.php" method="post" autocomplete="off" id="loginForm">
                  <div class="form-group" id="loginPart2">
                    <p>Select the avatar you registered with:</p>
                    <select class="image-picker-1" name="avatarSelect">';
                    for ($i=0; $i < count($_SESSION['avatarKeys']) ; $i++) {
                      echo '<option data-img-src="assets/oppSmall/oppon'.$_SESSION['avatarKeys'][$i].'.png" data-img-class="img-fluid rounded" value="'.$_SESSION['avatarKeys'][$i].'">'.$i.'</option>';
                    }
              echo '</select>
                    <p>Select the display name you registered with:</p>
                    <select class="image-picker-2" name="nameSelect">';
                    for ($i=0; $i < count($_SESSION['names']) ; $i++) {
                      echo '<option data-img-src="" data-img-label="'.$_SESSION['names'][$i].'" value="'.$_SESSION['names'][$i].'">'.$i.'</option>';
                    }
              echo '</select>
                    <button class="btn btn-primary" name="login" id="loginBtn">Log In</button>
                  </div>
                </form>
                <script>
                jQuery(document).ready(function($){
                  $(".image-picker-1").imagepicker()
                  $(".image-picker-2").imagepicker({
                    show_label: true
                  })
                  $(".image_picker_selector").addClass("row");
                  $("li").addClass("col-sm-3 mx-auto");
                });
                </script>';
          unset($_SESSION['avatarKeys'], $_SESSION['names']);
        }
        unset($_SESSION['loginpart2']);
      } else {
        echo '<form action="loginpart1.php" method="post" autocomplete="off" id="loginForm">
                <div class="form-group container" id="loginPart1" style="max-width: 400px;">
                  <label for="cnumberInput" class="form-label"><b>Enter your Camosun C number below</b></label>
                  <div class="input-group">
                    <input class="form-control" type="text" required autocomplete="off" name="cnumber" id="cnumberInput" pattern="[C][0-9]{7}" title="C + 7 numbers, eg \'C0654321\'"/>
                    <input id="name1" name="name1" type="hidden" class="randomName">
                    <input id="name2" name="name2" type="hidden" class="randomName">
                    <input id="name3" name="name3" type="hidden" class="randomName">
                    <script src="js/displaynames.js"></script>
                    <script>
                      jQuery(document).ready(function($){
                        $(".randomName").each(function(){
                          $(this).val(displayNames[Math.floor(Math.random() * displayNames.length)]);
                        });
                      });
                    </script>
                    <span class="input-group-btn"><button class="btn btn-primary" name="login" id="nextBtn">Next</button></span>
                  </div>
                </div>
              </form>';
      }
    ?>
  </div>
</body>
<script>
$(document).ready(function() {
    $('form:first *:input[type!=hidden]:first').focus();
});
</script>

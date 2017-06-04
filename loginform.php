<title>Sign-Up/Login Form</title>
<?php
  /* Main page with two forms: sign up and log in */
  require '../../db.php';
  session_start();
  include 'css/css.html';
?>
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
<script>
	jQuery(document).ready(function($){
	     $("#random").click(function(){
	     var myArray = ['Johnny Depp','Edward Scissorhand','Arnold Schwarzenegger','Jim Carrey','Emma Watson','Daniel Radcliffe','Leonardo DiCaprio','Tom Cruise','Brad Pitt','Charles Chaplin','Morgan Freeman','Tom Hanks','Hugh Jackman','Matt Damon','Sylvester Stallone','Will Smith','Clint Eastwood','Cameron Diaz','George Clooney','Steven Spielberg','Harrison Ford','Robert De Niro','Al Pacino','Robert Downey','Russell Crowe','Liam Neeson','Kate Winslet','Mark Wahlberg','Natalie Portman','Pierce Brosnan','Sean Connery','Orlando Bloom','Dwayne','Jackie Chan','Anglina Jolie','Kevin Sp Nicholson'];
	     var number= myArray[Math.floor(Math.random() * myArray.length)];
	     document.getElementById('number').value = number;

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

	    <div class="field-wrap">
              <label>
                <span class="req"></span>
              </label>
              <input type="text"required autocomplete="off" name='fakename' id="number" placeholder="Generate FakeName" />
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
            <input type="text"required autocomplete="off" name='cnumber' />
          </div>

          <div class="field-wrap">
            <label>
              Set A Password<span class="req">*</span>
            </label>
            <input type="password"required autocomplete="off" name='password'/>
          </div>

          <button type="submit" class="button button-block" name="register" />Register</button>
         <div style="position:relative; left:0px; top:-340px; height:50px; width:50px;"> <button class="fakename" type="button" name="buttonpassvalue" id="random">Generate</button></div>

          </form>

        </div>

      </div><!-- tab-content -->

</div> <!-- /form -->
  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

    <script src="js/index.js"></script>

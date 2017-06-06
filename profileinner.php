  <?php
    // Check if user is logged in using the session variable
    if ( $_SESSION['logged_in'] != 1 ) {
      $_SESSION['message'] = "You must log in before viewing your profile page!";
      header("location: error.php");
    }
    else {
        // Makes it easier to read
        $first_name = $_SESSION['first_name'];
        $last_name = $_SESSION['last_name'];
        $c_number = $_SESSION['c_number'];
        $email = $_SESSION['email'];
        $active = $_SESSION['active'];
        $avatarnum = $_SESSION['avatarnum'];
        $play_name = $_SESSION['play_name'];
    }
  ?>
  <title>Welcome <?= $first_name.' '.$last_name ?></title>
  <?php include 'css/css.html'; ?>
</head>

<body>
  <div class="form">

          <h1>Welcome</h1>

          <p>
          <?php


          // Display message about account verification link only once
          if ( isset($_SESSION['message']) )
          {
              echo $_SESSION['message'];

              // Don't annoy the user with more messages upon page refresh
              unset( $_SESSION['message'] );
          }

          ?>
          </p>

          <?php

          // Keep reminding the user this account is not active, until they activate
          if ( !$active ){
              echo
              '<div class="info">
              Account is unverified, please confirm your email by clicking
              on the email link!
              </div>';
          }

          ?>

          <h2><?php echo '<img src="assets/opp/sm/opp' . $avatarnum . '.png"/>'; ?></h2>
          <h2><?php echo $play_name; ?></h2>
          <p><?= $email ?></p>
          <a href="questiongame.php"><button class="button button-block" name="logout"/>Play</button></a>
          <br>
          <a href="logout.php"><button class="button button-block" name="logout"/>Log Out</button></a>

    </div>

<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src="js/index.js"></script>

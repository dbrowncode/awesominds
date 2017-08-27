<head>
  <meta charset="UTF-8" />
  <?php
    // Check if user is logged in using the session variable
    if ( $_SESSION['logged_in'] != 1 ) {
      $_SESSION['message'] = "You must log in before viewing your profile page!";
      header("location: error.php");
    }
    else {
        // Makes it easier to read
        $c_number = $_SESSION['c_number'];
        $active = $_SESSION['active'];
        $avatarnum = $_SESSION['avatarnum'];
        $play_name = $_SESSION['play_name'];
        $isInstructor = $_SESSION['isInstructor'];
    }
  ?>
  <title>Welcome <?= $play_name ?></title>
  <?php include 'css/css.html'; ?>
</head>

<body>
  <?php include 'inst-nav2.php' ?>
  <div class="container text-center">
  <div class="form">
    <h2>Welcome to Awesominds</h2>
    <p>
    <?php
    // Display message about account verification link only once
    if ( isset($_SESSION['message']) ){
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
      Account is unverified, please confirm your email address by clicking
      on the email link!
      </div>';
    }
    ?>
    <h4>You are logged in as</h4><br>
    <?php echo '<img src="assets/opp2/oppon' . $avatarnum . '.png" width=120/>'; ?>
    <h3><?php echo $play_name; ?></h3><br>
    </div>
  </div>
<script src="js/index.js"></script>
</body>

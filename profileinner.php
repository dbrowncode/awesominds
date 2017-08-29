<head>
  <meta charset="UTF-8" />
  <?php
    include('redir-notloggedin.php');
    // Makes it easier to read
    $c_number = $_SESSION['c_number'];
    $active = $_SESSION['active'];
    $avatarnum = $_SESSION['avatarnum'];
    $play_name = $_SESSION['play_name'];
    $isInstructor = $_SESSION['isInstructor'];
  ?>
  <title>Welcome <?= $play_name ?></title>
  <?php include 'css/css.html'; ?>
</head>

<body>
  <?php include 'inst-nav2.php' ?>
  <div class="container text-center">
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
    <h3><?php echo $play_name; ?></h3><h5><?php echo $c_number; ?></h5><br>
    <p><a class="btn btn-success" href="questiongame.php">Play Game</a></p>
  </div>
</body>

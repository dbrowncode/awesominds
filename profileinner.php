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
        $first_name = $_SESSION['first_name'];
        $last_name = $_SESSION['last_name'];
        $c_number = $_SESSION['c_number'];
        $email = $_SESSION['email'];
        $active = $_SESSION['active'];
        $avatarnum = $_SESSION['avatarnum'];
        $play_name = $_SESSION['play_name'];
        $isInstructor = $_SESSION['isInstructor'];
    }
  ?>
  <title>Welcome <?= $first_name.' '.$last_name ?></title>
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
              Account is unverified, please confirm your email address by clicking
              on the email link!
              </div>';
          }

          ?>

          <h4>You are logged in as</h4><br>
          <?php echo '<img src="assets/opp2/oppon' . $avatarnum . '.png" width=120/>'; ?>
          <h3><?php echo $play_name; ?></h3><br>


          <!-- <button class="button button-block" name="story" data-toggle="modal" data-target="#storyModal">Story</button><br> -->
          <!-- <a href="logout.php"><button class="button button-block" name="logout"/>Log Out</button></a>  -->

    </div>
  </div>
    <div class="modal fade" id="storyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header text-center">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title text-center" id="myModalLabel">Story</h4>
          </div>
          <div class="modal-body text-center">
            <p>The world is in peril.<br>
            The forces of recklessness, chaos, and ignorance are growing stronger.<br>
            We are the Wisdom Alliance, tasked with protecting this world.</p>

            <p>We seek allies who are not only intelligent, but posses a rare quality known as a <i><b>Mind of Awe</b></i>.<br>
            We experience awe when we are amazed by and enveloped in something larger than ourselves.<br>
            This state possesses a special attribute - one can see the truth beyond facts and reality.<br>
            One can select the truth, even when they know little of the question.</p>

            <p>Our newest member, Jin, will present you with challenges to see if one of you has this rare ability.</p>

            <p>The forces of ignorance and chaos grow stronger.<br>
            To save this world, the Wisdom Alliance must grow.<br>
            To save the world, we need <i><b>Awesominds!</b></i></p>
          </div>
        </div>
      </div>
    </div>
<script src="js/index.js"></script>
</body>

<nav class="navbar sticky-top navbar-expand-sm navbar-light bg-light">
  <a class="navbar-brand" href="index.php">Awesominds</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbar">
    <div class="navbar-nav">

      <?php if ($_SESSION['isInstructor']){
        echo '  <div class="nav-item dropdown">
            <a class="d-block dropdown-toggle btn btn-outline-primary" href="" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Instructor Options
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
              <a class="dropdown-item" href="inst-coursemgmt.php">Manage Courses</a>
              <a class="dropdown-item" href="inst-inviteinstructor.php">Invite Instructor</a>
              <a class="dropdown-item" href="inst-stats.php">View Student Progress</a>
            </div>
          </div>';
      }
      if ($_SESSION['logged_in'] && $_SESSION['active']){
        echo '<a class="btn btn-outline-success" href="questiongame.php">Play</a>';
      }
      if ($_SESSION['logged_in'] && $_SESSION['active'] && !($_SESSION['isInstructor'])){
        echo '<a class="btn btn-outline-primary" href="leaderboard.php">Leaderboards</a>';
      } ?>
      <a class="btn btn-outline-info" href="" name="story" data-toggle="modal" data-target="#creditsModal">About</a>
      <?php
      if ($_SESSION['logged_in']){
        echo '<a class="btn btn-outline-warning" href="logout.php">Log Out</a>';
      } else {
        echo '<a class="btn btn-outline-primary" href="index.php">Log In</a>
              <a class="btn btn-outline-success" href="signup.php">Create Account</a>';
      }

      ?>

    </div>
  </div>
</nav>
<div class="modal fade" id="creditsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title text-center" id="myModalLabel">About Awesominds</h4>
      </div>
      <div class="modal-body text-center">

        <h5>Camosun College's <i>Awesominds</i></h5>
        <table class="table table-sm text-left">
          <tr><td>Concept, Project Manager, Visual Assets: Marty Donatelli</td></tr>
          <tr><td>Lead Programmer: Dustin Brown</td></tr>
          <tr><td>Programmer: Brian Baker</td></tr>
          <tr><td>Programmer: Veenu</td></tr>
          <tr><td>Financial Support: Camosun College Innovation Fund</td></tr>
          <tr><td>Additional Support: Camosun College Centre for Excellence in Teaching and Learning</td></tr>
          <tr><td>License:<br><a rel="license" href="http://creativecommons.org/licenses/by-sa/4.0/"><img alt="Creative Commons License" style="border-width:0" src="https://i.creativecommons.org/l/by-sa/4.0/80x15.png" /></a><br />This work is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-sa/4.0/">Creative Commons Attribution-ShareAlike 4.0 International License</a>.</td></tr>
          <tr><td>Terms of use:<br>By using this program the user agrees to the following. This program is presented on an as is basis. Camosun College and the people involved with this project make no guarantees nor assurances, and are not responsible for any incident that may occur from its use. The user uses this program at their own risk.</td></tr>
        </table>
        <!-- <p>The world is in peril.<br>
        The forces of recklessness, chaos, and ignorance are growing stronger.<br>
        We are the Wisdom Alliance, tasked with protecting this world.</p>

        <p>We seek allies who are not only intelligent, but posses a rare quality known as a <i><b>Mind of Awe</b></i>.<br>
        We experience awe when we are amazed by and enveloped in something larger than ourselves.<br>
        This state possesses a special attribute - one can see the truth beyond facts and reality.<br>
        One can select the truth, even when they know little of the question.</p>

        <p>Our newest member, Jin, will present you with challenges to see if one of you has this rare ability.</p>

        <p>The forces of ignorance and chaos grow stronger.<br>
        To save this world, the Wisdom Alliance must grow.<br>
        To save the world, we need <i><b>Awesominds!</b></i></p> -->
      </div>
    </div>
  </div>
</div>
<script>
  $('.dropdown-item').filter(function() {
    return this.href == window.location;
  }).addClass('active');
</script>

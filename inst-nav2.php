<nav class="navbar sticky-top navbar-toggleable navbar-light bg-faded">
  <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <a class="navbar-brand" href="index.php">Awesominds</a>
  <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
    <div class="navbar-nav">

      <div class="nav-item dropdown">
        <a class="d-block dropdown-toggle btn btn-outline-primary" href="" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Instructor Options
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="inst-createcourse.php">Create Course</a>
          <a class="dropdown-item" href="inst-addquestions.php">Add Chapter/Game</a>
          <a class="dropdown-item" href="inst-givepermissions.php">Give Permissions</a>
          <a class="dropdown-item" href="inst-stats.php">View Student Progress</a>
          <a class="dropdown-item" href="inst-deletecourse.php">Delete Courses/Chapters</a>
        </div>
      </div>

      <a class="btn btn-outline-success" href="questiongame.php">Play</a>
      <a class="btn btn-outline-info" href="" name="story" data-toggle="modal" data-target="#storyModal">Story</a>
      <a class="btn btn-outline-warning" href="logout.php">Log Out</a>

    </div>
  </div>
</nav>
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
<script>
  $('.dropdown-item').filter(function() {
    return this.href == window.location;
  }).addClass('active');
</script>

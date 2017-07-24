<ul class="tab-group">
  <li class="tab"><a href="inst-createcourse.php">Create Course</a></li>
  <li class="tab"><a href="inst-addquestions.php">Add Questions</a></li>
  <li class="tab"><a href="inst-givepermissions.php">Give Permissions</a></li>
  <li class="tab"><a href="inst-stats.php">View Student Progress</a></li>
  <li class="tab"><a href="inst-deletecourse.php">Delete Courses/Chapters</a></li>
</ul>
<script>
  $('ul.tab-group a').filter(function() {
    return this.href == window.location;
  }).parent().addClass('active');
</script>

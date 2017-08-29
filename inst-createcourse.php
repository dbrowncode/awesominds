<!DOCTYPE html>
<html>
<head>
  <?php
    session_start();
    if(!$_SESSION['logged_in'] || !$_SESSION['active'] || !$_SESSION['isInstructor']){
      header("location: index.php");
    }
    include 'css/css.html';
  ?>
</head>
<body>
  <?php include 'inst-nav2.php' ?>
  <div class="container text-center">
    <h2>Create a Course</h2><br>
    <form action="createCourse.php" method="post" id="createCourseForm">
      <p>Enter a course code and name to create a new course.</p>
      <div class="form-group container" style="max-width: 400px;">
        <p>Course Code</p>
        <input class="form-control" type="text" name="courseID" id="courseID" required="true" placeholder="PSYC150" pattern="[A-Za-z]{3,4}[0-9]{3}" title="3-4 letters followed by 3 numbers; no spaces.">
        <p>Course Name</p>
        <input class="form-control" type="text" name="courseName" id="courseName" required="true" placeholder="PSYCHOLOGY 150">

        <input type="submit" class='btn btn-primary' value="Create Course" name"createC">
      </div>
    </form>
    <p id="createCourseOutput"></p>
  </div>

<script>
var create_output           = '#createCourseOutput';
var createForm              = $('#createCourseForm');
createForm.submit(function (e) {
  e.preventDefault();
  $.ajax({
    type: createForm.attr('method'),
    url: createForm.attr('action'),
    data: createForm.serialize(),
    success: function(data) {
     $(create_output).html(data);
      console.log(data);
    },
  error: function(data) {
    console.log('error');
    console.log(data);
  },
 });
});
</script>
</body>
</html>

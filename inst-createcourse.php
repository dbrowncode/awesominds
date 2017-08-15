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
    <div class="formWrap form">
      <h2>Create a Course</h2><br>
        <div class="createClass">
          <form action="createCourse.php" method="post" id="createCourseForm">
            <!-- could use js to restrict input type here -->

              <p>Create a new course based on the alphanumeric course code and name.</p>
              <p>Course Code: <input type="text" name="courseID" id="courseID" required="true" placeholder="PSYC150" pattern="[A-Za-z]{3,4}[0-9]{3}" title="3-4 letters, 3 numbers, no spaces."></p>
              <p>Course Name: <input type="text" name="courseName" id="courseName" required="true" placeholder="PSYCHOLOGY 150"></p>

            <input type="submit" class='button button-block' value="Create Course" name"createC">
          </form>
        <p id="confirm"></p>
      </div>
    </div>
  </div>


<!--jquery goes here-->

<script>
var create_output           = '#confirm';
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

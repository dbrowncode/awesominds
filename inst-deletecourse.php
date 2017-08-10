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
      <h2>Delete Courses/Chapters</h2><br>
      <p>Select a course to view its chapters/games. You may then delete the selected course or any of its chapters/games.</p>
      <div id='selectCourseDiv'>
        Select a course:
        <select id='courseDropdown'>
          <option value="default">No Courses Found</option>
        </select>
        <button id='selectCourseBtn' value='Select'>Select</button>
      </div>

      <div id="noChapters"><br>No chapters available for this course<br><br><button class='deleteCourseBtn' value='Delete Course' data-toggle="modal" data-target="#confirmDelete">Delete Course</button></div>

      <div id='selectChapterDiv'>
        <br><button class='deleteCourseBtn' value='Delete Course' data-toggle="modal" data-target="#confirmDelete">Delete Course</button><br><br>
        Select chapter:
        <select id='chapterDropdown'>
          <option value="default">No Chapters Found</option>
        </select>
        <button id='selectChapterBtn' value='Delete Chapter' data-toggle="modal" data-target="#confirmDelete">Delete Chapter</button>
      </div>
    </div>
    <div id="output" style="max-width: 90%; margin: 0 auto"></div>
  </div>

  <div class="modal fade" id="confirmDelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header text-center">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title text-center" id="myModalLabel2">Are you sure?</h4>
        </div>
        <div class="modal-body text-center" id='modalBody2'>
          Are you sure you want to delete this course?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger btn-ok" data-dismiss="modal" id="deleteBtn">Delete</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>

<script>
var deletingCourse = true; //set to false if deleting a chapter
var getCourses = function(){
  $.ajax({
    url: 'getcourses.php',
    success: function(data){
      $('#courseDropdown').empty();
      var courses = $.parseJSON(data);
      for (var i = 0; i < courses.length; i++) {
        $('#courseDropdown').append('<option value="' + courses[i].courseid + '">' + courses[i].courseid + ' - ' + courses[i].name + '</option>');
      }
    }
  });
}

var getChapters = function(){
  $('#chapterDropdown').empty();
  $.ajax({
    url: 'getchapters.php',
    data: 'courseid=' + $('#courseDropdown').find(":selected").val(),
    success: function(data){
      var chapters = $.parseJSON(data);
      if(chapters.length == 0){
        $('#selectChapterDiv').hide();
        $('#noChapters').show();
      } else {
        for (var i = 0; i < chapters.length; i++) {
          $('#chapterDropdown').append('<option value="' + chapters[i].chapter + '">' + chapters[i].chapter + '</option>');
        }
        $('#noChapters').hide();
        $('#selectChapterDiv').show();
      }
    }
  });
}

$(function (){
  $('#selectChapterDiv').hide();
  $('#noChapters').hide();

  $("#selectCourseBtn").click(function(){
    $('#output').empty();
    $.ajax({
      type: 'POST',
      url: 'setcourse.php',
      data: { course: $('#courseDropdown').find(":selected").val() },
      success: function(data){
        getChapters();
      }
    });
  });

  $('.deleteCourseBtn').click(function(){
    $('#modalBody2').html('Are you sure you want to delete ' + $('#courseDropdown').find(":selected").html() + '?');
    deletingCourse = true;
  });

  $("#selectChapterBtn").click(function(){
    $('#modalBody2').html('Are you sure you want to delete Chapter ' + $('#chapterDropdown').find(":selected").val() + ' from ' + $('#courseDropdown').find(":selected").html() + '?');
    deletingCourse = false;
  });

  $('#deleteBtn').click(function(){
    var postData = {
      courseid: $('#courseDropdown').find(":selected").val(),
      deletingCourse: deletingCourse
    };
    if(!deletingCourse){
      postData.chapter = $('#chapterDropdown').find(":selected").val();
    }
    $.ajax({
      type: 'POST',
      url: 'db-deletecourse.php',
      data: postData,
      success: function(data){
        getCourses();
        $('#selectChapterDiv').hide();
        $('#noChapters').hide();
        console.log(data);
      }
    });
  });

  getCourses();

});
</script>

</body>
</html>

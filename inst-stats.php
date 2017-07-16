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
  <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.min.js" integrity="sha256-VNbX9NjQNRW+Bk02G/RO6WiTKuhncWI4Ey7LkSbE+5s=" crossorigin="anonymous"></script>
</head>
<body>

  <div class="formWrap form">
    <ul class="tab-group">
      <li class="tab"><a href="index.php">Home</a></li>
      <li class="tab"><a href="inst-createcourse.php">Create Course</a></li>
      <li class="tab"><a href="inst-addquestions.php">Add Questions</a></li>
      <li class="tab"><a href="inst-givepermissions.php">Give Permissions</a></li>
    </ul>
    <div id='selectCourseDiv' class='tab-content field-wrap'>
      <p>Select a course</p>
      <select id='courseDropdown'>
        <option value="default">No Courses Found</option>
      </select>
      <button id='selectCourseBtn' value='Select'>Select</button>
    </div>
    <div id='selectChapterDiv' class='tab-content field-wrap'>
      <p>Select a chapter</p>
      <select id='chapterDropdown'>
        <option value="default">No Chapters Found</option>
      </select>
      <button id='selectChapterBtn' value='Select'>Select</button>
    </div>
    <div id="output"></div>
  </div>

<script>
var getCourses = function(){
  $.ajax({
    url: 'getcourses.php',
    success: function(data){
      $('#courseDropdown').empty();
      var courses = $.parseJSON(data);
      for (var i = 0; i < courses.length; i++) {
        $('#courseDropdown').append('<option value="' + courses[i].courseid + '">' + courses[i].courseid + '</option>');
      }
    }
  });
}

var getChapters = function(){
  $.ajax({
    url: 'getchapters.php',
    data: 'courseid=' + $('#courseDropdown').find(":selected").val(),
    success: function(data){
      $('#chapterDropdown').empty();
      var chapters = $.parseJSON(data);
      for (var i = 0; i < chapters.length; i++) {
        $('#chapterDropdown').append('<option value="' + chapters[i].chapter + '">' + chapters[i].chapter + '</option>');
      }
    }
  });
}

$(function (){
  $('#selectChapterDiv').hide();

  $("#selectCourseBtn").click(function(){
    $.ajax({
      type: 'POST',
      url: 'setcourse.php',
      data: { course: $('#courseDropdown').find(":selected").val() },
      success: function(data){
        getChapters();
        $('#selectChapterDiv').show();
        var selectedCourseText = '<p>Course: ' + $.parseJSON(data).course + '</p><p><a href="inst-addquestions.php">Back to Select</a></p>'
        $('#selectChapterDiv').append(selectedCourseText);
        $('#selectCourseDiv').hide();
      }
    });
  });

  $("#selectChapterBtn").click(function(){
    $.ajax({
      url: 'getscores-allusers-chapter.php',
      data: 'courseid=' + $('#courseDropdown').find(":selected").val() + '&chapter=' + $('#chapterDropdown').find(":selected").val(),
      success: function(data){
        var str = "<h2>Scores for " + $('#courseDropdown').find(":selected").val() + ", Chapter " + $('#chapterDropdown').find(":selected").val() + " </h2><table><tr><th>C Number</th><th>High Score</th><th>Total Points Earned</th></tr>";
        var scores = $.parseJSON(data);
        for (var i = 0; i < scores.length; i++) {
          console.log(scores[i]);
          str += '<tr><td>' + scores[i].c_number + '</td><td>' + scores[i].high_score + '</td><td>' + scores[i].total_score + '</td></tr>';
        }
        $('#output').append(str + '</table>');
      }
    });
  });

  getCourses();

});
</script>

</body>
</html>

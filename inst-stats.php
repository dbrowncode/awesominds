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
  <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.15/css/jquery.dataTables.css">
  <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.js"></script>
</head>
<body>
  <?php include 'inst-nav2.php' ?>
  <div class="container text-center">
    <div class="formWrap form">
      <h2>View Student Progress</h2><br>
      <p>Select a course to view its game statistics.<br> You may then select a chapter/game for specific stats.<br> Courses and chapters/games with no available statistics are not shown.</p>
      <div id='selectCourseDiv'>
        Select a course:
        <select id='courseDropdown'>
          <option value="default">No Courses Found</option>
        </select>
        <button id='selectCourseBtn' value='Select'>Select</button>
      </div>
      <div id='selectChapterDiv'>
        <br>
        Select chapter:
        <select id='chapterDropdown'>
          <option value="default">No Chapters Found</option>
        </select>
        <button id='selectChapterBtn' value='Select'>Select</button>
      </div>
    </div>
    <div id="output" class="container"></div>
  </div>

<script>
var getCourses = function(){
  $.ajax({
    url: 'getcourses-forstats.php',
    success: function(data){
      console.log('got courses');
      $('#courseDropdown').empty();
      var courses = $.parseJSON(data);
      console.log(data);
      for (var i = 0; i < courses.length; i++) {
        $('#courseDropdown').append('<option value="' + courses[i].courseid + '">' + courses[i].courseid + ' - ' + courses[i].name + '</option>');
      }
    }
  });
}

var getChapters = function(){
  $('#chapterDropdown').empty();
  $.ajax({
    url: 'getchapters-forstats.php',
    data: 'courseid=' + $('#courseDropdown').find(":selected").val(),
    success: function(data){
      var chapters = $.parseJSON(data);
      for (var i = 0; i < chapters.length; i++) {
        $('#chapterDropdown').append('<option value="' + chapters[i].chapter + '">' + chapters[i].chapter + '</option>');
      }
    }
  });
}

$(function (){
  $('#selectChapterDiv').hide();
  var modes = ['Countdown', 'Wild Wild Guess'];

  $("#selectCourseBtn").click(function(){
    $('#output').empty();
    $.ajax({
      type: 'POST',
      url: 'setcourse.php',
      data: { course: $('#courseDropdown').find(":selected").val() },
      success: function(data){
        getChapters();
        $('#selectChapterDiv').show();
        $.ajax({
          url: 'getscores-allusers-course.php',
          data: 'courseid=' + $('#courseDropdown').find(":selected").val(),
          success: function(data){
            var str = "<h2>Scores for " + $('#courseDropdown').find(":selected").val() + '</h2><p>Click a column heading to sort by that attribute</p><table id="table" class="display"><thead><tr><th>First Name</th><th>Last Name</th><th>C Number</th><th>Display Name</th><th>Chapter</th><th>Game Mode</th><th>Total Points Earned</th><th>Times Played</th></tr></thead><tbody>';
            var scores = $.parseJSON(data);
            for (var i = 0; i < scores.length; i++) {
              str += '<tr><td>' + scores[i].first_name + '</td><td>' + scores[i].last_name + '</td><td>' + scores[i].c_number + '</td><td>' + scores[i].play_name + '</td><td>' + scores[i].chapter + '</td><td>' + modes[scores[i].game_mode] + '</td><td>' + scores[i].total_score + '</td><td>' + scores[i].times_played + '</td></tr>';
            }
            $('#output').html(str + '</tbody></table>');
            $('#table').DataTable({ paging: false, "order": [[1, 'asc']] });
          }
        });

        // var selectedCourseText = '<p>Course: ' + $.parseJSON(data).course + '</p><p><a href="inst-stats.php">Back to Course Select</a></p>'
        // $('#selectChapterDiv').append(selectedCourseText);
        // $('#selectCourseDiv').hide();
      }
    });
  });

  $("#selectChapterBtn").click(function(){
    $('#output').empty();
    $.ajax({
      url: 'getscores-allusers-chapter.php',
      data: 'courseid=' + $('#courseDropdown').find(":selected").val() + '&chapter=' + $('#chapterDropdown').find(":selected").val(),
      success: function(data){
        var str = "<h2>Scores for " + $('#courseDropdown').find(":selected").val() + ", Chapter " + $('#chapterDropdown').find(":selected").val() + ' </h2><p>Click a column heading to sort by that attribute</p><table id="table" class="display"><thead><tr><th>First Name</th><th>Last Name</th><th>C Number</th><th>Display Name</th><th>Game Mode</th><th>Total Points Earned</th><th>Times Played</th></tr></thead><tbody>';
        var scores = $.parseJSON(data);
        for (var i = 0; i < scores.length; i++) {
          str += '<tr><td>' + scores[i].first_name + '</td><td>' + scores[i].last_name + '</td><td>' + scores[i].c_number + '</td><td>' + scores[i].play_name + '</td><td>' + modes[scores[i].game_mode] + '</td><td>' + scores[i].total_score + '</td><td>' + scores[i].times_played + '</td></tr>';
        }
        $('#output').html(str + '</tbody></table>');
        $('#table').DataTable({ paging: false, "order": [[1, 'asc']] });
        // $('#selectChapterDiv').hide();
      }
    });
  });

  getCourses();

});
</script>

</body>
</html>

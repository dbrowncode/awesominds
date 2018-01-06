<!DOCTYPE html>
<html>
<head>
  <?php
    include('redir-notinstructor.php');
    include 'css/css.html';
  ?>
  <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.15/css/jquery.dataTables.css">
  <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.js"></script>
  <title>View Student Progress - Awesominds</title>
</head>
<body>
  <?php include 'inst-nav2.php' ?>
  <div class="container text-center">
    <h2>View Student Progress</h2><br>
    <p>Select a course to view its game statistics.<br> You may then select a chapter/game for specific stats.</p>
    <div class="card">
      <p>Select a course:</p>
      <div id='selectCourseDiv' class="container" style="max-width: 400px">
        <div class="input-group">
          <span class="input-group-addon">Course</span>
          <select class="form-control" id='courseDropdown'>
            <option value="null">Select a Course</option>
          </select>
        </div>
      </div>
    </div>

    <div class='card selectChapterUI'>
      <p>Select a chapter/game:</p>
      <div id='selectChapterDiv' class="container" style="max-width: 400px">
        <div class="input-group">
          <span class="input-group-addon">Chapter</span>
          <select class='form-control' id='chapterDropdown'>
            <option value="null">Select a Chapter/Game</option>
          </select>
          <!-- <span class="input-group-btn"><button id='selectChapterBtn' class='btn btn-primary' value='Select'>Select</button></span> -->
        </div>
        <div id="selectedChapterOutput"></div>
      </div>
    </div>
    <div id="output" class="card"></div>
  </div>

<script>
var getCourses = function(){
  $.ajax({
    url: 'getcourses.php',
    success: function(data){
      // console.log('got courses');
      $('#courseDropdown').empty();
      $('#courseDropdown').append('<option value="null">Select a Course</option>');
      var courses = $.parseJSON(data);
      // console.log(data);
      for (var i = 0; i < courses.length; i++) {
        $('#courseDropdown').append('<option value="' + courses[i].courseid + '">' + courses[i].courseid + ' - ' + courses[i].name + '</option>');
      }
    }
  });
}

var getChapters = function(){
  $('#chapterDropdown').empty();
  $('#chapterDropdown').append('<option value="null">Select a Chapter</option>');
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
  $('.selectChapterUI').hide();
  $('#output').hide();
  var modes = ['Countdown Crown', 'Wild Wild Guess'];

  $("#courseDropdown").change(function(){
    $('#output').empty();
    $('#output').show();
    $.ajax({
      type: 'POST',
      url: 'setcourse.php',
      data: { course: $('#courseDropdown').find(":selected").val() },
      success: function(data){
        getChapters();
        $('.selectChapterUI').show();
        $.ajax({
          url: 'getscores-allusers-course.php',
          data: 'courseid=' + $('#courseDropdown').find(":selected").val(),
          success: function(data){
            var str = "<h2>Scores for " + $('#courseDropdown').find(":selected").val() + '</h2><p>Click a column heading to sort by that attribute</p><table id="table" class="display"><thead><tr><th>C Number</th><th>Display Name</th><th>Chapter</th><th>Game Mode</th><th>High Score</th><th>Total Points Earned</th><th>Times Played</th></tr></thead><tbody>';
            var scores = $.parseJSON(data);
            for (var i = 0; i < scores.length; i++) {
              str += '<tr><td>' + scores[i].c_number + '</td><td>' + scores[i].play_name + '</td><td>' + scores[i].chapter + '</td><td>' + modes[scores[i].game_mode] + '</td><td>' + scores[i].high_score + '</td><td>' + scores[i].total_score + '</td><td>' + scores[i].times_played + '</td></tr>';
            }
            $('#output').html(str + '</tbody></table>');
            $('#table').DataTable({ paging: false, "order": [[1, 'asc']] });
          }
        });
      }
    });
  });

  $("#chapterDropdown").change(function(){
    $('#output').empty();
    $('#output').show();
    $.ajax({
      url: 'getscores-allusers-chapter.php',
      data: 'courseid=' + $('#courseDropdown').find(":selected").val() + '&chapter=' + $('#chapterDropdown').find(":selected").val(),
      success: function(data){
        var str = "<h2>Scores for " + $('#courseDropdown').find(":selected").val() + ", Chapter " + $('#chapterDropdown').find(":selected").val() + ' </h2><p>Click a column heading to sort by that attribute</p><table id="table" class="display"><thead><tr><th>C Number</th><th>Display Name</th><th>Game Mode</th><th>High Score</th><th>Total Points Earned</th><th>Times Played</th></tr></thead><tbody>';
        var scores = $.parseJSON(data);
        for (var i = 0; i < scores.length; i++) {
          str += '<tr><td>' + scores[i].c_number + '</td><td>' + scores[i].play_name + '</td><td>' + modes[scores[i].game_mode] + '</td><td>' + scores[i].high_score + '</td><td>' + scores[i].total_score + '</td><td>' + scores[i].times_played + '</td></tr>';
        }
        $('#output').html(str + '</tbody></table>');
        $('#table').DataTable({ paging: false, "order": [[1, 'asc']] });
      }
    });
  });

  getCourses();

});
</script>

</body>
</html>

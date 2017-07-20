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
  <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.15/css/jquery.dataTables.css">
  <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.js"></script>
</head>
<body>
  <a href="index.php"><i class="fa fa-home fa-2x" aria-hidden="true"></i></a>

  <div class="formWrap form">
    <?php include 'inst-nav.php' ?>
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
  </div>
  <div id="output" style="max-width: 90%; margin: 0 auto"></div>

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
        var selectedCourseText = '<p>Course: ' + $.parseJSON(data).course + '</p><p><a href="inst-stats.php">Back to Course Select</a></p>'
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
        var str = "<h2>Scores for " + $('#courseDropdown').find(":selected").val() + ", Chapter " + $('#chapterDropdown').find(":selected").val() + ' </h2><table id="table" class="display"><thead><tr><th>First Name</th><th>Last Name</th><th>C Number</th><th>Display Name</th><th>Total Points Earned</th></tr></thead><tbody>';
        var scores = $.parseJSON(data);
        for (var i = 0; i < scores.length; i++) {
          str += '<tr><td>' + scores[i].first_name + '</td><td>' + scores[i].last_name + '</td><td>' + scores[i].c_number + '</td><td>' + scores[i].play_name + '</td><td>' + scores[i].total_score + '</td></tr>';
        }
        $('#output').html(str + '</tbody></table>');
        $('#table').DataTable({ paging: false, "order": [[1, 'asc']], searching: false, ordering: false });
        $('#selectChapterDiv').hide();
      }
    });
  });

  getCourses();

});
</script>

</body>
</html>

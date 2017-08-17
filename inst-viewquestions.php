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
  <title>View Questions - Awesominds</title>
</head>
<body>
  <?php include 'inst-nav2.php' ?>
  <div class="container text-center">
    <div class="formWrap form">
      <h2>View Questions</h2><br>

      <div id='selectCourseDiv' class="row">
        <p class="col-12">Select a course to view its chapters/games.</p>
        <div class="col-12">
          Select a course:&nbsp;
          <select id='courseDropdown' class='custom-select'>
            <option value="default">No Courses Found</option>
          </select>
          <button id='selectCourseBtn' class='btn btn-primary' value='Select'>Select</button>
        </div>
      </div>

      <div id="noChapters"><br>No chapters available for this course<br><br></div>

      <div id='selectChapterDiv' class="row" style="margin-top: 20px">
        <p class="col-12">Select a chapter/game to view its questions.</p>
        <div class="col-12">
          Select a chapter/game:&nbsp;
          <select id='chapterDropdown' class='custom-select'>
            <option value="default">No Chapters Found</option>
          </select>
          <button id='selectChapterBtn' class='btn btn-primary' value='Select'>Select</button>
        </div>
      </div>
    </div>
    <div id="output" style="margin-top: 20px"></div>
  </div>

<script>
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

var getChapters = function(course){
  $('#chapterDropdown').empty();
  $.ajax({
    url: 'getchapters.php',
    data: 'courseid=' + course,
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
  var selectedCourse = "";
  var selectedChapter = 0;
  var questions = [];

  $("#selectCourseBtn").click(function(){
    $('#output').empty();
    selectedCourse = $('#courseDropdown').find(":selected").val();
    $.ajax({
      type: 'POST',
      url: 'setcourse.php',
      data: { course: selectedCourse },
      success: function(data){
        getChapters(selectedCourse);
      }
    });
  });

  $("#selectChapterBtn").click(function(){
    selectedChapter = $('#chapterDropdown').find(":selected").val();
    $.ajax({
      type: 'GET',
      url: 'getquestion.php',
      data: { 'courseid': selectedCourse, 'chapter': selectedChapter },
      dataType: 'json',
      success: function(data){
        questions = [];
        $('#output').empty();
        var htmlStr = '<h3>Chapter ' + selectedChapter + ' Questions</h3><table class="table table-responsive table-hover table-bordered text-left"><thead class="thead-default"><tr><th>Question Text</th><th>Choices</th><th>Answer</th><th>Options</th></tr></thead><tbody>';
        for (var i = 0; i < data.length; i++) {
          var q = $.parseJSON(data[i]["question"]);
          var id = $.parseJSON(data[i]["questionid"]);
          // console.log(q);
          htmlStr += '<tr><td>' + q.question + '</td><td>'
          Object.keys(q.choices).forEach(function(key){
            htmlStr += key + ': ' + q.choices[key] + '<br>';
          });
          htmlStr += '</td><td>' + q.answer + '</td><td><a href="inst-editquestion.php?mode=edit&qid=' + id + '">Edit</a><br><a href="">Delete</a>';
          // for (var v in q.choices) {
          //   if (q.choices.hasOwnProperty(v)) {
          //     htmlStr += v + ': ' + q.choices.v;
          //   }
          // }
          // console.log(q.choices);
          // <div id="question' + q.questionid + '">'



          // questions[i] = {};
          // questions[i].questionid = $.parseJSON(data[i]["questionid"]);
          // questions[i].questionObj = $.parseJSON(data[i]["question"]);
        }
        htmlStr += '</tbody></table>';
        $('#output').append(htmlStr);
        // console.log(questions);
      }
    });
  });

  getCourses();

});
</script>

</body>
</html>

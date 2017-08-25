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

  <div class="modal fade" id="confirmDelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header text-center">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title text-center" id="myModalLabel2">Are you sure?</h4>
        </div>
        <div class="modal-body text-center" id='modalBody2'>
          Are you sure you want to delete this question?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger btn-ok" data-dismiss="modal" id="deleteBtn">Delete</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>

<script>
var selectedCourse = "";
var selectedChapter = 0;
var questions = [];
var questionid = 0;
var table = null;

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

var getQuestions = function(){
  $.ajax({
    type: 'GET',
    url: 'getquestion.php',
    data: { 'courseid': selectedCourse, 'chapter': selectedChapter },
    dataType: 'json',
    success: function(data){
      questions = [];
      $('#output').empty();
      var htmlStr = '<h3>Chapter ' + selectedChapter + ' Questions</h3><table id="table" class="display table table-hover table-bordered text-left"><thead><tr><th>ID#</th><th>Question Text</th><th>Choices</th><th>Answer</th><th>Options</th></tr></thead><tbody>';
      for (var i = 0; i < data.length; i++) {
        var q = $.parseJSON(data[i]["question"]);
        var id = $.parseJSON(data[i]["questionid"]);
        // console.log(q);
        htmlStr += '<tr id="row' + id + '"><td>' + id +'</td><td>' + q.question + '</td><td>'
        Object.keys(q.choices).forEach(function(key){
          htmlStr += key + ': ' + q.choices[key] + '<br>';
        });
        htmlStr += '</td><td>' + q.answer + '</td><td><a href="inst-editquestion.php?mode=edit&qid=' + id + '"><button class="btn btn-info">Edit</button></a><br><button class="btn btn-danger deleteQuestionBtn" data-toggle="modal" data-target="#confirmDelete" id="' + id + '">Delete</button></td></tr>';
      }
      htmlStr += '</tbody></table>';
      $('#output').html(htmlStr);
      table = $('#table').DataTable({ paging: false, "order": [[0, 'asc']] });
      $('.deleteQuestionBtn').click(function(){
        $('#modalBody2').html('Are you sure you want to delete question #' + $(this).attr('id') + '?');
        questionid = $(this).attr('id');
      });
    }
  });
}

$(function (){
  $('#selectChapterDiv').hide();
  $('#noChapters').hide();

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
    getQuestions();
  });

  $('#deleteBtn').click(function(){
    $.ajax({
      type: 'POST',
      url: 'api-deletequestion.php',
      data: { questionid : questionid },
      success: function(data){
        // $('#row' + questionid).remove();
        getQuestions();
      }
    });
  });

  getCourses();

});
</script>

</body>
</html>

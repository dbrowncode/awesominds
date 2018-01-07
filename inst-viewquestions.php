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

  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header text-center">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title text-center" id="myModalLabel3">Editing Question</h4>
        </div>
        <div class="modal-body container text-center" id='editModalBody'>
          <form id='editQuestionForm'>

            <label class="col-form-label" for="questionText">Question Text</label>

            <div class="form-group row" id="questionRow">
              <textarea name="questionText" class="col-sm-12 form-control" id="questionText" required rows="3"></textarea>
            </div>

            <label class="col-form-label" for="optionText">Options</label>

            <div class="form-group input-group optionRow" id="optionRow0">
              <span class="input-group-addon" id="optionLetter" value="A">A</span>
              <input name="optionLetterHidden" id="optionLetterHidden" type="hidden" value="A">

              <input name="optionText" type="text" class="form-control" id="optionText">

              <span class="input-group-addon">
                <small class="form-text">Correct</small>
                <input type="radio" name="answer" id="answerRadio" value="A" checked>
              </span>
            </div>

            <div class="form-group" id="addOption">
              <button id="addOptionBtn" type="button" class="btn btn-success">+ Add Option</button>
            </div>
            <!-- <button id="saveQuestionBtn" type="button" class="btn btn-primary">Save Question</button> -->

          </form>
          <div id="editOutput"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary btn-ok" data-dismiss="modal" id="saveQuestionBtn">Save Changes</button>
          <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel (Discard Changes)</button>
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

function nextLetter(s){ //function takes a letter and returns the next letter alphabetically; useful for lettering the answer options for a question
  return s.replace(/([A-Z])[^A-Z]*$/, function(a){
    var c = a.charCodeAt(0);
    switch(c){
      case 90: return 'A';
      default: return String.fromCharCode(++c);
    }
  });
}

var optionLimit = 6
var numTotal = 1;

function addOption(){
  if(numTotal < optionLimit){
    var $div = $('div[id^="optionRow"]:last');
    var newRow = $div.clone().prop('id', 'optionRow' + numTotal );
    var letter = newRow.find('#optionLetterHidden').val().toUpperCase();
    newRow.find('#optionLetter').html(nextLetter(letter));
    newRow.find('#optionLetterHidden').val(nextLetter(letter));
    newRow.find('#answerRadio').val(nextLetter(letter));
    newRow.find('#optionText').val('');
    $("#addOption").before(newRow);
    numTotal++;
    if(numTotal >= optionLimit){
      $("#addOptionBtn").prop("disabled", true);
      $("#addOption").append('<p><small>Limit ' + optionLimit + ' options per question</small></p>');
    }
  }
};

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

var getQuestions = function(){ //retrieves questions for the selected chapter of the selected course and outputs them to a table
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
        htmlStr += '<tr id="row' + id + '"><td>' + id +'</td><td>' + q.question + '</td><td>'
        Object.keys(q.choices).forEach(function(key){
          htmlStr += key + ': ' + q.choices[key] + '<br>';
        });
        htmlStr += '</td><td>' + q.answer + '</td><td><button class="btn btn-info editBtn" data-toggle="modal" data-target="#editModal" id="' + id + '"">Edit</button><br><button class="btn btn-danger deleteQuestionBtn" data-toggle="modal" data-target="#confirmDelete" id="' + id + '">Delete</button></td></tr>';
      }
      htmlStr += '</tbody></table>';
      $('#output').html(htmlStr);
      table = $('#table').DataTable({ paging: false, "order": [[0, 'asc']] });

      $('.deleteQuestionBtn').click(function(){
        $('#modalBody2').html('Are you sure you want to delete question #' + $(this).attr('id') + '?');
        questionid = $(this).attr('id');
      });

      $('.editBtn').click(function(){
        questionid = $(this).attr('id');
        $.ajax({
          type: 'GET',
          url: 'api-getonequestion.php?qid=' + questionid,
          dataType: 'json',
          success: function(data){
            var q = $.parseJSON(data['question']);
            $('#questionText').val(q.question);
            var keys = Object.keys(q.choices);
            for (var i = 0; i < keys.length; i++) {
              if(i < keys.length-1) addOption();
              $('#optionRow' + i).children('#optionText').val(q.choices[keys[i]]);
            }
            $('#answerRadio[value='+ q.answer +']').prop("checked", true);
          }
        });
      });

      $('#saveQuestionBtn').click(function(){
        var formArray = $('form').serializeArray();
        console.log(formArray);
        var questionBank = {};
        questionBank.choices = {};
        for (var i = 0; i < formArray.length; i++) {
          switch (formArray[i].name) {
            case 'optionText':
              // console.log(formArray[i-1]);
              questionBank.choices[formArray[i-1].value] = formArray[i].value;
              break;
            case 'questionText':
              questionBank.question = formArray[i].value;
              break;
            case 'answer':
              questionBank.answer = formArray[i].value;
              break;
            default:
              break;
          }
        }

        $.ajax({
          type: 'POST',
          url: 'api-updatequestion.php',
          data: { questionBank: questionBank, questionid: questionid },
          success: function(data) {
            getQuestions();
            $('div[id^="optionRow"]').not(':first').remove();
            numTotal = 1;
          }
        });
      });
    }
  });
}

$(function (){
  $('#selectChapterDiv').hide();
  $('#noChapters').hide();

  $('#editModal').on('hide.bs.modal', function () {
    $('div[id^="optionRow"]').not(':first').remove();
    numTotal = 1;
  })

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
        getQuestions();
      }
    });
  });

  $("#addOptionBtn").click(function(){
    addOption();
  });

  getCourses();

  <?php
    if(isset($_GET["courseid"])){
      $c = $_GET["courseid"];
      echo "selectedCourse = '$c';
            $.ajax({
              type: 'POST',
              url: 'setcourse.php',
              data: { course: selectedCourse },
              success: function(data){
                getChapters(selectedCourse);";
              if(isset($_GET["chapter"])){
                $ch = $_GET["chapter"];
                echo "selectedChapter = '$ch';
                      getQuestions();";
              }
        echo "}
            });";
    }
  ?>

});
</script>

</body>
</html>

<!DOCTYPE html>
<html>
<head>
  <?php
    include('redir-notinstructor.php');
    include 'css/css.html';
  ?>
  <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.15/css/jquery.dataTables.css">
  <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.js"></script>
  <title>Course Management - Awesominds</title>
</head>
<body>
  <?php include 'inst-nav2.php' ?>
  <div class="container text-center">
    <h2>Course Management</h2><br>
    <div class="card">
      <p id="selectCourseText">Select a course to manage, or create a new course.</p>
      <div id='selectCourseDiv' class="container" style="max-width: 400px">
        <p><button class="btn btn-success" id='newCourseBtn' data-toggle="modal" data-target="#createCourseModal">Create New Course</button></p>
        <div class="input-group">
          <span class="input-group-addon">Course</span>
          <select class="form-control" id='courseDropdown'>
            <option value="null">Select an Existing Course</option>
          </select>
        </div>
      </div>
      <div id="selectedCourseOutput"></div>
    </div>

    <div class='card selectChapterUI'>
      <div id='selectChapterDiv' class="container" style="max-width: 400px">
        <p><button class="btn btn-success newChapterBtn" data-toggle="modal" data-target="#createChapterModal"></button></p>
        <div class="input-group">
          <span class="input-group-addon">Chapter</span>
          <select class='form-control' id='chapterDropdown'>
            <option value="null">Select a Chapter/Game to Manage</option>
          </select>
        </div>
        <div id="selectedChapterOutput">
          <br><p><button id="editChapterBtn" data-toggle="modal" data-target="#createChapterModal" class="btn btn-primary">Edit Chapter</button> <button id="deleteChapterBtn" data-toggle="modal" data-target="#confirmDelete" class="btn btn-danger">Delete Chapter</button></p>
        </div>
      </div>
    </div><br>
    <div id="output" class="card"></div>

  </div>

  <div class="modal fade" id="confirmDelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header text-center">
          <h4 class="modal-title text-center" id="myModalLabel2">Are you sure?</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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

  <div class="modal fade" id="createCourseModal" tabindex="-1" role="dialog" aria-labelledby="createCourseModalLabel">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header text-center">
          <h4 class="modal-title text-center" id="createCourseModalLabel">Create Course</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <form action="createCourse.php" method="post" id="createCourseForm">
        <div class="modal-body text-center" id='createCourseModalBody'>

            <p>Enter a course code and name to create a new course.</p>
            <div class="form-group container" style="max-width: 400px;">
              <p>Course Code</p>
              <input class="form-control" type="text" name="courseID" id="courseIDinput" required placeholder="e.g. 'PSYC150'" pattern="[A-Za-z]{3,4}[0-9]{3}" title="3-4 letters followed by 3 numbers; no spaces."><br>
              <p>Course Name</p>
              <input class="form-control" type="text" name="courseName" id="courseNameinput" required placeholder="e.g. 'Psychology 150'">
            </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary btn-ok" id="createCourseBtn">Create Course</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="createChapterModal" tabindex="-1" role="dialog" aria-labelledby="createChapterModalLabel">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header text-center">
          <h4 class="modal-title text-center" id="createChapterModalLabel">Create Chapter</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <form action="api-createchapter.php" method="post" id="createChapterForm">
        <div class="modal-body text-center" id='createChapterModalBody'>
          <p id="createChapterModalDesc">Enter a chapter number, chapter name, and availability dates to create a new chapter.</p>
          <div class="form-group container" style="max-width: 400px;">
            <p>Chapter Number</p>
            <input class="form-control" type="number" name="chapterid" id="chapterIDinput" required value="1" min="1" max="999"><br>
            <p>Chapter Name</p>
            <input class="form-control" type="text" name="chaptername" id="chapterNameinput" required placeholder="eg. Intro to Topic"><br>
            <p>Available from:</p>
            <input class="form-control" type="datetime-local" id="date_start_input" required><br>
            <p>To:</p>
            <input class="form-control" type="datetime-local" id="date_end_input" required>
            <p id="createChapterOutput"></p>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary btn-ok" id="createChapterBtn">Create Chapter</button>
          <button type="submit" class="btn btn-primary btn-ok" id="updateChapterBtn">Save Changes</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header text-center">
          <h4 class="modal-title text-center" id="uploadModalLabel">Upload Questions</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <form action="upload2.php" method="post" enctype="multipart/form-data" id="uploadForm">
        <div class="modal-body text-center" id='uploadModalBody'>

          <p>Upload a .doc file of questions to add them to this chapter. File must adhere to formatting rules:</p>
          <small><ol class="text-left">
            <li>Plain text only; no columns or tables</li>
            <li>Each question must be numbered, followed by a period or parenthesis (eg "1. How many..." or "2) Which...")</li>
            <li>Each question must be followed by 2 or more lettered choices, each signified by a letter followed by a period or parenthesis (eg. "A. True" or "B) False")</li>
            <li>Each question must have the letter of its correct answer listed after its choices, signified by "Ans:" or "Answer:" (eg "Answer: C")</li>
            <li>File must be saved in .doc format (not .docx or any other filetype)</li>
          <ol></small><br>
          <input type="file" class="form-control-file" name="fileToUpload" id="fileToUpload"><br>
          <p id="uploadOutput"></p>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary btn-ok" id="uploadSubmitBtn">Upload File</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="inviteStudentsModal" tabindex="-1" role="dialog" aria-labelledby="inviteStudentsModalLabel">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header text-center">
          <h4 class="modal-title text-center" id="inviteStudentsModalLabel">Invite Students</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body text-center" id='inviteStudentsModalBody'>
          <p>Copy the following link and paste it in a D2L post, or anywhere else that only students in your course will see it.</p>
          <textarea readonly id="studentReglink" class="form-control"></textarea>
          <p>Students can click the link to add this course to their Awesominds account, adding it to their in-game course list.</p>
          <p>Other instructors can click the link to add this course to their Awesominds account, giving them access to course management features for this course.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary btn-ok" data-dismiss="modal">OK</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header text-center">
          <h4 class="modal-title text-center" id="editModalLabel">Editing Question</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body container text-center" id='editModalBody'>
          <form id='editQuestionForm'>
            <label class="col-form-label" for="questionText">Question Text</label>

            <div class="form-group row" id="questionRow">
              <textarea name="questionText" class="col-sm-12 form-control question" id="questionText" required rows="3"></textarea>
            </div>

            <p><small>Add up to 6 options and select the <i class="fa fa-check" aria-hidden="true"></i> next to the correct answer for this question.<br>
            Click the <i class="fa fa-trash" aria-hidden="true"></i> button next to an option to remove it.</small></p>
            <label class="col-form-label" for="optionText">Options</label>

            <div class="form-group input-group optionRow" id="optionRow0">
              <span class="input-group-btn"><button type="button" class="btn btn-danger deleteOptionBtn"><i class="fa fa-trash fa-lg" aria-hidden="true"></i></button></span>
              <span class="input-group-addon" id="optionLetter" value="A">A</span>
              <input name="optionLetterHidden" id="optionLetterHidden" type="hidden" value="A">

              <input name="optionText" type="text" class="form-control question" id="optionText">

              <span class="input-group-addon">
                <i class="fa fa-check fa-lg" aria-hidden="true"></i>
                <input type="radio" name="answer" id="answerRadio" value="A" checked>
              </span>
            </div>

            <div class="form-group" id="addOption">
              <button id="addOptionBtn" type="button" class="btn btn-success">+ Add Option</button>
              <div id="limitMessage"></div>
            </div>

          </form>
          <div id="editOutput"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary btn-ok" data-dismiss="modal" id="saveQuestionBtn">Save Changes</button>
          <button type="button" class="btn btn-primary btn-ok" data-dismiss="modal" id="newQuestionBtn">Save Question</button>
          <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>

<script>
var selectedCourse = "";
var courses = [];
var selectedChapter = 0;
var thingToDelete = "";
var questions = [];
var questionid = 0;
var table = null;

function nextLetter(s){
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

function deleteOption(thing){ //delete an option, then reletter all options to stick to ABC pattern
  if($('div[id^="optionRow"]').length > 1){
    var rowsBefore = thing.parents();
    rowsBefore[1].remove();
    numTotal = $('div[id^="optionRow"]').length;
    if (numTotal < optionLimit){
      $("#addOptionBtn").prop("disabled", false);
      $("#limitMessage").empty();
    }
    var row = $('div[id^="optionRow"]')[0];
    $(row).find('#optionLetter').val('A');
    $(row).find('#optionLetter').html('A');
    $(row).find('#optionLetterHidden').val('A');
    $(row).find('#answerRadio').val('A');
    for (var i = 1; i < $('div[id^="optionRow"]').length; i++) {
      var row = $('div[id^="optionRow"]')[i];
      var prevRow = $('div[id^="optionRow"]')[i-1];
      var letter = $(prevRow).find('#optionLetter').val();
      $(row).find('#optionLetter').val(nextLetter(letter));
      $(row).find('#optionLetter').html(nextLetter(letter));
      $(row).find('#optionLetterHidden').val(nextLetter(letter));
      $(row).find('#answerRadio').val(nextLetter(letter));
    }
    //if answer was deleted, mark the last option as the answer to avoid having an undefined answer
    if (!$("input[name='answer']:checked").val()) $("input[name='answer']:last").prop("checked", true);
    //disable delete button if there's only one option left
    if($('div[id^="optionRow"]').length <= 1) $('.deleteOptionBtn').prop("disabled", true);
  }
}

function addOption(){
  numTotal = $('div[id^="optionRow"]').length;
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
    if($('div[id^="optionRow"]').length > 1) $('.deleteOptionBtn').prop("disabled", false);
    $(".deleteOptionBtn").off('click');
    $(".deleteOptionBtn").click(function(){
      deleteOption($(this));
    });
    if(numTotal >= optionLimit){
      $("#addOptionBtn").prop("disabled", true);
      $("#limitMessage").html('<p><small>Limit ' + optionLimit + ' options per question</small></p>');
    }
  }
};

var getCourses = function(){
  $.ajax({
    url: 'getcourses.php',
    success: function(data){
      $('#courseDropdown').empty();
      $('#courseDropdown').append('<option value="null">Select a Course</option>');
      $("#selectedCourseOutput").empty();
      courses = $.parseJSON(data);
      for (var i = 0; i < courses.length; i++) {
        $('#courseDropdown').append('<option value="' + courses[i].courseid + '">' + courses[i].courseid + ' - ' + courses[i].name + '</option>');
      }
      <?php
        if(isset($_GET["courseid"])){
          $c = $_GET["courseid"];
          echo "if ($('#courseDropdown option[value=\"$c\"]').length > 0){
                  selectedCourse = '$c';
                  $('.selectChapterUI').show();
                  $.ajax({
                    type: 'POST',
                    url: 'setcourse.php',
                    data: { course: selectedCourse },
                    success: function(data){
                      $('#courseDropdown').val(selectedCourse);
                      getChapters(selectedCourse);
                    }
                  });
                }";
        }
      ?>
    }
  });
}

var getChapters = function(course){
  $('#chapterDropdown').empty();
  $('#selectedChapterText').empty();
  $('#output').empty();
  $('#output').hide();
  $.ajax({
    url: 'api-getchapters-chaptertable.php',
    data: 'courseid=' + course,
    success: function(data){
      var chapters = $.parseJSON(data);
      $('#chapterDropdown').empty();
      $('#chapterDropdown').append('<option value="null">Select a Chapter</option>');
      for (var i = 0; i < chapters.length; i++) {
        $('#chapterDropdown').append('<option value="' + chapters[i].chapterid + '">' + chapters[i].chapterid + ' - ' + chapters[i].chaptername + '</option>');
      }
      $('#selectChapterDiv').show();
      // $('#selectChapterText').show();
      $('.newChapterBtn').html('Create New Chapter in Course "' + selectedCourse + '"');
      $("#selectedCourseOutput").html('<br><p><button id="inviteStudentsBtn" data-toggle="modal" data-target="#inviteStudentsModal" class="btn btn-primary">Invite Students</button> <button id="deleteCourseBtn" data-toggle="modal" data-target="#confirmDelete" class="btn btn-danger">Delete Course "'+ selectedCourse +'"</button></p>');
      $('#deleteCourseBtn').click(function(){
        $('#modalBody2').html('Are you sure you want to delete the course "' + selectedCourse + '"?');
        thingToDelete = 'course';
      });
      <?php
      if(isset($_GET["chapter"])){
        $ch = $_GET["chapter"];
        echo "if($('#chapterDropdown option[value=\"$ch\"]').length > 0){
                selectedChapter = '$ch';
                $('#chapterDropdown').val(selectedChapter);
                $.ajax({
                  type: 'POST',
                  url: 'setchapter.php',
                  data: { chapterid: selectedChapter },
                  success: function(data){
                    getQuestions();
                  }
                });
              }";
      }
      ?>
      $('#inviteStudentsBtn').off('click');
      $('#inviteStudentsBtn').click(function(){
        for (var i = 0; i < courses.length; i++) { //find this course's regcode and make the link
          if(courses[i].courseid === selectedCourse){
            var url = window.location.href.substring(0, window.location.href.indexOf("inst-coursemgmt.php"));
            $('#studentReglink').val(url + '?regcode=' + courses[i].regcode);
          } 
        }
        $('#studentReglink').off('click');
        $('#studentReglink').click(function(){ this.select(); });
        $('#inviteStudentsModalLabel').html('Invite Students to ' + selectedCourse);
      });

      $("#editChapterBtn").off('click');
      $('#editChapterBtn').click(function(){
        $('#createChapterBtn').hide();
        $('#updateChapterBtn').show();
        $('#chapterIDinput').prop('readonly', true);
        $('#createChapterModalLabel').html('Edit Chapter ' + selectedCourse + ' - ' + selectedChapter);
        $('#createChapterModalDesc').html('You may edit the name and dates of the selected chapter here');
        $.ajax({
          type: 'GET',
          url: 'api-getonechapter.php?chapter=' + selectedChapter,
          dataType: 'json',
          success: function(data){
            $('#chapterIDinput').val(data.chapterid);
            $('#chapterNameinput').val(data.chaptername);
            $('#date_start_input').val(data.date_start);
            $('#date_end_input').val(data.date_end);
          }
        });
      });
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
      $('#output').show();
      var htmlStr = '<h3>'+ selectedCourse + ' - Chapter ' + selectedChapter + ' Questions</h3><p><button class="btn btn-success addQuestionBtn" data-toggle="modal" data-target="#editModal">Add Question</button> <button class="btn btn-success uploadQuestionsBtn" data-toggle="modal" data-target="#uploadModal">Upload .doc File of Questions</button></p><table id="table" class="display table table-hover table-bordered text-left"><thead><tr><th>ID#</th><th>Question Text</th><th>Choices</th><th>Answer</th><th>Options</th></tr></thead><tbody>';
      for (var i = 0; i < data.length; i++) {
        var q = $.parseJSON(data[i]["question"]);
        var id = $.parseJSON(data[i]["questionid"]);
        htmlStr += '<tr id="row' + id + '"><td>' + id +'</td><td>' + q.question + '</td><td>'
        Object.keys(q.choices).forEach(function(key){
          htmlStr += key + ': ' + q.choices[key] + '<br>';
        });
        htmlStr += '</td><td>' + q.answer + '</td><td><button class="btn btn-primary editBtn" data-toggle="modal" data-target="#editModal" id="' + id + '"">Edit</button><br><button class="btn btn-danger deleteQuestionBtn" data-toggle="modal" data-target="#confirmDelete" id="' + id + '">Delete</button></td></tr>';
      }
      htmlStr += '</tbody></table>';
      $('#output').html(htmlStr);
      $('#selectedChapterOutput').show();

      $('.uploadQuestionsBtn').click(function(){
        $('#uploadModalLabel').html('Upload Questions to "' + selectedCourse + ' - '+ selectedChapter + '"');
      });

      $("#deleteChapterBtn").click(function(){
        $('#modalBody2').html('Are you sure you want to delete Chapter ' + selectedChapter + ' from ' + selectedCourse + '?');
        thingToDelete = 'chapter';
      });
      table = $('#table').DataTable({ paging: false, "order": [[0, 'asc']] });

      $('.deleteQuestionBtn').click(function(){
        $('#modalBody2').html('Are you sure you want to delete question #' + $(this).attr('id') + '?');
        thingToDelete = 'question';
        questionid = $(this).attr('id');
      });

      $(".editBtn").off('click');
      $('.editBtn').click(function(){
        $('#saveQuestionBtn').show();
        $('#newQuestionBtn').hide();
        questionid = $(this).attr('id');
        $('#editModalLabel').html('Edit Question #' + questionid);
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
            if($('div[id^="optionRow"]').length <= 1) $('.deleteOptionBtn').prop("disabled", true);
            $('#answerRadio[value='+ q.answer +']').prop("checked", true);
            numTotal = $('div[id^="optionRow"]').length;
            if (numTotal < optionLimit){
              $("#addOptionBtn").prop("disabled", false);
              $("#limitMessage").empty();
            }
          }
        });
      });

      $(".addQuestionBtn").off('click');
      $('.addQuestionBtn').click(function(){
        $('#saveQuestionBtn').hide();
        $('#newQuestionBtn').show();
        $('#editModalLabel').html('Add Question');
        $('.question').val('');
        $("input[name='answer']:last").prop("checked", true);
        $('.deleteOptionBtn').prop("disabled", true);
        $("#addOptionBtn").prop("disabled", false);
        $("#limitMessage").empty();
      });

      function prepQuestion(){
        var formArray = $('#editQuestionForm').serializeArray();
        var questionBank = {};
        questionBank.choices = {};
        for (var i = 0; i < formArray.length; i++) {
          switch (formArray[i].name) {
            case 'optionText':
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
        return questionBank;
      };

      $("#saveQuestionBtn").off('click');
      $('#saveQuestionBtn').click(function(){
        var questionBank = prepQuestion();
        $.ajax({
          type: 'POST',
          url: 'api-updatequestion.php',
          data: { questionBank: prepQuestion(), questionid: questionid },
          success: function(data) {
            getQuestions();
            $('div[id^="optionRow"]').not(':first').remove();
            numTotal = 1;
          }
        });
      });

      $("#newQuestionBtn").off('click');
      $('#newQuestionBtn').click(function(){
        var questionBank = prepQuestion();
        $.ajax({
          type: 'POST',
          url: 'api-insert.php',
          data: { questionBank: prepQuestion(), chapter: selectedChapter, courseid: selectedCourse },
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

var createForm = $('#createCourseForm');
createForm.submit(function (e) {
  e.preventDefault();
  $.ajax({
    type: createForm.attr('method'),
    url: createForm.attr('action'),
    data: createForm.serialize(),
    success: function(data) {
      window.location.href = "inst-coursemgmt.php?courseid=" + $('#courseIDinput').val().toUpperCase();
    }
  });
});

var createChapterForm = $('#createChapterForm');
createChapterForm.submit(function (e) {
  e.preventDefault();
  var postData = {
    courseid : selectedCourse,
    chapterid : $('#chapterIDinput').val(),
    chaptername: $('#chapterNameinput').val(),
    date_start: $('#date_start_input').val(),
    date_end: $('#date_end_input').val()
  };
  var url = createChapterForm.attr('action');
  if(document.activeElement.id == 'updateChapterBtn') url = 'api-updatechapter.php';
  $.ajax({
    type: createChapterForm.attr('method'),
    url: url,
    data: postData,
    success: function(data) {
      if(data.includes('successfully')){
        window.location.href = "inst-coursemgmt.php?courseid=" + selectedCourse + "&chapter=" + $('#chapterIDinput').val();
      } else if(data.includes('Duplicate')){
        $('#createChapterOutput').html('Error creating chapter - chapter number already exists!');
      }
    }
  });
});

//configuration
var max_file_size           = 1048576 * 3; //allowed file size. (1 MB = 1048576)
var result_output           = '#uploadOutput'; //ID of an element for response output
var total_files_allowed     = 1; //Number files allowed to upload

//on form submit
$('#uploadForm').submit(function(e) {
  e.preventDefault();
  var proceed = true; //set proceed flag
  var error = []; //errors
  var total_files_size = 0;

  if(!window.File && window.FileReader && window.FileList && window.Blob){ //if browser doesn't supports File API
    error.push("Your browser does not support new File API! Please upgrade."); //push error text
  }else{
    var total_selected_files = this.elements['fileToUpload'].files.length; //number of files

    //limit number of files allowed
    if(total_selected_files > total_files_allowed){
      error.push( "You have selected "+total_selected_files+" file(s), " + total_files_allowed +" is maximum!"); //push error text
      proceed = false; //set proceed flag to false
    }
     //iterate files in file input field
    $(this.elements['fileToUpload'].files).each(function(i, ifile){
      if(ifile.value !== ""){ //continue only if file(s) are selected
        total_files_size = total_files_size + ifile.size; //add file size to total size
      }
    });

    //if total file size is greater than max file size
    if(total_files_size > max_file_size){
      error.push( "You have "+total_selected_files+" file(s) with total size "+total_files_size+", Allowed size is " + max_file_size +", Try smaller file!"); //push error text
      proceed = false; //set proceed flag to false
    }

    var submit_btn  = $('#uploadSubmitBtn'); //form submit button

    //if everything looks good, proceed with jQuery Ajax
    if(proceed){
      submit_btn.val("Please Wait...").prop( "disabled", true); //disable submit button
      var form_data = new FormData(this); //Creates new FormData object
      var post_url = $(this).attr("action"); //get action URL of form

      //jQuery Ajax to Post form data
      $.ajax({
        url : post_url,
        type: "POST",
        data : form_data,
        contentType: false,
        cache: false,
        processData:false,
        mimeType:"multipart/form-data"
      }).done(function(res){ //
        $('#uploadForm')[0].reset(); //reset form
        $(result_output).html(res + '<p><a href="inst-coursemgmt.php?courseid=' + selectedCourse + '&chapter=' + selectedChapter + '">View Questions</a></p>'); //output response from server
        submit_btn.val("Upload file").prop( "disabled", false); //enable submit button once ajax is done
      });
    }
  }

  $(result_output).empty(); //reset output

});

$('.newChapterBtn').click(function(){
  $('#createChapterBtn').show();
  $('#updateChapterBtn').hide();
  $('#createChapterForm').trigger('reset');
  $('#chapterIDinput').prop('readonly', false);
  $('#createChapterModalLabel').html('Create Chapter in Course "' + selectedCourse + '"');
  $('#createChapterModalDesc').html('Enter a chapter number, chapter name, and availability dates to create a new chapter.');
  $('#date_start_input').val(moment().format("YYYY-MM-DDTHH:mm"));
  $('#date_end_input').val(moment().add(14, 'days').format("YYYY-MM-DDTHH:mm"));
});

$(function (){
  $('.selectChapterUI').hide();
  $('#selectChapterDiv').hide();
  // $('#selectChapterText').hide();
  $('#output').hide();

  $('#editModal').on('hide.bs.modal', function () {
    $('div[id^="optionRow"]').not(':first').remove();
    numTotal = 1;
  })

  $('#uploadModal').on('hide.bs.modal', function () {
    getQuestions();
  })

  $("#courseDropdown").change(function(){
    $('#output').empty();
    if($('#courseDropdown').find(":selected").val() != 'null'){
      $('.selectChapterUI').show();
      selectedCourse = $('#courseDropdown').find(":selected").val();
      $.ajax({
        type: 'POST',
        url: 'setcourse.php',
        data: { course: selectedCourse },
        success: function(data){
          getChapters(selectedCourse);
        }
      });
    } else {
      $('.selectChapterUI').hide();
      $('#selectedCourseOutput').empty();
      $('#selectChapterDiv').hide();
      // $('#selectChapterText').hide();
      $('#output').hide();
    }
    $('#selectedChapterOutput').hide();
  });

  $("#chapterDropdown").change(function(){
    if($('#chapterDropdown').find(":selected").val() != 'null'){
      selectedChapter = $('#chapterDropdown').find(":selected").val();
      $.ajax({
        type: 'POST',
        url: 'setchapter.php',
        data: { chapterid: selectedChapter },
        success: function(data){
          getQuestions();
        }
      });
    } else {
      $('#output').empty();
      $('#output').hide();
    }
  });

  $('#deleteBtn').click(function(){
    switch (thingToDelete) {
      case 'question':
        $.ajax({
          type: 'POST',
          url: 'api-deletequestion.php',
          data: { questionid : questionid },
          success: function(data){
            getQuestions();
          }
        });
        break;
      case 'course':
        $.ajax({
          type: 'POST',
          url: 'db-deletecourse.php',
          data: { courseid: selectedCourse, deletingCourse: true },
          success: function(data){
            getCourses();
            $('#selectChapterDiv').hide();
            $('.selectChapterUI').hide();
          }
        });
        break;
      case 'chapter':
        $.ajax({
          type: 'POST',
          url: 'db-deletecourse.php',
          data: { courseid: selectedCourse, deletingCourse: false, chapter: selectedChapter },
          success: function(data){
            getChapters(selectedCourse);
            $('#selectedChapterOutput').hide();
          }
        });
        break;
      default:
        break;
    }
  });

  $("#addOptionBtn").click(function(){
    addOption();
  });

  getCourses();
});
</script>

</body>
</html>

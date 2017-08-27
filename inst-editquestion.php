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
  <title>Edit Question - Awesominds</title>
</head>
<body>
  <?php include 'inst-nav2.php' ?>
  <div class="container text-center">
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
      <button id="saveQuestionBtn" type="button" class="btn btn-primary">Save Question</button>

    </form>
    <div id="output"></div>
  </div>

<script>
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
//TODO make sure limit is enforced server side as well
var numTotal = 1;
var mode = <?php echo json_encode($_GET["mode"]); ?>;
if(mode == 'edit'){
  var questionid = <?php echo json_encode($_GET["qid"]); ?>;
}

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

$(function (){
  $("#addOptionBtn").click(function(){
    addOption();
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
    // console.log(questionBank);
    if(mode != 'edit'){
      $.ajax({
        type: 'POST',
        url: 'api-insert.php',
        //TODO add chapter and courseid properly
        data: { questionBank: questionBank, chapter: 1, courseid: 'BUTT666' },
        success: function(data) {
          $('#output').html(data);
          // console.log(data);
        },
        error: function(data) {
          // console.log(data);
        },
      });
    } else {
      // console.log('edit mode yo');
      // console.log(questionBank);
      $.ajax({
        type: 'POST',
        url: 'api-updatequestion.php',
        //TODO add chapter and courseid properly
        data: { questionBank: questionBank, questionid: questionid },
        success: function(data) {
          $('#output').html(data);
          // console.log(data);
        },
        error: function(data) {
          // console.log(data);
        },
      });
    }
  });

  if(mode == 'edit'){
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
  }

});
</script>
</body>
</html>

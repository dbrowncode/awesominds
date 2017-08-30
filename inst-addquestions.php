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
  <title>Add Chapter/Game - Awesominds</title>
</head>
<body>
  <?php include 'inst-nav2.php' ?>
  <div class="container text-center">
  <div class="formWrap form">
    <h2>Add Chapter/Game</h2><br>
    <p id="selectCourseText">Select a course to add a new chapter/game of questions to</p>
    <div id='selectCourseDiv' class='input-group container' style="max-width: 400px">
      <select class="form-control" id='courseDropdown'>
        <!-- <option value="default">No Courses Found</option> -->
      </select>
      <span class="input-group-btn"><button class="btn btn-primary" id='selectBtn' value='Select'>Select</button></span>
    </div>
    <p class="uploadText"><br>Select a .doc or .txt file of questions to upload. Chapter number must be included in the file.</p><br>
    <div id='uploadDiv' class='form-group container' style="max-width: 400px">
      <form action="upload2.php" method="post" enctype="multipart/form-data" id="uploadForm">
        <!-- <div class="input-group"> -->
          <input type="file" class="form-control-file" name="fileToUpload" id="fileToUpload"><br>
          <button class='btn btn-primary' type="submit" value="Upload File" name="submit">Upload File</button>
        <!-- </div> -->
      </form>
      <p id="output"></p>
    </div>
  </div>
</div>

<!--jquery goes here-->
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
$(function (){
  $('.uploadText').hide();
  $('#uploadDiv').hide();
  $("#selectBtn").click(function(){
    $.ajax({
      type: 'POST',
      url: 'setcourse.php',
      data: { course: $('#courseDropdown').find(":selected").val() },
      success: function(data){
        $('.uploadText').show();
        $('#uploadDiv').show();
        var selectedCourseText = '<p>Course: ' + $.parseJSON(data).course + '</p><p><a href="inst-addquestions.php">Back to Select</a></p>';
        $('#uploadDiv').append(selectedCourseText);
        $('#selectCourseDiv').hide();
        $('#selectCourseText').hide();
      }
    });
  });
  getCourses();
});

//configuration
var max_file_size           = 1048576 * 3; //allowed file size. (1 MB = 1048576)
var result_output           = '#output'; //ID of an element for response output
var total_files_allowed     = 1; //Number files allowed to upload

//on form submit
$('#uploadForm').on( "submit", function(event) {
    event.preventDefault();
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

        var submit_btn  = $(this).find("input[type=submit]"); //form submit button

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
                var courseid = $('#courseDropdown').find(":selected").val();
                var chapter = res.match(/chapter \d+/)[0].match(/\d+/)[0];
                console.log(chapter);
                $(result_output).html(res + '<p><a href="inst-viewquestions.php?courseid=' + courseid + '&chapter=' + chapter + '">View Questions</a></p>'); //output response from server
                console.log(res);
                submit_btn.val("Upload file").prop( "disabled", false); //enable submit button once ajax is done
            });
        }
    }

    $(result_output).html(""); //reset output
    $(error).each(function(i){ //output any error to output element
        $(result_output).append('<div class="error">'+error[i]+"</div>");
    });

});
</script>
</body>
</html>

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
</head>
<body>
  <a href="index.php"><i class="fa fa-home fa-2x" aria-hidden="true"></i></a>

  <div class="formWrap form">
    <?php include 'inst-nav.php' ?>
    <div id='selectCourseDiv' class='tab-content field-wrap'>
      <p>Select a course to add questions to</p>
      <select id='courseDropdown'>
        <option value="default">No Courses Found</option>
      </select>
      <button id='selectBtn' value='Select'>Select</button>
    </div>
    <div id='uploadDiv' class='tab-content field-wrap'>
      <form action="upload3.php" method="post" enctype="multipart/form-data" id="uploadForm">

          <p>Select a .doc or .txt file of questions to upload</p>
          <input type="file" name="fileToUpload" id="fileToUpload"><br>
          <input class='button button-block' type="submit" value="Upload file" name="submit">

      </form>
      <p id="output"></p>
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
  $('#uploadDiv').hide();
  $("#selectBtn").click(function(){
    $.ajax({
      type: 'POST',
      url: 'setcourse.php',
      data: { course: $('#courseDropdown').find(":selected").val() },
      success: function(data){

        $('#uploadDiv').show();
        var selectedCourseText = '<p>Course: ' + $.parseJSON(data).course + '</p><p><a href="inst-addquestions.php">Back to Select</a></p>'
        $('#uploadDiv').append(selectedCourseText);
        $('#selectCourseDiv').hide();
      }
    });
  });
  getCourses();
});
</script>
<script>
var create_output           = '#confirm';
var createForm              = $('#createCourseForm');
createForm.submit(function (e) {
  e.preventDefault();
  $.ajax({
    type: createForm.attr('method'),
    url: createForm.attr('action'),
    data: createForm.serialize(),
    success: function(data) {
     $(create_output).html(data);
      console.log(data);
    },
  error: function(data) {
    console.log(data);
  },
 });
});
</script>

<script>
//configuration
var max_file_size           = 1048576 * 3; //allowed file size. (1 MB = 1048576)
//var allowed_file_types      = ['application/msword']; //allowed file types
var result_output           = '#output'; //ID of an element for response output
var my_form_id              = '#uploadForm'; //ID of an element for response output
var total_files_allowed     = 1; //Number files allowed to upload

//on form submit
$(my_form_id).on( "submit", function(event) {
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
                $(my_form_id)[0].reset(); //reset form
                $(result_output).html(res); //output response from server
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

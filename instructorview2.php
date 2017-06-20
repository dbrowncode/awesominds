<!DOCTYPE html>
<html>
<head>

  <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
  <script src="js/jquery.easing.1.3.js"></script>
  <title>Awesominds Instructor View</title>
  <style>
    /*custom font*/
    @import url(https://fonts.googleapis.com/css?family=Montserrat);

    /*basic reset*/
    * {margin: 0; padding: 0;}

    html {
    	height: 100%;
      background-color: #078EB7;
    	/*background:
    		linear-gradient(rgba(196, 102, 0, 0.6), rgba(155, 89, 182, 0.6));*/
    }

    body {
    	font-family: montserrat, arial, verdana;
    }
    /*form styles*/
    #msform {
    	width: 400px;
    	margin: 50px auto;
    	text-align: center;
    	position: relative;
    }
    #msform fieldset {
    	background: white;
    	border: 0 none;
    	border-radius: 3px;
    	box-shadow: 0 0 15px 1px rgba(0, 0, 0, 0.4);
    	padding: 20px 30px;
    	box-sizing: border-box;
    	width: 80%;
    	margin: 0 10%;

    	/*stacking fieldsets above each other*/
    	position: relative;
    }
    /*Hide all except first fieldset*/
    #msform fieldset:not(:first-of-type) {
    	display: none;
    }
    /*inputs*/
    #msform input, #msform textarea {
    	padding: 15px;
    	border: 1px solid #ccc;
    	border-radius: 3px;
    	margin-bottom: 10px;
    	width: 100%;
    	box-sizing: border-box;
    	font-family: montserrat;
    	color: #2C3E50;
    	font-size: 13px;
    }
    /*buttons*/
    #msform .action-button {
    	width: 100px;
    	background: #27AE60;
    	font-weight: bold;
    	color: white;
    	border: 0 none;
    	border-radius: 1px;
    	cursor: pointer;
    	padding: 10px 5px;
    	margin: 10px 5px;
    }
    #msform .action-button:hover, #msform .action-button:focus {
    	box-shadow: 0 0 0 2px white, 0 0 0 3px #27AE60;
    }
    /*headings*/
    .fs-title {
    	font-size: 15px;
    	text-transform: uppercase;
    	color: #2C3E50;
    	margin-bottom: 10px;
    }
    .fs-subtitle {
    	font-weight: normal;
    	font-size: 13px;
    	color: #666;
    	margin-bottom: 20px;
    }
    /*progressbar*/
    #progressbar {
    	margin-bottom: 30px;
    	overflow: hidden;
    	/*CSS counters to number the steps*/
    	counter-reset: step;
    }
    #progressbar li {
    	list-style-type: none;
    	color: white;
    	text-transform: uppercase;
    	font-size: 9px;
    	width: 33.33%;
    	float: left;
    	position: relative;
    }
    #progressbar li:before {
    	content: counter(step);
    	counter-increment: step;
    	width: 20px;
    	line-height: 20px;
    	display: block;
    	font-size: 10px;
    	color: #333;
    	background: white;
    	border-radius: 3px;
    	margin: 0 auto 5px auto;
    }
    /*progressbar connectors*/
    #progressbar li:after {
    	content: '';
    	width: 100%;
    	height: 2px;
    	background: white;
    	position: absolute;
    	left: -50%;
    	top: 9px;
    	z-index: -1; /*put it behind the numbers*/
    }
    #progressbar li:first-child:after {
    	/*connector not needed before the first step*/
    	content: none;
    }
    /*marking active/completed steps green*/
    /*The number of the step and the connector before it = green*/
    #progressbar li.active:before,  #progressbar li.active:after{
    	background: #27AE60;
    	color: white;
    }
  </style>
  <?php
    /* Main page with two forms: sign up and log in */
    // include 'css/css.html';
  ?>

</head>
<body>
<h4><a href='index.php'>Home</a></h4>
  <!-- multistep form -->
  <div id="msform">
    <!-- progressbar -->
    <ul id="progressbar">
      <li class="active">Create Course / Instructor</li>
      <li>Select Course</li>
      <li>Add Questions</li>
    </ul>
    <!-- fieldsets -->

    <fieldset id='fs1'>
      <h2 class="fs-title">Create Course</h2>
      <h3 class="fs-subtitle">Create a new course based on the alphanumeric course code and name.</h3>

      <!-- <form action="createCourse.php" method="post" id="createCourseForm"> -->

        <table>
          <tr><td>Course Code:</td><td><input type="text" name="courseID" id="courseID" required="true" placeholder="PSYC150" pattern="[A-Za-z]{3,4}[0-9]{3}" title="3-4 letters, 3 numbers, no spaces."></td></tr>
          <tr><td>Course Name:</td><td><input type="text" name="courseName" id="courseName" required="true" placeholder="PSYCHOLOGY 150"></td</tr>
        </table>
        <input type="submit" value="Create Course" name"createC" class='submitBtn'><br>

        <h2 class="fs-title">Add Instructor</h2>
        <h3 class="fs-subtitle">Grant instructor permissions to another account to let them view this page and add courses.</h3>
        <table class='field-wrap'>
          <tr><td>Instructor C number:</td><td><input type="text" name="instructorC" id="instructorC" required ="true" placeholder="CXXXXXXX"></td></tr>
        </table>
        <input type="submit" value="Activate Instructor" name'grantPermission' class='submitBtn'><br>
      <!-- </form> -->
      <div id="confirm"></div>

      <input type="button" name="next" class="next action-button" value="Next" />
    </fieldset>

    <fieldset id='fs2'>
      <h2 class="fs-title">Select Course</h2>
      <h3 class="fs-subtitle">Choose an available course to add questions to</h3>
      <select id='courseDropdown'>
        <option value="default">No Courses Found</option>
      </select><br>
      <input type="button" name="previous" class="previous action-button" value="Previous" />
      <input type="button" name="next" class="next action-button" value="Next" />
    </fieldset>
    <fieldset id='fs3'>
      <h2 class="fs-title">Add Questions</h2>
      <h3 class="fs-subtitle">Select a .doc or .txt file of questions to upload. Chapter will be detected.</h3>
      <form id='uploadForm'>
        <table class='field-wrap'>
          <tr><td><input type="file" name="fileToUpload" id="fileToUpload"></td></tr>
          <tr><td><input type="submit" id="uploadSubmit" value="Upload file" name="submit" class='submitBtn'></td></tr>
        </table>
        <div id="output"></div>
      </form>

      <input type="button" name="previous" class="previous action-button" value="Previous" />
    </fieldset>
  </div>


<!--jquery goes here-->
<script>
//configuration
var max_file_size           = 1048576 * 3; //allowed file size. (1 MB = 1048576)
//var allowed_file_types      = ['application/msword']; //allowed file types
var result_output           = '#output'; //ID of an element for response output
var my_form_id              = '#uploadForm'; //ID of an element for response output
var total_files_allowed     = 1; //Number files allowed to upload

var uploadFile = function(){
  var proceed = true; //set proceed flag
  var error = []; //errors
  var total_files_size = 0;

  if(!window.File && window.FileReader && window.FileList && window.Blob){ //if browser doesn't supports File API
      error.push("Your browser does not support new File API! Please upgrade."); //push error text
  }else{

      console.log($('#fileToUpload').prop('files').length);
      var total_selected_files = $('#fileToUpload').prop('files').length; //number of files

      //limit number of files allowed
      if(total_selected_files > total_files_allowed){
          error.push( "You have selected "+total_selected_files+" file(s), " + total_files_allowed +" is maximum!"); //push error text
          proceed = false; //set proceed flag to false
      }
       //iterate files in file input field
      $($('#fileToUpload').prop('files')).each(function(i, ifile){
          if(ifile.value !== ""){ //continue only if file(s) are selected
              total_files_size = total_files_size + ifile.size; //add file size to total size
          }
      });

      //if total file size is greater than max file size
      if(total_files_size > max_file_size){
          error.push( "You have "+total_selected_files+" file(s) with total size "+total_files_size+", Allowed size is " + max_file_size +", Try smaller file!"); //push error text
          proceed = false; //set proceed flag to false
      }

      var submit_btn  = $("#uploadSubmit"); //form submit button

      //if everything looks good, proceed with jQuery Ajax
      if(proceed){
          submit_btn.val("Please Wait...").prop( "disabled", true); //disable submit button
          var form_data = new FormData(this); //Creates new FormData object
          //var post_url = $(this).attr("action"); //get action URL of form

          //jQuery Ajax to Post form data
          $.ajax({
              url : 'upload2.php',
              type: "POST",
              data : form_data,
              contentType: false,
              cache: false,
              processData:false,
              mimeType:"multipart/form-data"
          }).done(function(res){ //
              $(my_form_id)[0].reset(); //reset form
              $(result_output).html(res); //output response from server
              submit_btn.val("Upload file").prop( "disabled", false); //enable submit button once ajax is done
          });
      }
  }

  $(result_output).html(""); //reset output
  $(error).each(function(i){ //output any error to output element
      $(result_output).append('<div class="error">'+error[i]+"</div>");
  });
}
$(function(){
  $(".submitBtn").click(function(ev){
    ev.preventDefault()// cancel form submission
    switch($(this).attr("value")){
      case "Activate Instructor":
        activateInstructor();
        break;
      case "Create Course":
        createCourse();
        break;
      case "Upload file":
        uploadFile();
        break;
      default:
        break;
    }


    // } else if ($(this).attr("value")=="Activate Instructor"){
    //
    // }

    // $("#my-form").submit(); if you want to submit the form
  });
});
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
</script>
<script>
  //jQuery time
  var current_fs, next_fs, previous_fs; //fieldsets
  var left, opacity, scale; //fieldset properties which we will animate
  var animating; //flag to prevent quick multi-click glitches

  $(".next").click(function(){
    if(animating) return false;
    animating = true;

    current_fs = $(this).parent();
    next_fs = $(this).parent().next();
    if(next_fs.is('#fs2')){
      getCourses();
    }
    if(next_fs.is('#fs3')){

    }

    //activate next step on progressbar using the index of next_fs
    $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

    //show the next fieldset
    next_fs.show();
    //hide the current fieldset with style
    current_fs.animate({opacity: 0}, {
      step: function(now, mx) {
        //as the opacity of current_fs reduces to 0 - stored in "now"
        //1. scale current_fs down to 80%
        scale = 1 - (1 - now) * 0.2;
        //2. bring next_fs from the right(50%)
        left = (now * 50)+"%";
        //3. increase opacity of next_fs to 1 as it moves in
        opacity = 1 - now;
        current_fs.css({
          'transform': 'scale('+scale+')',
          'position': 'absolute'
        });
        next_fs.css({'left': left, 'opacity': opacity});
      },
      duration: 800,
      complete: function(){
        current_fs.hide();
        animating = false;
      },
      //this comes from the custom easing plugin
      easing: 'easeInOutBack'
    });
  });

  $(".previous").click(function(){
    if(animating) return false;
    animating = true;

    current_fs = $(this).parent();
    previous_fs = $(this).parent().prev();
    if(previous_fs.is('#fs2')){
      getCourses();
    }

    //de-activate current step on progressbar
    $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

    //show the previous fieldset
    previous_fs.show();
    //hide the current fieldset with style
    current_fs.animate({opacity: 0}, {
      step: function(now, mx) {
        //as the opacity of current_fs reduces to 0 - stored in "now"
        //1. scale previous_fs from 80% to 100%
        scale = 0.8 + (1 - now) * 0.2;
        //2. take current_fs to the right(50%) - from 0%
        left = ((1-now) * 50)+"%";
        //3. increase opacity of previous_fs to 1 as it moves in
        opacity = 1 - now;
        current_fs.css({'left': left});
        previous_fs.css({'transform': 'scale('+scale+')', 'opacity': opacity});
      },
      duration: 800,
      complete: function(){
        current_fs.hide();
        animating = false;
      },
      //this comes from the custom easing plugin
      easing: 'easeInOutBack'
    });
  });

  $(".submit").click(function(){
    return false;
  })
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

</body>
</html>

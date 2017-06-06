var loadState = {
  preload: function() {

  },

  create: function() {
    game.global.letters = ['A', 'B', 'C', 'D'];
    game.global.questionShown = false;

        // get a chapter of questions from the database and load them into the questions array
    $(function (){
      $.ajax({
        type: 'GET',
        url: 'getquestion.php',
        data: { 'courseid': game.global.selectedCourse, 'chapter': game.global.selectedChapter },
        dataType: 'json',
        success: function(data){
          game.global.questions = [];
          for (var i = 0; i < data.length; i++) {
            game.global.questions[i] = $.parseJSON(data[i]["question"]);
          }
          console.log('chapter ' + game.global.selectedChapter + '; ' + game.global.questions.length + ' questions loaded');
          //once the questions are successfully loaded, move to the play state
          game.state.start('play');
        }
      });
    });

  }
};

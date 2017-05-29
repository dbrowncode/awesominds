var loadState = {
  preload: function() {

  },

  create: function() {
    game.global.letters = ['A', 'B', 'C', 'D'];
    game.global.questionShown = false;

    //Temporary math fixers
    game.global.answersShown = false;
    game.global.numCor = 0;
    game.global.numWro = 0;
    game.global.lXOffset = 16;
    game.global.rXOffset = 16;
    game.global.winStreak = 1;
    game.global.loseStreak = 1;      

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

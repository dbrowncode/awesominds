var loadState = {
  preload: function() {
    game.load.image('sky', 'assets/sky.png');
    game.load.spritesheet('button', 'assets/button_sprite_sheet.png', 193, 71);
  },

  create: function() {
    game.global.background = game.add.sprite(0, 0, 'sky');
    game.global.mainFont = { font: 'Arial', fontSize: '20px', fill: '#000' };
    game.global.optionFont = { font: 'Arial', fontSize: '26px', fill: '#fff', align: 'center'};
    game.global.letters = ['A', 'B', 'C', 'D'];
    game.global.numRounds = 4;
    game.global.totalStats = {
      numRight: 0,
      numWrong: 0,
      score: 0
    };

    game.global.btnClick = function(){
      //increment number of answered questions
      game.global.questionsAnswered++;
      console.log('pressed ' + this.data.letter + ', correct?: ' + this.data.correct, '; answered ' + game.global.questionsAnswered + ' Qs');

      // record correct or incorrect and update score
      if (game.global.roundStats[game.global.currentRound].correct){
        game.global.roundStats[game.global.currentRound].numRight++;
        game.global.roundStats[game.global.currentRound].score += 100;
      } else {
        game.global.roundStats[game.global.currentRound].numWrong++;
        game.global.roundStats[game.global.currentRound].score -= 50;
      }


      //show the next question if there is one
      if (game.global.questionsAnswered < game.global.qPerRound){
        game.global.showQuestion(game.global.questions[game.global.questionsAnswered]);
      } else {
        //if no questions left in the round, round is over
        game.global.removeQuestion();
        console.log(game.global);
        //TODO: go to "end of round" state, probably
      }
    };

    game.global.removeQuestion = function(){
      game.global.questionText.kill();
      for (var i = 0; i < 4; i++){
        game.global.buttons[i].data.text.kill();
        game.global.buttons[i].kill();
      }
    }

    game.global.showQuestion = function(question){
      //first clear any question that is already up
      if (game.global.questionsAnswered > 0){
        game.global.removeQuestion();
      }

      //then make the new question
      game.global.questionText = game.add.text(16, 16, question.question, game.global.mainFont);
      game.global.buttons = [];

      //Create a button for each choice, and put some data into it in case we need it
      for (var i = 0; i < 4; i++) {
        game.global.buttons[i] = game.add.button(game.world.centerX - 95, 100 + (71 * i), 'button', game.global.btnClick, game.global.buttons[i], 2, 1, 0);
        //Set the letter of this option
        game.global.buttons[i].data.letter = game.global.letters[i];
        //Storing the option text as part of this button's data just in case we need to access it later.
        //Center text over the button
        game.global.buttons[i].data.text = game.add.text(game.global.buttons[i].x + game.global.buttons[i].width/2, game.global.buttons[i].y + game.global.buttons[i].height/2, question.choices[game.global.letters[i]], game.global.optionFont);
        game.global.buttons[i].data.text.anchor.set(0.5);

        //Store a boolean that indicates whether this is the correct answer
        game.global.buttons[i].data.correct = (game.global.letters[i] == question.answer[0]);
      }
    };


    // get a chapter of questions from the database and load them into the questions array
    $(function (){
      $.ajax({
        type: 'POST',
        url: 'getquestion.php',
        data: { 'courseid': 150, 'chapter': 1 },
        dataType: 'json',
        success: function(data){
          game.global.questions = [];
          for (var i = 0; i < data.length; i++) {
            game.global.questions[i] = $.parseJSON(data[i]["question"]);
          }
          // set the number of questions per round
          // TODO: proper function to determine number of questions per round
          // game.global.qPerRound = data.length / game.global.numRounds;
          game.global.qPerRound = 12;
          //once the questions are successfully loaded, set up the rounds and move to the play state
          game.global.currentRound = 0;
          game.global.roundStats = [];
          for (var i = 0; i < game.global.numRounds; i++) {
            game.global.roundStats[i] = {};
            game.global.roundStats[i].numRight = 0;
            game.global.roundStats[i].numWrong = 0;
            game.global.roundStats[i].score = 0;
          }
          game.state.start('play');
        }
      });
    });

  }
};

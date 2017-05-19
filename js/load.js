var loadState = {
  preload: function() {
    //TODO: work on responsiveness using scalemanager
    //game.scale.scaleMode = Phaser.ScaleManager.RESIZE;
  },

  create: function() {
    game.global.background = game.add.sprite(0, 0, 'sky');
    //TODO: dynamic font sizes for responsiveness?
    game.global.mainFont = { font: 'Arial', fontSize: '18px', fill: '#000', align: 'center' };
    game.global.optionFont = { font: 'Arial', fontSize: '16px', fill: '#fff', align: 'center'};
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
      //TODO: determine score gain/loss amount based on time/other mechanics
      if (this.data.correct){
        game.global.roundStats[game.global.currentRound].numRight++;
        game.global.totalStats.numRight++;
        game.global.roundStats[game.global.currentRound].score += 100;
        game.global.totalStats.score += 100;
      } else {
        game.global.roundStats[game.global.currentRound].numWrong++;
        game.global.totalStats.numWrong++;
        game.global.roundStats[game.global.currentRound].score -= 50;
        game.global.totalStats.score -= 50;
      }

      //show the next question if there is one
      if (game.global.questionsAnswered < game.global.qPerRound){
        game.global.showQuestion(game.global.questions[game.global.questionsAnswered]);
      } else {
        //if no questions left in the round, round is over
        game.global.removeQuestion();
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

      //create a timer to delay showing the answer options by 2 seconds
      var timer = game.time.create(false);
      timer.add(2000, showChoices, this);
      timer.start();

      //then make the new question
      //TODO: add background sprite for the question
      game.global.questionText = game.add.text(game.world.width + 1000, 40, question.question, game.global.mainFont);
      game.global.questionText.anchor.set(0.5);

      game.add.tween(game.global.questionText).to({x: game.world.centerX}, 500, Phaser.Easing.Default, true, 250, 0, false);
      game.global.buttons = [];

      function showChoices(){
        //Create a button for each choice, and put some data into it in case we need it
        for (var i = 0; i < 4; i++) {
          game.global.buttons[i] = game.add.button(game.world.width + 1000, 100 + (71 * i), 'button', game.global.btnClick, game.global.buttons[i], 2, 1, 0);
          //Set the letter of this option
          game.global.buttons[i].data.letter = game.global.letters[i];
          //Storing the option text as part of this button's data just in case we need to access it later.
          game.global.buttons[i].data.text = game.add.text(0, 0, question.choices[game.global.letters[i]], game.global.optionFont);
          game.global.buttons[i].data.text.anchor.set(0.5);

          //Store a boolean that indicates whether this is the correct answer
          game.global.buttons[i].data.correct = (game.global.letters[i] == question.answer[0]);

          //animate button coming in
          game.add.tween(game.global.buttons[i]).to({x: game.world.centerX - game.global.buttons[i].width/2}, 500, Phaser.Easing.Default, true, 250 * i, 0, false);
        }
      }
    };


    // get a chapter of questions from the database and load them into the questions array
    $(function (){
      $.ajax({
        type: 'POST',
        url: 'getquestion.php',
        //TODO: use courseid and chapter chosen by user
        data: { 'courseid': 150, 'chapter': 1 },
        dataType: 'json',
        success: function(data){
          game.global.questions = [];
          for (var i = 0; i < data.length; i++) {
            game.global.questions[i] = $.parseJSON(data[i]["question"]);
          }
          // set the number of questions per round
          // TODO: proper function to determine number of questions per round
          // game.global.qPerRound = data.length / game.global.numRounds; something like this but accounting for remainder
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

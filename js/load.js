var loadState = {
  preload: function() {
    //TODO: work on responsiveness using scalemanager
    //game.scale.scaleMode = Phaser.ScaleManager.RESIZE;
  },

  create: function() {
    game.global.letters = ['A', 'B', 'C', 'D'];
    game.global.questionShown = false;
    game.global.numRounds = 4;
    game.global.totalStats = {
      numRight: 0,
      numWrong: 0,
      score: 0
    };

    //Temporary math fixers
    game.global.answersShown = false;
    game.global.numCor = 0;
    game.global.numWro = 0;

    game.global.btnClick = function(){

      //increment number of answered questions
      game.global.questionsAnswered++;
      game.global.roundStats[game.global.currentRound].answered++;
      console.log('pressed ' + this.data.letter + ', correct?: ' + this.data.correct, '; answered ' + game.global.questionsAnswered + ' Qs');


      if(game.global.numCor == 6){
	       game.global.numCor = 0;
      }
      if(game.global.numWro == 6){
	       game.global.numWro = 0;
      }
      // record correct or incorrect and update score
      //TODO: determine score gain/loss amount based on time/other mechanics
      if (this.data.correct){
        game.global.roundStats[game.global.currentRound].numRight++;
        game.global.totalStats.numRight++;
        if(game.global.answersShown == false){
		      game.global.roundStats[game.global.currentRound].score += 100;
        	game.global.totalStats.score += 100;
	      }else{
      		game.global.roundStats[game.global.currentRound].score += 50;
      		game.global.totalStats.score += 50;
	      }

      	//add up two stacks of 6, second stack has no limit yet.
      	if(game.global.totalStats.numRight <= 6){
      		correct = game.add.sprite(16,((game.height - 150) - (50 * game.global.numCor)) ,'right');
      	}
      	if(game.global.totalStats.numRight > 6){
      		correct = game.add.sprite(25,(game.height - 150) - (50 * game.global.numCor),'right');
      	}
      	correct.scale.setTo(.1,.1);
      	game.global.numCor++;

      } else {
        game.global.roundStats[game.global.currentRound].numWrong++;
        game.global.totalStats.numWrong++;
        game.global.roundStats[game.global.currentRound].score -= 50;
        game.global.totalStats.score -= 50;

      	if(game.global.totalStats.numWrong <= 6){
      		wrong = game.add.sprite((game.width - 16),((game.height - 150) - (50 * game.global.numWro)) , 'wrong');
      	}
      	if(game.global.totalStats.numWrong > 6){
      		wrong = game.add.sprite((game.width - 25),((game.height - 150) - (50 * game.global.numWro)), 'wrong');
      	}
      	wrong.scale.setTo(.1,.1);
      	game.global.numWro++;

      }
      //remove answers from screen, needs some timer magic as they pop up almost immediately.
      game.global.answersShown = false;
      for(i = 1; i < 4; i++){
	       game.global.chars[i].answer.kill();
      }

      //show the next question if there is one
      if (game.global.roundStats[game.global.currentRound].answered < game.global.qPerRound){
        game.global.showQuestion(game.global.questions[game.global.questionsAnswered]);
      } else {
        //if no questions left in the round, round is over
        game.global.removeQuestion();
        game.state.start('endOfRound');
      }
    };

    game.global.removeQuestion = function(){
      game.global.questionText.kill();
      for (var i = 0; i < 4; i++){
        game.global.buttons[i].data.text.kill();
        game.global.buttons[i].kill();

        //not sure if this is needed here, was testing stuff
        if(i>0 && game.global.answersShown){
          game.global.chars[i].answer.kill();
        }
      }
      game.global.questionShown = false;
    }

    game.global.showQuestion = function(question){
      //first clear any question that is already up
      if (game.global.questionShown){
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

      game.add.tween(game.global.questionText).to({x: game.world.centerX}, 500, Phaser.Easing.Default, true, 250);
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
          game.add.tween(game.global.buttons[i]).to({x: game.world.centerX - game.global.buttons[i].width/2}, 500, Phaser.Easing.Default, true, 250 * i);
        }
        game.global.questionShown = true;
        //add timer to delay until answers are shown.
        timer.add(2000, showAnswers, this);

        function showAnswers() {
          if(!game.global.answersShown){
          	game.global.answersShown = true;
          	for(i=1;i<4;i++){
          	  game.global.chars[i].answer = game.add.text((game.global.chars[i].sprite.x + game.global.chars[i].sprite.width), game.global.chars[i].sprite.centerY - 20, game.global.letters[i-1],game.global.mainFont);
          	}
          }
      	};

      };
    };


    // get a chapter of questions from the database and load them into the questions array
    $(function (){
      $.ajax({
        type: 'POST',
        url: 'getquestion.php',
        //TODO: use courseid and chapter chosen by user
        data: { 'courseid': game.global.selectedCourse, 'chapter': 1 },
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
            game.global.roundStats[i] = {
              numRight: 0,
              numWrong: 0,
              score: 0,
              answered: 0
            };
          }
          game.global.questionsAnswered = 0;
          game.state.start('play');
        }
      });
    });

  }
};

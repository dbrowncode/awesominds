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
    game.global.lXOffset = 16;
    game.global.rXOffset = 16;
    game.global.winStreak = 1;
    game.global.loseStreak = 1;
    game.global.btnClick = function(){

      //increment number of answered questions
      game.global.questionsAnswered++;
      game.global.roundStats[game.global.currentRound].answered++;
      console.log('pressed ' + this.data.letter + ', correct?: ' + this.data.correct, '; answered ' + game.global.questionsAnswered + ' Qs');

      //temp until I fix modulus methods.
      if(game.global.numCor == 5){
	       game.global.numCor = 0;
      }
      if(game.global.numWro == 5){
	       game.global.numWro = 0;
      }

      //add points to AI if correct
      for(i = 1 ; i < 4; i++){
          if(game.global.chars[i].correct){
             game.global.chars[i].score += 25;
          }
             
      }   

      // record correct or incorrect and update score
      //TODO: determine score gain/loss amount based on time/other mechanics
      if (this.data.correct){
        game.global.roundStats[game.global.currentRound].numRight++;
        game.global.totalStats.numRight++;
        game.global.numCor++;

        if(game.global.answersShown == false){
		    game.global.roundStats[game.global.currentRound].score += 100;
        	game.global.totalStats.score += 25;
	    }else{
      		game.global.roundStats[game.global.currentRound].score += 50;
      		game.global.totalStats.score += 10;
	    }
        //TODO adjust offsets every five questions
        //for some reason need 2 more than numWro to make five.
        if((game.global.totalStats.numRight + 1) % 7 == 0){
           game.global.lXOffset +=6;
        }
        
      	correct = game.add.sprite((game.global.lXOffset),((game.height - 150) - (50 * game.global.numCor)) ,'right');
      	correct.scale.setTo(.1,.1);
        game.global.loseStreak = 1;
        game.global.winStreak += 1;
        
      } else {
        game.global.roundStats[game.global.currentRound].numWrong++;
        game.global.totalStats.numWrong++;
        game.global.numWro++;
        game.global.roundStats[game.global.currentRound].score -= 50;
        game.global.totalStats.score += 2;
        //TODO fix offset mess doesn't always work
        if((game.global.totalStats.numWrong + 1) % 7 == 0){
            game.global.rXOffset += 6;
        }
      	wrong = game.add.sprite((game.width - game.global.rXOffset),((game.height - 150) - (50 * game.global.numWro)) , 'wrong');
        wrong.scale.setTo(.1,.1);
        game.global.loseStreak += 1;
        game.global.winStreak = 1;
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
      }
      game.global.questionShown = false;
    }

    game.global.showQuestion = function(question){
      //first clear any question that is already up
      if (game.global.questionShown){
        game.global.removeQuestion();
      }
     
      game.global.questionShown = false;
 
        //adjust ai by %5 up/down if on a streak
        if(game.global.winStreak % 4 == 0){
         for(i = 1; i < 4; i++){
            game.global.chars[i].chance += 5;
         }
        }else if(game.global.loseStreak % 4 == 0){
         for(i = 1; i < 4; i++){
            game.global.chars[i].chance -= 5;
         }
        }


      //create a timer to delay showing the answer options by 2 seconds
      game.global.timer = game.time.create(false);
      game.global.timer.add(2000, showChoices, this);
      game.global.timer.start();

      //then make the new question
      //TODO: add background sprite for the question
      game.global.questionText = game.add.text(game.world.width + 1000, 40, question.question, game.global.mainFont);
      game.global.questionText.anchor.set(0.5);

      game.add.tween(game.global.questionText).to({x: game.world.centerX}, 500, Phaser.Easing.Default, true, 250);
      game.global.buttons = [];

      //check if ai knows the answer.
      game.global.winThreshold = Math.floor(Math.random() * 100) + 1;
      //check if ai got it right
      console.log(game.global.winThreshold);
      for(i = 1; i < 4; i++){
          if(game.global.winThreshold <= game.global.chars[i].chance){
              game.global.chars[i].correct = true;
          }else{
              game.global.chars[i].correct = false;
          }
      }
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
        game.global.timer.stop();
        game.global.timer.add(2000, showAnswers, this);
        game.global.timer.start();

        //temporary fix to show answers,
        //TODO refactor once array is randomized
        function showAnswers() {
          if((!game.global.answersShown) && game.global.questionShown){
          	for(i=1;i<4;i++){
              if(game.global.chars[i].correct){
                game.global.chars[i].answer = game.add.text((game.global.chars[i].sprite.x + game.global.chars[i].sprite.width), game.global.chars[i].sprite.centerY - 20, game.global.questions[game.global.questionsAnswered].answer, game.global.mainFont);
              }else{
          	    game.global.chars[i].answer = game.add.text((game.global.chars[i].sprite.x + game.global.chars[i].sprite.width), game.global.chars[i].sprite.centerY - 20, game.global.letters[i-1], game.global.mainFont);
                //randomize answer so it isn't the correct one.
                while(game.global.chars[i].answer==game.global.questions[game.global.questionsAnswered].answer){
                    game.global.chars[i].answer = game.global.letters[Math.floor(Math.random() * 3)]; 
                }

              }
            }
            game.global.answersShown = true;
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

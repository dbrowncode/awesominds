var loadState = {
  preload: function() {
    //TODO: work on responsiveness using scalemanager
    //game.scale.scaleMode = Phaser.ScaleManager.RESIZE;
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

    game.global.btnClick = function(){
      //disable each button and alter its appearance
      for (var i = 0; i < game.global.buttons.length; i++) {
        game.global.buttons[i].inputEnabled = false;
      }
      //TODO: decide how button should look when pressed; find or make appropriate assets
      //for now this changes which frames it shows
      this.setFrames(0, 0, 0);

      //bring in a symbol of right or wrong
      game.global.symbol = game.add.sprite(game.width + 1000, Math.floor(this.centerY), this.data.correct ? 'check' : 'x');
      game.global.symbol.height = game.global.symbol.width = this.height;
      game.global.symbol.anchor.setTo(0.5,0.5);
      game.add.tween(game.global.symbol).to({x: this.right + 10}, 1000, Phaser.Easing.Default, true, 250);

      //increment number of answered questions
      game.global.questionsAnswered++;
      console.log('pressed ' + this.data.letter + ', correct?: ' + this.data.correct, '; answered ' + game.global.questionsAnswered + ' Qs');

      game.global.updateScores = function(answerCorrect){
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
        if (answerCorrect){
          game.global.totalStats.numRight++;
          game.global.numCor++;

          if(!game.global.answersShown){
          	game.global.totalStats.score += 25;
          }else{
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
          game.global.totalStats.numWrong++;
          game.global.numWro++;
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
      };

      game.global.timer.stop();
      game.global.timer.add(3000, animateOut, this);
      game.global.timer.start();

      function animateOut(){
        game.add.tween(game.global.questionText).to({x: game.world.x - 1000}, 500, Phaser.Easing.Default, true, 250);
        game.global.bubble.kill();
        for (var i = 0; i < game.global.buttons.length; i++) {
          game.add.tween(game.global.buttons[i]).to({x: game.world.x - 1000}, 500, Phaser.Easing.Default, true, 250);
        }
        game.add.tween(game.global.symbol).to({x: game.world.x - 1000}, 500, Phaser.Easing.Default, true, 250);
        game.global.updateScores(this.data.correct);
        //remove answers from screen
        game.global.answersShown = false;
        for(i = 1; i < 4; i++){
           game.global.chars[i].answer.kill();
        }

        game.global.timer.stop();
        game.global.timer.add(3000, nextQuestion, this);
        game.global.timer.start();

        function nextQuestion(){
          //show the next question if there is one
          if (game.global.questionsAnswered < game.global.numQuestions){
            game.global.showQuestion(game.global.questions[game.global.questionsAnswered]);
          } else {
            //if no questions left in the game, game is over
            game.global.removeQuestion();
            game.state.start('endOfGame');
          }
        };
      };
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
      game.global.bubble = game.world.add(new game.global.SpeechBubble(game, game.world.x + game.global.jinny.width, game.world.y + game.global.jinny.centerY, game.world.width * .8, "WHO let the dogs out who who who  who who who who who who who who who who who who  who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who who"));
      console.log(game.global.jinny.centerY);
      console.log(game.global.bubble);
      game.global.bubble.y += Math.floor(game.global.bubble.bubbleheight);

      game.add.tween(game.global.questionText).to({x: game.world.centerX}, 500, Phaser.Easing.Default, true, 250);
      game.global.buttons = [];

      //check if ai knows the answer.
      game.global.winThreshold = Math.floor(Math.random() * 100) + 1;
      //check if ai got it right
      console.log(game.global.winThreshold);
      for(i = 1; i < 4; i++){
        game.global.chars[i].correct = (game.global.winThreshold <= game.global.chars[i].chance);
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
          //once the questions are successfully loaded, move to the play state
          game.state.start('play');
        }
      });
    });

  }
};

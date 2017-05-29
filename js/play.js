var playState = {
  create: function(){
    console.log('state: play');
    game.global.questions = game.global.shuffleArray(game.global.questions);
    // set the number of questions per game; will use all questions if there are less than the desired number
    game.global.numQuestions = Math.min(2, game.global.questions.length);
    game.global.questionsAnswered = 0;
    game.global.totalStats = {
      numRight: 0,
      numWrong: 0,
      score: 0
    };
    game.global.music['menu'].pause();
    game.global.music['play'].play();
    game.global.jinny = game.add.sprite(0,0, 'jinny');
    game.global.jinny.scale.setTo(.1,.1);
    //set up game characters

    //create array of chances
    //shuffle for replayability.
    //assign win % to NPC
    var winChances = [20, 40, 60, 75];
    winChances = game.global.shuffleArray(winChances);

    game.global.chars = [];

    game.global.oppImageKeys = game.global.shuffleArray(game.global.oppImageKeys);
    var characterPercent = 0;
    for(var i = 0; i < 4; i++){
    	game.global.chars[i] = {};
      game.global.chars[i].sprite = game.add.sprite((((game.width/4)*(i+1)-game.width/4)) ,game.height - 110, game.global.oppImageKeys[i]);
      game.global.chars[i].sprite.scale.setTo(dpr/4,dpr/4);
      game.global.chars[i].score = 0;
      game.global.chars[i].scoreText = game.add.text((((game.width/4)*(i+1)-game.width/4)+game.global.chars[i].sprite.width), game.global.chars[i].sprite.centerY, game.global.chars[i].score, game.global.mainFont);
     	if(i!=0){
    		//placeholder text to make kill not break game.
    		game.global.chars[i].answer = game.add.text(0,0,'');
    		game.global.chars[i].chance = winChances[i];
        game.global.chars[i].correct = false;
        console.log('win chance for ' + i + ' = ' + game.global.chars[i].chance);
    	}
    }

    this.showQuestion(game.global.questions[game.global.questionsAnswered]);
  },
  update: function(){
    //update score text
    for (var i = 1; i < game.global.chars.length; i++) {
      game.global.chars[i].scoreText.text = game.global.chars[i].score;
    }
    game.global.chars[0].scoreText.text = game.global.totalStats.score;
  },
  resize: function(width, height){
    //TODO: redraw things on window resize; think about resizing text and elements
  },
  showQuestion: function(question){
    //first clear any question that is already up
    if (game.global.questionShown){
      playState.removeQuestion();
    }
    game.global.questionUI = game.add.group();
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
    game.global.questionNumText = game.add.text(0, 5, 'Q ' + (game.global.questionsAnswered + 1) + '/' + game.global.numQuestions, game.global.rightSideFont);
    game.global.questionNumText.setTextBounds(0, 5, game.width-10, game.height-10);
    game.global.questionUI.add(game.global.questionNumText);
    game.global.bubble = game.world.add(new game.global.SpeechBubble(game, game.world.width + 1000, game.world.y + game.global.jinny.centerY, game.world.width - (game.global.jinny.width*2), question.question, true));
    game.global.bubble.y += Math.floor(game.global.bubble.bubbleheight);
    game.global.questionUI.add(game.global.bubble);

    game.add.tween(game.global.bubble).to({x: game.world.x + game.global.jinny.width}, 500, Phaser.Easing.Default, true, 250);
    game.global.buttons = [];

    //check if ai knows the answer.
    game.global.winThreshold = Math.floor(Math.random() * 100) + 1;
    //check if ai got it right
    console.log('ai wins if over ' + game.global.winThreshold);
    for(i = 1; i < 4; i++){
      game.global.chars[i].correct = (game.global.winThreshold <= game.global.chars[i].chance);
    }

    function showChoices(){
      //Create a button for each choice, and put some data into it in case we need it
      game.global.choiceBubbles = game.add.group();
      var i = 0;
      var prevHeights = 0;
      for (var c in question.choices) {
        var cb = game.world.add(new game.global.SpeechBubble(game, game.world.width + 1000, Math.max(game.global.jinny.height, game.global.bubble.y), game.world.width - (game.global.jinny.width*2), c + '. ' + question.choices[c], false));
        cb.y += Math.floor(cb.bubbleheight + prevHeights);
        prevHeights += cb.bubbleheight;
        game.add.tween(cb).to({x: game.global.jinny.width}, 500, Phaser.Easing.Default, true, 250 * i);
        cb.data = {
          letter: c,
          text: c + '. ' + question.choices[c],
          correct: (c == question.answer[0])
        };
        cb.inputEnabled = true;
        //cb.events.onInputOver.add(cb.data.over, cb);
        //cb.events.onInputOut.add(cb.data.out, cb);
        cb.events.onInputDown.add(this.btnClick, cb);
        game.global.choiceBubbles.add(cb);
        i++;
      }
      game.global.questionUI.add(game.global.choiceBubbles);

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
              choice = game.global.letters[Math.floor(Math.random() * 4)];
              answer = game.global.questions[game.global.questionsAnswered].answer;
              //strip any whitespace so the god damn comparisons will work... this stupid bug took way to fucking long to fix.
              answer = answer.replace(/(^\s+|\s+$)/g,"");
              choice = choice.replace(/(^\s+|\s+$)/g,"");

              //randomize answer so it isn't the correct one.
              while(choice == answer){
                console.log('first conditional worked');
                choice = game.global.letters[Math.floor(Math.random() * 4)];
                console.log('ai choosing answer' + choice);
              }
              game.global.chars[i].answer = game.add.text((game.global.chars[i].sprite.x + game.global.chars[i].sprite.width), game.global.chars[i].sprite.centerY - 20, choice, game.global.mainFont);
              game.global.questionUI.add(game.global.chars[i].answer);
            }
          }
          game.global.answersShown = true;
        }
      };

    };
  },

  btnClick : function(){
    //disable each button and alter its appearance
    game.global.choiceBubbles.forEach( function(item){ item.inputEnabled = false; } );
    //TODO: decide how button should look when pressed; find or make appropriate assets
    this.tint = 0x80ff80;

    //bring in a symbol of right or wrong
    //TODO: better positioning/sizing on mobile
    game.global.symbol = game.add.sprite(game.world.x - 1000, this.y - this.bubbleheight - game.global.borderFrameSize, this.data.correct ? 'check' : 'x');
    game.global.symbol.height = game.global.symbol.width = game.global.borderFrameSize * 3;
    game.global.symbol.anchor.setTo(0.5,0.5);
    game.global.questionUI.add(game.global.symbol);
    game.add.tween(game.global.symbol).to({x: this.x - game.global.symbol.width}, 1000, Phaser.Easing.Default, true, 250);

    //if answered wrong, highlight the right answer
    if(!this.data.correct){
      game.global.choiceBubbles.forEach( function(item){
        if(item.data.correct){
          var arrow = game.add.sprite(game.world.x - 1000, item.y - item.bubbleheight - game.global.borderFrameSize, 'arrow');
          arrow.height = arrow.width = game.global.borderFrameSize * 3;
          arrow.anchor.setTo(0.5,0.5);
          game.global.questionUI.add(arrow);
          game.add.tween(arrow).to({x: item.x - arrow.width}, 1000, Phaser.Easing.Default, true, 250);
        }
      });
    }

    //increment number of answered questions
    game.global.questionsAnswered++;
    console.log('pressed ' + this.data.letter + ', correct?: ' + this.data.correct, '; answered ' + game.global.questionsAnswered + ' Qs');

    game.global.timer.stop();
    game.global.timer.add(3000, playState.animateOut, this);
    game.global.timer.start();
  },

  updateScores : function(answerCorrect){
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
  },

  animateOut : function(){
    game.add.tween(game.global.questionUI).to({x: game.world.x - 1000}, 500, Phaser.Easing.Default, true, 250);
    playState.updateScores(this.data.correct);
    //remove answers from screen
    game.global.answersShown = false;
    for(i = 1; i < 4; i++){
       game.global.chars[i].answer.kill();
    }

    game.global.timer.stop();
    game.global.timer.add(3000, playState.nextQuestion, playState);
    game.global.timer.start();
  },

  nextQuestion : function(){
    playState.removeQuestion();
    //show the next question if there is one
    if (game.global.questionsAnswered < game.global.numQuestions){
      this.showQuestion(game.global.questions[game.global.questionsAnswered]);
    } else {
      //if no questions left in the game, game is over
      game.state.start('endOfGame');
    }
  },

  removeQuestion : function(){
    game.global.questionUI.destroy();
    game.global.questionShown = false;
  }
};

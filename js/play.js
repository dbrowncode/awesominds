var playState = {

  /*
   *sets up number of questions/game
   *sets up the game NPC's and assigns win % to each
   *
   */
  create: function(){
    console.log('state: play');
    game.global.questions = game.global.isRehash ? game.global.rehashQuestions : game.global.shuffleArray(game.global.questions);
    console.log('rehash: ' + game.global.isRehash);
    this.ticks = game.add.group();
    game.global.numQuestions = Math.min(3, game.global.questions.length);
    game.global.questionsAnswered = 0;
    game.global.questionShown = false;
    if(!game.global.isRehash){
      game.global.totalStats = {
        numRight: 0,
        numWrong: 0,
        score: 0
      };
      for (var i = 0; i < game.global.chars.length; i++) {
        game.global.chars[i].score = 0;
      }
      game.global.answerBubbles = game.add.group();
    }

    game.global.music.stop();
    game.global.music = game.add.audio('play');
    game.global.music.loop = true;
    game.global.music.play();
    this.enterSound = game.add.audio('question');
    this.enterSound.volume = 0.2;

    //Temporary math fixers
    game.global.answersShown = false;
    game.global.numCor = 0;
    game.global.numWro = 0;
    game.global.lXOffset = 16;
    game.global.rXOffset = 16;
    game.global.winStreak = 1;
    game.global.loseStreak = 1;

    //Host
    game.global.jinny.frame = 0;
    game.global.hostComments = {
      //TODO: add other categories of comments and content; possibly load from json or db
      right : ["That's correct","Well done","Good job","Nice going","Nice!","Yes!","You betcha","Good guess","Right!","You got it!","Impressive","That's a Texas size Ten-Four good buddy"],
      wrong : [ "Oh no!"," Not quite", "Sorry", "Incorrect", "That's a miss", "Too bad", "Unfortunate", "That's not it", "Nope", "Uh-uh", "Ouch"]
    };
    game.global.jinnySpeech = game.world.add(new game.global.SpeechBubble(game, game.global.jinny.right + (game.global.borderFrameSize * 2), game.world.y + game.global.logoText.height*2, game.world.width - (game.global.jinny.width*2), 'Here comes your first question...', true, false));

    var chapterText = game.add.bitmapText(game.global.pauseButton.left, game.world.y, '8bitoperator', 'Chapter ' + game.global.selectedChapter, 11 * dpr);
    chapterText.x -= chapterText.width + game.global.borderFrameSize;
    chapterText.tint = 0x000000;

    var courseText = game.add.bitmapText(game.global.jinny.width, game.world.y, '8bitoperator', game.global.selectedCourseName, 11 * dpr);
    courseText.tint = 0x000000;

    //animate avatars to the bottom
    var image = game.global.imagecheck;
    for (var i = 0; i < game.global.chars.length; i++) {
      game.add.tween(game.global.chars[i].sprite).to({x: (((game.width/4)*(i+1) -game.width/4)+(game.width/20)), y: (game.height - image.height - game.global.chars[i].name.height*2)}, 250, Phaser.Easing.Default, true);
    }

    //show the first question
    this.showQuestion(game.global.questions[game.global.questionsAnswered]);
  },

  /*
   *updates ai and characters score on screen
   */
  update: function(){
    for (var i = 0; i < game.global.chars.length; i++) {
      game.global.chars[i].scoreText.text = game.global.chars[i].score;
      game.global.chars[i].scoreText.x = Math.floor(game.global.chars[i].sprite.right + game.global.borderFrameSize);
      game.global.chars[i].scoreText.y = Math.floor(game.global.chars[i].sprite.centerY + (11*dpr));
      game.global.chars[i].name.x = Math.floor(game.global.chars[i].sprite.centerX - game.global.chars[i].name.width/2);
      game.global.chars[i].name.y = Math.floor(game.world.height - game.global.chars[i].name.height*2);
    }
    game.global.chars[0].scoreText.text = game.global.totalStats.score;

    if(this.timerOn){
      if(this.timeElapsed >= this.totalTime){
        //TODO: call 'time is up' function, clean up question and move on with no score
        this.timeUp();
      }
    }
  },


/*
 * Clear any question that is already up
 * checks if player is on a streak and adjusts ai, maxes out at 80% mins at 25%
 * sets timer to 2 second delay before showing options
 * creates new question
 * scores AI for new question
 */
 showQuestion: function(question){
    if (game.global.questionShown){
      playState.removeQuestion();
    }
    game.global.questionUI = game.add.group();
    game.global.questionShown = false;

    //AI win %
    if(game.global.winStreak % 4 == 0){
      for(i = 1; i < 4; i++){
        if(game.global.chars[i].chance >= 80){
          game.global.chars[i].chance = 80;
        }
        else{game.global.chars[i].chance += 5;}
      }
    }else if(game.global.loseStreak % 4 == 0){
      for(i = 1; i < 4; i++){
        if(game.global.chars[i].chance <= 25){
          game.global.chars[i].chance = 25;
        }
        else{game.global.chars[i].chance -= 5;}
      }
    }
    //timer - the phaser way
    game.global.timer = game.time.create(false);
    game.global.timer.add(2000, showChoices, this);
    game.global.timer.start();

    //new question
    var prefix = game.global.isRehash ? 'REHASH ' : '';
    game.global.questionNumText = game.add.bitmapText(game.global.pauseButton.left, game.world.y, '8bitoperator', prefix + 'Q ' + (game.global.questionsAnswered + 1) + '/' + game.global.numQuestions, 11 * dpr);
    game.global.questionNumText.x -= game.global.questionNumText.width + game.global.borderFrameSize;
    game.global.questionNumText.y += game.global.questionNumText.height;
    game.global.questionNumText.tint = 0x000000;
    game.global.questionUI.add(game.global.questionNumText);

    game.global.bubble = game.world.add(new game.global.SpeechBubble(game, game.world.width + 1000, game.global.jinnySpeech.y + game.global.jinnySpeech.bubbleheight*2, game.world.width - (game.global.jinny.width*2), question.question, false, false));
    game.global.questionUI.add(game.global.bubble);

    //timer - better/more universal way?
    this.startTime = new Date();
    this.totalTime = 20;
    this.timeElapsed = 0;
    this.createTimer();
    this.gameTimer = game.time.events.loop(100, function(){ playState.updateTimer() });
    this.timerOn = true;

    //animation
    game.add.tween(game.global.bubble).to({x: Math.floor(game.world.centerX - game.global.bubble.bubblewidth/2)}, 500, Phaser.Easing.Default, true, 250);
    //playState.enterSound.volume = 0.5;
    playState.enterSound.play();
    game.global.buttons = [];

    //ai
    game.global.winThreshold = Math.floor(Math.random() * 100) + 1;
    console.log('ai wins if over ' + game.global.winThreshold);
    for(i = 1; i < 4; i++){
      game.global.chars[i].correct = (game.global.winThreshold <= game.global.chars[i].chance);
    }

    function showChoices(){
      //Create a button for each choice, and put some data into it in case we need it
      game.global.choiceBubbles = game.add.group();
      var i = 0;
      var prevHeights = 10 *dpr;
      //array to store available letter choices for ai to choose from for this question
      var availChoices = [];
      for (var c in question.choices) {
        var cb = game.world.add(new game.global.SpeechBubble(game, game.world.width + 1000, game.global.bubble.y + game.global.bubble.bubbleheight, Math.floor(game.world.width - (game.global.jinny.width*2)), question.choices[c], false, true, playState.btnClick, true, c));
        //cb.y += Math.floor(cb.bubbleheight + prevHeights);
        cb.y += Math.floor(prevHeights);
        prevHeights += cb.bubbleheight + 10 *dpr;
        game.add.tween(cb).to({x: Math.floor(game.world.centerX - cb.bubblewidth/2)}, 500, Phaser.Easing.Default, true, 250 * i);
        cb.data = {
          letter: c,
          text: c + '. ' + question.choices[c],
          correct: (c == question.answer[0]),
          fullQuestion: question
        };
        game.global.choiceBubbles.add(cb);
        availChoices[i] = c;
        i++;
      }
      game.global.questionUI.add(game.global.choiceBubbles);

      game.global.questionShown = true;
      //add timer to delay until answers are shown.
      game.global.timer.stop();
      game.global.timer.add(2000, showAnswers, this);
      game.global.timer.start();

      function showAnswers() {
        if((!game.global.answersShown) && game.global.questionShown && !game.global.isRehash){
          for(i=1;i<game.global.chars.length;i++){
            if(game.global.chars[i].correct){
              game.global.chars[i].answerBubble = game.world.add(new game.global.SpeechBubble(game, Math.floor(game.global.chars[i].sprite.right + game.global.borderFrameSize), Math.floor(game.global.chars[i].sprite.centerY - 20), Math.floor(game.global.chars[i].sprite.width), game.global.questions[game.global.questionsAnswered].answer, true, false));
            }else{
              choice = availChoices[Math.floor(Math.random() * availChoices.length)];
              answer = game.global.questions[game.global.questionsAnswered].answer;
              //strip any whitespace so comparisons will work
              answer = answer.replace(/(^\s+|\s+$)/g,"");
              choice = choice.replace(/(^\s+|\s+$)/g,"");

              //randomize answer so it isn't the correct one.
              while(choice == answer){
                console.log('first conditional worked');
                choice = availChoices[Math.floor(Math.random() * availChoices.length)];
                console.log('ai choosing answer' + choice);
              }
              game.global.chars[i].answerBubble = game.world.add(new game.global.SpeechBubble(game, Math.floor(game.global.chars[i].sprite.right + game.global.borderFrameSize), Math.floor(game.global.chars[i].sprite.centerY - 20), Math.floor(game.global.chars[i].sprite.width), choice, true, false));
            }
            game.global.answerBubbles.add(game.global.chars[i].answerBubble);
          }
          game.global.answersShown = true;
        }
      };

    };
  },

  btnClick : function(){
    //set cursor back to default; gets stuck as 'hand' otherwise
    game.canvas.style.cursor = "default";
    //disable each button
    game.global.choiceBubbles.forEach( function(item){ item.inputEnabled = false; } );
    //bring in a symbol of right or wrong
    //TODO: better positioning/sizing on mobile
    game.global.symbol = game.add.sprite(game.world.x - game.world.width, this.centerY, this.data.correct ? 'check' : 'x');
    game.global.symbol.height = game.global.symbol.width = game.global.borderFrameSize * 3;
    game.global.symbol.anchor.setTo(0.5,0.5);
    game.global.questionUI.add(game.global.symbol);
    game.add.tween(game.global.symbol).to({x: this.x, y: this.y + game.global.symbol.height/2}, 200, Phaser.Easing.Default, true, 250);
    var sounds = this.data.correct ? game.global.rightsounds : game.global.wrongsounds;
    //play sound
    sounds[0].play();

    //set host's attitude based on right or wrong answer
    var speech = this.data.correct ? 'right' : 'wrong';
    game.global.jinny.frame = this.data.correct ? 2 : 1;

    game.global.jinnySpeech.destroy();
    game.global.jinnySpeech = game.world.add(new game.global.SpeechBubble(game, game.global.jinny.right + (game.global.borderFrameSize * 2), game.world.y + game.global.logoText.height*2, game.world.width - (game.global.jinny.width*2), game.global.hostComments[speech][Math.floor(Math.random() * game.global.hostComments[speech].length)] + '\n', true, false));


    //if answered wrong, highlight the right answer
    if(!this.data.correct){
      //also add wrongly answered question to the rehash round
      game.global.rehashQuestions.push(this.data.fullQuestion);
      game.global.choiceBubbles.forEach( function(item){
        if(item.data.correct){
          var arrow = game.add.sprite(game.world.x - game.world.width, item.centerY, 'arrow');
          arrow.height = arrow.width = game.global.borderFrameSize * 3;
          arrow.anchor.setTo(0.5,0.5);
          game.global.questionUI.add(arrow);
          game.add.tween(arrow).to({x: item.x, y: item.y + arrow.height/2}, 200, Phaser.Easing.Default, true, 250);
        }
      });
    }

    //increment number of answered questions
    game.global.questionsAnswered++;
    console.log('pressed ' + this.data.letter + ', correct?: ' + this.data.correct, '; answered ' + game.global.questionsAnswered + ' Qs');

    game.global.timer.stop();
    game.global.timer.add(1000, playState.animateOut, this, false);
    game.global.timer.start();
  },

  /* update player and NPC score
   * create right and wrong answer ticks
   * TODO: determine score gain/loss amount based on time/other mechanics
   */

  updateScores : function(answerCorrect, didntAnswer){
    for(i = 1 ; i < 4; i++){
      if(game.global.chars[i].correct && !game.global.isRehash){
        game.global.chars[i].score += 25;
      }
    }

    if(answerCorrect){
     if(!game.global.isRehash){
       correct = game.add.sprite((game.global.lXOffset),((game.height - 200) - (50 * game.global.numCor)) ,'right');
       correct.scale.setTo(.1,.1);
       this.ticks.add(correct);
     }
     game.global.totalStats.numRight++;

     if(game.global.numCor == 5){
       game.global.numCor = 1;
     }

     //update player score
     if(game.global.isRehash){
       game.global.totalStats.score += 5;
     }else{
       if(!game.global.answersShown){
         game.global.totalStats.score += 25;
       }else{
         game.global.totalStats.score += 10;
       }
     }

     if(game.global.totalStats.numRight !=0 && (game.global.totalStats.numRight % 5 == 0)){
       game.global.lXOffset +=6;
       game.global.numCor = -1;
     }
     game.global.numCor++;
     game.global.loseStreak = 1;
     game.global.winStreak += 1;
   } else {
      game.global.totalStats.numWrong++;
      if(!game.global.isRehash && !didntAnswer) {
        wrong = game.add.sprite((game.width - game.global.rXOffset),((game.height - 200) - (50 * game.global.numWro)) , 'wrong');
        wrong.scale.setTo(.1,.1);
        this.ticks.add(wrong);
        game.global.totalStats.score += 2;
      }
      game.global.totalStats.numWrong++;
        
      if(game.global.totalStats.numWrong !=0 && game.global.totalStats.numWrong % 5 == 0){
        game.global.rXOffset += 6;
        game.global.numWro = -1;
      }
      game.global.numWro++;
      game.global.loseStreak += 1;
      game.global.winStreak = 1;
    }
    game.global.chars[0].score = game.global.totalStats.score;
  },

  animateOut : function(didntAnswer){
    console.log('in animateOut: didntAnswer = ' + didntAnswer);
    game.add.tween(game.global.questionUI).to({x: game.world.x - game.world.width}, 500, Phaser.Easing.Default, true, 250);
    playState.updateScores(this.data.correct, didntAnswer);

    /*
     * create horizontal progress bars for each player
     * and animate them
     */
    for (var i = 0; i < game.global.chars.length; i++) {
      if(game.global.questionsAnswered <= 1){
        game.global.chars[i].gfx = game.add.graphics(0,0);
        game.global.chars[i].gfx.visible = false;
        game.global.chars[i].gfx.beginFill(0x02C487, 1);
        game.global.chars[i].gfx.drawRect(game.global.chars[i].sprite.x, game.global.chars[i].sprite.y, game.global.chars[i].sprite.width, 1);
        game.global.chars[i].barSprite = game.add.sprite(game.global.chars[i].sprite.x, game.global.chars[i].sprite.y, game.global.chars[i].gfx.generateTexture());
        game.global.chars[i].barSprite.anchor.y = 1;
      }
      game.add.tween(game.global.chars[i].barSprite).to({height: Math.max(game.global.chars[i].score, 1)}, 500, Phaser.Easing.Default, true, 250);
    }

    game.global.timer.stop();

    /*
     * remove answers from screen
     */
    removeAnswers = function(){
      game.global.answersShown = false;
      game.global.answerBubbles.destroy();
      game.global.answerBubbles = game.add.group();
    };
    game.global.timer.add(750, removeAnswers, playState);
    game.global.timer.add(1500, playState.nextQuestion, playState);
    game.global.timer.start();
  },

  /*
   * Reveal next question
   * if max number of questions reached
   * switch state to endOfGame state
   */
  nextQuestion : function(){
    playState.removeQuestion();
    //set jin's face to default state
    game.global.jinny.frame = 0;
    if (game.global.questionsAnswered < game.global.numQuestions){
      //still questions left, show the next one
      game.global.jinnySpeech.destroy();
      game.global.jinnySpeech = game.world.add(new game.global.SpeechBubble(game, game.global.jinny.right + (game.global.borderFrameSize * 2), game.world.y + game.global.logoText.height*2, game.world.width - (game.global.jinny.width*2), "Next question...",true));

      this.showQuestion(game.global.questions[game.global.questionsAnswered]);
    } else if (game.global.rehashQuestions.length > 0 && !game.global.isRehash) {
      //if out of questions and any were answered wrong, and this isn't a rehash round, go to rehash round
      game.global.isRehash = true;
      game.global.jinnySpeech.destroy();
      this.ticks.destroy();
      game.state.start('play', false, false);
    } else {
      //out of questions, and everything was right OR this was a rehash round? end the game
      game.global.jinnySpeech.destroy();
      console.log("ticks " + game.global.ticks);
      endGame = game.add.audio('endGame');
      endGame.play();
      game.state.start('endOfGame', false, false);
    }
  },

  removeQuestion : function(){
    game.global.questionUI.destroy();
    game.global.questionShown = false;
  },

  createTimer : function(){
    this.timeLabel = game.add.bitmapText(game.global.bubble.centerX, game.global.bubble.y + game.global.bubble.bubbleheight, '8bitoperator', '00:00', 11 * dpr);
    this.timeLabel.tint = 0x000000;
    game.global.questionUI.add(this.timeLabel);
  },

  updateTimer : function(){
    if(this.timerOn){
      var currentTime = new Date();
      var timeDiff = this.startTime.getTime() - currentTime.getTime();
      //time elapsed in seconds
      this.timeElapsed = Math.abs(timeDiff / 1000);
      this.timeRemaining = this.totalTime - this.timeElapsed;
      this.minutes = Math.floor(this.timeRemaining/60);
      this.seconds = Math.floor(this.timeRemaining) - (60 * this.minutes);
      // display minutes, add 0 if under 10
      var result = (this.minutes < 10) ? "0" + this.minutes : this.minutes;
      // add seconds
      result += (this.seconds < 10) ? ":0" + this.seconds : ":" + this.seconds;
      // update text; use 'result' if you want minutes:seconds
      this.timeLabel.text = this.seconds;
      this.timeLabel.x = Math.floor(game.global.bubble.centerX - this.timeLabel.width/2);
      this.timeLabel.y = Math.floor(game.global.bubble.centerY + game.global.bubble.bubbleheight);
    }
  },

  timeUp : function(){
    game.global.jinnySpeech.destroy();
    game.global.jinnySpeech = game.world.add(new game.global.SpeechBubble(game, game.global.jinny.right + (game.global.borderFrameSize * 2), game.world.y + game.global.logoText.height*2, game.world.width - (game.global.jinny.width*2), "Time's up!", true, false));
    game.global.questionsAnswered++;
    var dummy = {data: {correct: false}};
    this.timerOn = false;
    this.timeLabel.destroy();
    this.animateOut.call(dummy, true);
  }
};

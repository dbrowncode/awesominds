var playState = {

  /*
   *sets up number of questions/game
   *sets up the game NPC's and assigns win % to each
   *
   */
  create: function(){
    console.log('state: play');
    if(game.global.isRehash){ //if in rehash round, use the array of questions that were answered incorrectly in the previous round
      game.global.questions = game.global.rehashQuestions;
    } else {
      if(game.global.roundNum == 1){
        //new game, first round
        console.log('new game');
        game.global.questionsBackup = game.global.origQuestions.slice();
        game.global.questions = game.global.shuffleArray(game.global.origQuestions);
      } else {
        //returning on round 2 or higher
        if(game.global.questions.length <= 0){
          game.global.questions = game.global.shuffleArray(game.global.questionsBackup.slice());
        }
      }
    }
    console.log('rehash: ' + game.global.isRehash);
    this.ticks = game.add.group();
    game.global.numQuestions = Math.min( (devmode ? devvars.numQ : 10), game.global.questions.length);
    game.global.questionsAnswered = 0;
    game.global.questionShown = false;
    game.global.answeredBeforeAI = false;
    if(!game.global.isRehash){
      game.global.numOrigQuestions = game.global.numQuestions;
      game.global.totalStats = {
        numRight: 0,
        numWrong: 0,
        score: 0
      };
      for (var i = 0; i < game.global.chars.length; i++) {
        game.global.chars[i].score = 0;
        game.global.chars[i].scoreText.text = 0;
      }
      if(game.global.bonus > 0){
        game.global.totalStats.score = game.global.bonus;
        game.global.chars[0].score = game.global.totalStats.score;
        game.global.bonus = 0;
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
      right : ["That's correct","Well done","Good job","Nice going","Nice!","Yes!","You betcha","Good guess","Right!","You got it!","Impressive"],
      wrong : [ "Oh no"," Not quite", "Sorry", "Incorrect", "That's a miss", "Too bad", "Unfortunate", "That's not it", "Nope", "Uh-uh", "Ouch"]
    };
    game.global.jinnySpeech = game.world.add(new game.global.SpeechBubble(game, game.global.jinny.right + (game.global.borderFrameSize * 2), game.global.chapterText.bottom, game.world.width - (game.global.jinny.width*2), game.global.isRehash ? "Welcome to the rehash round!\nThis is a second chance to earn some points on the questions you answered incorrectly.\nCorrect answers are worth 5 points, and your opponents are sitting out this round." : 'Here comes your first question...', true, false, null, false, null, true));

    //animate avatars to the bottom; needed in case this state was skipped to before animation finished in pregame
    var image = game.global.imagecheck;
    for (var i = 0; i < game.global.chars.length; i++) {
      game.add.tween(game.global.chars[i].sprite).to({x: Math.floor(((game.width/game.global.chars.length)*(i+1) -game.width/game.global.chars.length)+(game.width/25))}, 250, Phaser.Easing.Default, true);
    }

    //show the rehash splash or the first question
    if(game.global.isRehash){
      function playBtnClick(){
        game.global.jinnySpeech.destroy();
        this.destroy();
        game.global.jinnySpeech = game.world.add(new game.global.SpeechBubble(game, game.global.jinny.right + (game.global.borderFrameSize * 2), game.global.chapterText.bottom, game.world.width - (game.global.jinny.width*2), 'Here comes your first question...', true, false, null, false, null, true));
        game.state.getCurrentState().showQuestion(game.global.questions.shift());
      }
      var playBtn = game.world.add(new game.global.SpeechBubble(game, game.world.centerX, game.height, game.width, "Play", false, true, playBtnClick));
      playBtn.x = Math.floor(playBtn.x - (playBtn.bubblewidth/2));
      playBtn.y = Math.floor(game.global.jinnySpeech.y + game.global.jinnySpeech.bubbleheight + (10*dpr));
    } else {
      this.showQuestion(game.global.questions.shift());
    }
  },

  /*
   *updates ai and characters score on screen
   */
  update: function(){
    for (var i = 0; i < game.global.chars.length; i++) {
      var n = parseInt(game.global.chars[i].scoreText.text);
      if (n < game.global.chars[i].score){
        n++;
        game.global.chars[i].scoreText.text = n;
      }
      game.global.chars[i].scoreText.x = Math.floor(game.global.chars[i].sprite.right + game.global.borderFrameSize);
      game.global.chars[i].scoreText.y = Math.floor(game.global.chars[i].sprite.centerY + (11*dpr));
      game.global.chars[i].name.x = Math.floor(game.global.chars[i].sprite.centerX - game.global.chars[i].name.width/2);
      game.global.chars[i].name.y = Math.floor(game.global.chars[i].sprite.bottom);
      game.global.chars[i].crown.centerX = Math.floor(game.global.chars[i].sprite.centerX);
    }

    if(this.timerOn){
      if(Math.floor(this.timeElapsed) >= 10 && !game.global.answersShown){
        //show ai answers after 10 seconds and make bar yellow
        this.showAnswers(false);
        this.gfx = game.add.graphics(game.world.x - 1000, game.world.y - 1000);
        this.gfx.lineStyle(1, 0x000000, 1);
        this.gfx.beginFill(0xebf442, 1);
        this.gfx.drawRoundedRect(this.gfx.x, this.gfx.y, game.global.bubble.bubblewidth, 8*dpr, 5);
        this.timerBar.loadTexture(this.gfx.generateTexture());
      }

      if(Math.floor(this.timeRemaining) <= 3 && game.global.answersShown){ //when there's little time left, make the bar red
        this.gfx = game.add.graphics(game.world.x - 1000, game.world.y - 1000);
        this.gfx.lineStyle(1, 0x000000, 1);
        this.gfx.beginFill(0xf70e0e, 1);
        this.gfx.drawRoundedRect(this.gfx.x, this.gfx.y, game.global.bubble.bubblewidth, 8*dpr, 5);
        this.timerBar.loadTexture(this.gfx.generateTexture());
      }

      if(this.timeElapsed >= this.totalTime){
        //call 'time is up' function, clean up question and move on with no score
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
    if(devmode) console.log('questions left: ' + game.global.questions.length );
    if (game.global.questionShown){
      game.state.getCurrentState().removeQuestion();
    }
    game.global.questionUI = game.add.group();

    game.global.questionShown = false;
    game.global.answeredBeforeAI = false;
    game.global.answersShown = false;

    //AI win %
    if(game.global.winStreak % 4 == 0){
      for(i = 1; i < game.global.chars.length; i++){
        if(game.global.chars[i].chance >= 80){
          game.global.chars[i].chance = 80;
        }
        else{game.global.chars[i].chance += 5;}
      }
    }else if(game.global.loseStreak % 4 == 0){
      for(i = 1; i < game.global.chars.length; i++){
        if(game.global.chars[i].chance <= 25){
          game.global.chars[i].chance = 25;
        }
        else{game.global.chars[i].chance -= 5;}
      }
    }

    //new question
    var prefix = game.global.isRehash ? 'REHASH ' : '';
    var questionNumText = game.add.text(game.world.width, Math.floor(game.global.logoText.bottom + 5), prefix + 'Q ' + (game.global.questionsAnswered + 1) + '/' + game.global.numQuestions, game.global.smallerWhiteFont);
    questionNumText.x = Math.round(questionNumText.x - questionNumText.width - game.global.borderFrameSize);
    questionNumText.setShadow(2, 2, 'rgba(0,0,0,0.5)', 5);
    questionNumText.padding.x = 5;
    game.global.questionUI.add(questionNumText);

    var bwidth = Math.min(Math.floor(game.world.width - (game.global.jinny.width/2)), game.global.jinny.width * 7);
    game.global.bubble = game.world.add(new game.global.SpeechBubble(game, game.world.width + 1000, game.global.jinnySpeech.y + (game.global.jinnySpeech.bubbleheight*2), bwidth, question.question, false, true, game.state.getCurrentState().showChoices));
    game.global.bubble.question = question;
    game.global.questionUI.add(game.global.bubble);

    //animation
    game.add.tween(game.global.bubble).to({x: Math.floor(game.world.centerX - game.global.bubble.bubblewidth/2)}, 500, Phaser.Easing.Default, true, 250);
    this.enterSound.play();

    //ai
    game.global.winThreshold = Math.floor(Math.random() * 100) + 1;
    console.log('ai wins if over ' + game.global.winThreshold);
    for(i = 1; i < game.global.chars.length; i++){
      game.global.chars[i].correct = (game.global.winThreshold <= game.global.chars[i].chance);
    }

    game.global.promptShown = false;
    //timer - the phaser way
    game.global.timer = game.time.create(false);
    game.global.timer.add(1500, game.state.getCurrentState().showClickPrompt, this);
    game.global.timer.start();
  },

  showClickPrompt : function(){
    if(!game.global.questionShown){
      game.global.promptText = game.add.text(game.global.bubble.x, Math.floor(game.global.bubble.y + game.global.bubble.bubbleheight), '^ Click/Tap Question To Show Options ^', game.global.smallerWhiteFont);
      game.global.promptText.x = Math.floor(game.world.centerX - game.global.promptText.width/2);
      game.global.promptText.setShadow(2, 2, 'rgba(0,0,0,0.5)', 5);
      game.global.promptText.padding.x = 5;
    }
    game.global.promptShown = true;
  },

  showChoices : function(){
    this.inputEnabled = false;
    if(game.global.promptShown){
      game.global.promptText.destroy();
      game.global.promptShown = false;
    }

    function setupTimer(){
      var thisState = game.state.getCurrentState();
      thisState.startTime = new Date();
      thisState.totalTime = game.global.selectedMode.totalTime + 1;
      thisState.timeElapsed = 0;
      thisState.createTimer();
      thisState.gameTimer = game.time.events.loop(100, function(){ game.state.getCurrentState().updateTimer() });
      thisState.timerOn = true;
    }

    //Create a button for each choice, and put some data into it in case we need it
    game.global.choiceBubbles = game.add.group();
    var i = 0;
    var prevHeights = 10 *dpr;
    //array to store available letter choices for ai to choose from for this question
    var availChoices = [];
    var tweens = [];
    var question = this.question;
    var shuffChoices = [];
    var answerText = '';
    for (var c in question.choices) {
      availChoices[i] = c;
      shuffChoices[i] = question.choices[c];
      if(c == question.answer[0]) answerText = question.choices[c];
      i++;
    }
    shuffChoices = game.global.shuffleArray(shuffChoices);
    console.log(shuffChoices);
    i = 0;
    for (var c in question.choices) { //create buttons for each choice from the question
      var cbwidth = Math.min(Math.floor(game.world.width - (game.global.jinny.width)), game.global.jinny.width * 5);
      var cb = game.world.add(new game.global.SpeechBubble(game, game.world.width + 1000, game.global.bubble.y + game.global.bubble.bubbleheight, cbwidth, shuffChoices[i], false, true, game.state.getCurrentState().btnClick, true, c));
      cb.y += Math.floor(prevHeights);
      prevHeights += cb.bubbleheight + 10 *dpr;
      tweens[i] = game.add.tween(cb).to({x: Math.floor(game.world.centerX - cb.bubblewidth/2)}, 500, Phaser.Easing.Default, true, 250 * i);
      if(shuffChoices[i] == answerText) question.newAnswer = c;
      cb.data = {
        letter: c,
        text: c + '. ' + shuffChoices[i],
        correct: (shuffChoices[i] == answerText),
        fullQuestion: question
      };
      game.global.choiceBubbles.add(cb);
      availChoices[i] = c;
      i++;
    }
    tweens[tweens.length-1].onComplete.add(setupTimer, this);

    game.global.questionUI.add(game.global.choiceBubbles);
    game.global.questionShown = true;
    if(devmode) console.log('answer' + question.newAnswer);

    //determine AI answers
    for(i=1; i<game.global.chars.length; i++){
      if(game.global.chars[i].correct){
        game.global.chars[i].answerBubble = game.world.add(new game.global.SpeechBubble(game, Math.floor(game.global.chars[i].sprite.right + game.global.borderFrameSize), Math.floor(game.global.chars[i].sprite.centerY - 20), game.world.width, question.newAnswer, true, false));
      }else{
        choice = availChoices[Math.floor(Math.random() * availChoices.length)];
        answer = question.newAnswer;
        //strip any whitespace so comparisons will work
        answer = answer.replace(/(^\s+|\s+$)/g,"");
        choice = choice.replace(/(^\s+|\s+$)/g,"");

        //randomize answer so it isn't the correct one.
        while(choice == answer){
          choice = availChoices[Math.floor(Math.random() * availChoices.length)];
        }
        game.global.chars[i].answerBubble = game.world.add(new game.global.SpeechBubble(game, Math.floor(game.global.chars[i].sprite.right + game.global.borderFrameSize), Math.floor(game.global.chars[i].sprite.centerY - 20), game.world.width, choice, true, false));
      }
      //save width so we can set to 0 and tween to it later
      game.global.answerBubbleWidth = game.global.chars[i].answerBubble.width;
      game.global.chars[i].answerBubble.width = 0;
      game.global.answerBubbles.add(game.global.chars[i].answerBubble);
    }
  },

  showAnswers : function(fromButton) { //display ai's selected answers
    if((!game.global.answersShown) && game.global.questionShown && !game.global.isRehash){
      if(!fromButton){
        //only replace the speech if this function was not called from btn click and came from the timer
        game.global.jinnySpeech.destroy();
        game.global.jinnySpeech = game.world.add(new game.global.SpeechBubble(game, game.global.jinny.right + (game.global.borderFrameSize * 2), game.global.chapterText.bottom, game.world.width - (game.global.jinny.width*2), "Not much time left!", true, false, null, false, null, true));
      }
      for(i=1;i<game.global.chars.length;i++){
        game.add.tween(game.global.chars[i].answerBubble).to({width: game.global.answerBubbleWidth }, 100, Phaser.Easing.Default, true, 250 * i);
      }
      game.global.answersShown = true;
    }
  },

  btnClick : function(){
    //set cursor back to default; gets stuck as 'hand' otherwise
    game.canvas.style.cursor = "default";
    //disable each button
    game.global.choiceBubbles.forEach( function(item){ item.inputEnabled = false; } );
    //disable timer
    game.state.getCurrentState().timerOn = false;
    game.global.pointsToAdd = (typeof game.state.getCurrentState().seconds === 'undefined') ? 25 : game.state.getCurrentState().seconds; //capture time remaining to use as score; if time hasn't been set yet because the user is that fast, give them full points
    //increment number of answered questions
    game.global.questionsAnswered++;

    function btnClickShowAnswers(){
      //show AI answers if not already shown
      if(!game.global.answersShown){
        game.state.getCurrentState().showAnswers(true);
        game.global.answeredBeforeAI = true;
      }else{
        game.global.answeredBeforeAI = false;
      }
    };

    function btnClickSymbolFeedback(){
      //bring in a symbol of right or wrong
      game.global.symbol = game.add.sprite(game.world.x - game.world.width, this.centerY, this.data.correct ? 'check' : 'x');
      game.global.symbol.height = game.global.symbol.width = game.global.borderFrameSize * 3;
      game.global.symbol.anchor.setTo(0.5,0.5);
      game.global.questionUI.add(game.global.symbol);
      game.add.tween(game.global.symbol).to({x: Math.floor(this.x - game.global.symbol.width/3), y: Math.floor(this.y + this.bubbleheight/2)}, 300, Phaser.Easing.Default, true, 0);
      var sounds = this.data.correct ? game.global.rightsounds : game.global.wrongsounds;
      //play sound
      sounds[0].play();

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
            game.add.tween(arrow).to({x: Math.floor(item.x - arrow.width/3), y: Math.floor(item.y + item.bubbleheight/2)}, 300, Phaser.Easing.Default, true, 0);
          }
        });
      }
    };

    function btnClickHostFeedback(){
      //set host's attitude based on right or wrong answer
      var speech = this.data.correct ? 'right' : 'wrong';
      game.global.jinny.frame = this.data.correct ? 2 : 1;

      game.global.jinnySpeech.destroy();
      game.global.jinnySpeech = game.world.add(new game.global.SpeechBubble(game, game.global.jinny.right + (game.global.borderFrameSize * 2), game.global.chapterText.bottom, game.world.width - (game.global.jinny.width*2), game.global.hostComments[speech][Math.floor(Math.random() * game.global.hostComments[speech].length)], true, false, null, false, null, true));

      //points graphic
      if(!game.global.isRehash && this.data.correct){
        var ptsImage = game.add.text(game.world.centerX, game.world.height, game.global.pointsToAdd + ' pts!');
        ptsImage.font = 'Arial';
        ptsImage.fontWeight = 'bold';
        ptsImage.fill = '#ffffff';
        ptsImage.stroke = '#000000';
        ptsImage.strokeThickness = Math.max(game.global.pointsToAdd / 2, 10) * dpr;
        ptsImage.fontSize = Math.max(game.global.pointsToAdd * 4, 40) * dpr;
        var tweenA = game.add.tween(ptsImage).to({x: Math.floor(game.world.centerX - ptsImage.width/2), y: Math.floor(game.world.centerY - ptsImage.height/2)}, 300, Phaser.Easing.Default, false, 0);
        var tweenB = game.add.tween(ptsImage).to({alpha: 0}, 300, Phaser.Easing.Default, false, 300);
        tweenA.chain(tweenB);
        tweenA.start();
        game.global.questionUI.add(ptsImage);
      }
    };

    game.global.timer.stop();
    game.global.timer.add(500, btnClickShowAnswers, this);
    game.global.timer.add(1500, btnClickSymbolFeedback, this);
    game.global.timer.add(2000, btnClickHostFeedback, this);
    game.global.timer.add(4500, game.state.getCurrentState().animateOut, this, false);
    game.global.timer.start();
  },

  /* update player and NPC score
   * create right and wrong answer ticks
   */
  updateScores : function(answerCorrect, didntAnswer){
    for(i = 1 ; i < game.global.chars.length; i++){
      if(game.global.chars[i].correct && !game.global.isRehash){
        game.global.chars[i].score += game.global.selectedMode.maxPtsPerQ;
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
      game.global.totalStats.score += game.global.pointsToAdd;
      game.global.pointsToAdd = 0;
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
    game.state.getCurrentState().timerOn = false;
    game.add.tween(game.global.questionUI).to({x: game.world.x - game.world.width}, 300, Phaser.Easing.Default, true, 0);

    makeBars = function(correct, didntAnswer){
      /*
       * create horizontal progress bars for each player
       * and animate them
       */
      game.state.getCurrentState().updateScores(correct, didntAnswer);
      for (var i = 0; i < game.global.chars.length; i++) {
        if(game.global.questionsAnswered <= 1 && !game.global.isRehash){
          game.global.chars[i].gfx = game.add.graphics(0,0);
          game.global.chars[i].gfx.visible = false;
          game.global.chars[i].gfx.beginFill(0x02C487, 1);
          game.global.chars[i].gfx.drawRect(game.global.chars[i].sprite.x, (game.global.selectedMode.id == 0) ? game.global.chars[i].crown.y : game.global.chars[i].sprite.y, game.global.chars[i].sprite.width, 1);
          game.global.chars[i].barSprite = game.add.sprite(game.global.chars[i].sprite.x, (game.global.selectedMode.id == 0) ? game.global.chars[i].crown.y : game.global.chars[i].sprite.y, game.global.chars[i].gfx.generateTexture());
          game.global.chars[i].barSprite.anchor.y = 1;
        }
        game.add.tween(game.global.chars[i].barSprite).to({height: Math.max(game.global.chars[i].score, 1)}, 1000, Phaser.Easing.Default, true, 0);
      }
    }
    // makeBars();


    /*
     * remove answers from screen
     */
    removeAnswers = function(){
      game.global.answersShown = false;
      game.global.answerBubbles.destroy();
      game.global.answerBubbles = game.add.group();
    };

    game.global.timer.stop();
    game.global.timer.add(200, removeAnswers, game.state.getCurrentState());
    game.global.timer.add(600, makeBars, game.state.getCurrentState(), this.data.correct, didntAnswer);
    game.global.timer.add(2000, game.state.getCurrentState().nextQuestion, game.state.getCurrentState());
    game.global.timer.start();
  },

  /*
   * Reveal next question
   * if max number of questions reached
   * switch state to endOfGame state
   */
  nextQuestion : function(){
    game.state.getCurrentState().removeQuestion();
    //set jin's face to default state
    game.global.jinny.frame = 0;
    if (game.global.questionsAnswered < game.global.numQuestions){
      //still questions left, show the next one
      game.global.jinnySpeech.destroy();
      game.global.jinnySpeech = game.world.add(new game.global.SpeechBubble(game, game.global.jinny.right + (game.global.borderFrameSize * 2), game.global.chapterText.bottom, game.world.width - (game.global.jinny.width*2), "Next question...", true, false, null, false, null, true));

      game.state.getCurrentState().showQuestion(game.global.questions.shift());
    } else if (game.global.rehashQuestions.length > 0 && !game.global.isRehash) {
      //if out of questions and any were answered wrong, and this isn't a rehash round, go to rehash round
      game.global.isRehash = true;
      game.global.jinnySpeech.destroy();
      game.state.getCurrentState().ticks.destroy();
      game.state.start('play', false, false);
    } else {
      //out of questions, and everything was right OR this was a rehash round? end the game
      game.global.jinnySpeech.destroy();
      game.state.getCurrentState().ticks.destroy();
      endGame = game.add.audio('endGame');
      endGame.play();
      game.state.start(game.global.selectedMode.endstate, false, false);
    }
  },

  removeQuestion : function(){
    game.global.questionUI.destroy();
    game.global.questionShown = false;
  },

  createTimer : function(){
    this.timeLabel = game.add.bitmapText(game.world.width + 1000, game.global.jinnySpeech.bottom + (11 * dpr), '8bitoperator', game.state.getCurrentState().totalTime, 11 * dpr);
    this.timeLabel.tint = 0x000000;
    this.gfx = game.add.graphics(game.world.x - 1000, game.world.y - 1000);
    this.gfx.lineStyle(1, 0x000000, 1);
    this.gfx.beginFill(0x02C487, 1);
    this.gfx.drawRoundedRect(this.gfx.x, this.gfx.y, game.global.bubble.bubblewidth, 8*dpr, 5);
    this.timerBar = game.add.sprite(this.gfx.x, this.gfx.y, this.gfx.generateTexture());
    game.global.questionUI.add(this.timeLabel);
    game.global.questionUI.add(this.timerBar);
  },

  updateTimer : function(){
    if(game.state.getCurrentState().timerOn){
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
      this.timeLabel.centerX = Math.floor(game.global.bubble.x + game.global.bubble.bubblewidth/2);
      this.timeLabel.y = Math.floor(game.global.bubble.y - (this.timeLabel.height*2.5));

      this.timerBar.width = game.global.bubble.bubblewidth - game.global.mapNum(this.timeElapsed, 0, this.totalTime, 0, game.global.bubble.bubblewidth);
      this.timerBar.centerX = Math.floor(game.global.bubble.x + game.global.bubble.bubblewidth/2);
      this.timerBar.centerY = game.global.bubble.y;
    }
  },

  timeUp : function(){
    game.global.jinnySpeech.destroy();
    game.global.jinnySpeech = game.world.add(new game.global.SpeechBubble(game, game.global.jinny.right + (game.global.borderFrameSize * 2), game.global.chapterText.bottom, game.world.width - (game.global.jinny.width*2), "Time's up!", true, false, null, false, null, true));
    game.global.questionsAnswered++;
    var dummy = {data: {correct: false}};
    this.timerOn = false;
    this.timeLabel.destroy();
    this.animateOut.call(dummy, true);
  }
};

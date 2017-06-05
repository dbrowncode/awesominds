var playState = {

  /*
   *sets up number of questions/game
   *sets up the game NPC's and assigns win % to each
   *
   */
  create: function(){
    console.log('state: play');
    game.global.questions = game.global.shuffleArray(game.global.questions);
    game.global.numQuestions = Math.min(1, game.global.questions.length);
    game.global.questionsAnswered = 0;
    game.global.totalStats = {
      numRight: 0,
      numWrong: 0,
      score: 0
    };
    game.global.answerBubbles = game.add.group();

    game.global.music.stop();
    game.global.music = game.add.audio('play');
    game.global.music.loop = true;
    game.global.music.play();
    //Host
    game.global.jinny = game.add.sprite(0,0, 'jinny');
    game.global.jinny.scale.setTo(.1,.1);
    game.global.hostComments = {
      //TODO: add other categories of comments and content; possibly load from json or db
      right : ["That's correct","Well done","Good job","Nice going","Nice!","Yes!","You betcha","Good guess","Right!","You got it!","Impressive","That's a Texas size Ten-Four good buddy"],
      wrong : [ "Oh no!"," Not quite", "Sorry", "Incorrect", "That's a miss", "Too bad", "Unfortunate", "That's not it", "Nope", "Uh-uh", "Ouch"],
      endRound : ["You have an"," awesomind!"," very good mind"," good mind", " okay mind"]
    };
    game.global.jinnySpeech = game.world.add(new game.global.SpeechBubble(game, game.global.jinny.right, game.world.y + game.global.logoText.height*2, game.world.width - (game.global.jinny.width*2), 'Welcome to Awesominds, ' + game.global.session['play_name'] + '!', true, false));
    console.log(game.global.jinnySpeech);

    var chapterText = game.add.bitmapText(game.global.pauseButton.left, game.world.y, '8bitoperator', 'Chapter ' + game.global.selectedChapter, 11 * dpr);
    chapterText.x -= chapterText.width + game.global.borderFrameSize;
    chapterText.tint = 0x000000;

    var courseText = game.add.bitmapText(game.global.jinny.width, game.world.y, '8bitoperator', game.global.selectedCourseName, 11 * dpr);
    courseText.tint = 0x000000;

    //NPC and player
    var winChances = [20, 40, 60, 75];
    winChances = game.global.shuffleArray(winChances);

    game.global.chars = [];
    game.global.oppImageKeys = game.global.shuffleArray(game.global.oppImageKeys);
    //char[0] is currently player character
    for(var i = 0; i < 4; i++){
      game.global.chars[i] = {};
      game.global.chars[i].sprite = game.add.sprite((((game.width/4)*(i+1)-game.width/4)) ,game.height - 110, (i==0) ? 'opp' + game.global.session['avatarnum'] : game.global.oppImageKeys[i]);
      game.global.chars[i].sprite.scale.setTo(1/4,1/4);
      game.global.chars[i].score = 0;
      game.global.chars[i].scoreText = game.add.bitmapText(Math.floor(game.global.chars[i].sprite.right + game.global.borderFrameSize), Math.floor(game.global.chars[i].sprite.centerY + 20), '8bitoperator', game.global.chars[i].score, 11 * dpr);
      game.global.chars[i].scoreText.tint = 0x000000;
      //game.add.text((((game.width/4)*(i+1)-game.width/4)+game.global.chars[i].sprite.width), game.global.chars[i].sprite.centerY, game.global.chars[i].score, game.global.mainFont);
     	if(i!=0){
    		game.global.chars[i].chance = winChances[i];
        game.global.chars[i].correct = false;
        console.log('win chance for ' + i + ' = ' + game.global.chars[i].chance);
      }
    }
    game.global.chars[0].name = game.add.bitmapText(game.global.chars[0].scoreText.x, game.global.chars[0].scoreText.top, '8bitoperator', 'You', 11 * dpr);
    game.global.chars[0].name.tint = 0x000000;
    game.global.chars[0].name.y -= game.global.chars[0].name.height * 2;

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
    //timer
    game.global.timer = game.time.create(false);
    game.global.timer.add(2000, showChoices, this);
    game.global.timer.start();

    //new question
    game.global.questionNumText = game.add.bitmapText(game.global.pauseButton.left, game.world.y, '8bitoperator', 'Q ' + (game.global.questionsAnswered + 1) + '/' + game.global.numQuestions, 11 * dpr);
    game.global.questionNumText.x -= game.global.questionNumText.width + game.global.borderFrameSize;
    game.global.questionNumText.y += game.global.questionNumText.height;
    game.global.questionNumText.tint = 0x000000;
    game.global.questionUI.add(game.global.questionNumText);

    game.global.bubble = game.world.add(new game.global.SpeechBubble(game, game.world.width + 1000, game.global.jinnySpeech.y + game.global.jinnySpeech.bubbleheight*2, game.world.width - (game.global.jinny.width*2), question.question, false, false));
    //game.global.bubble.y += Math.floor(game.global.bubble.bubbleheight);
    game.global.questionUI.add(game.global.bubble);

    //animation
    game.add.tween(game.global.bubble).to({x: Math.floor(game.world.centerX - game.global.bubble.bubblewidth/2)}, 500, Phaser.Easing.Default, true, 250);
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
      var prevHeights = 0;
      //array to store available letter choices for ai to choose from for this question
      var availChoices = [];
      for (var c in question.choices) {
        var cb = game.world.add(new game.global.SpeechBubble(game, game.world.width + 1000, game.global.bubble.y + game.global.bubble.bubbleheight, Math.floor(game.world.width - (game.global.jinny.width*2)), c + '. ' + question.choices[c], false, true, playState.btnClick));
        //cb.y += Math.floor(cb.bubbleheight + prevHeights);
        cb.y += Math.floor(prevHeights);
        prevHeights += cb.bubbleheight;
        game.add.tween(cb).to({x: Math.floor(game.world.centerX - cb.bubblewidth/2)}, 500, Phaser.Easing.Default, true, 250 * i);
        cb.data = {
          letter: c,
          text: c + '. ' + question.choices[c],
          correct: (c == question.answer[0]),
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
        if((!game.global.answersShown) && game.global.questionShown){
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
    game.add.tween(game.global.symbol).to({x: this.x}, 200, Phaser.Easing.Default, true, 250);
    var sounds = this.data.correct ? game.global.rightsounds : game.global.wrongsounds;
    //play sound
    sounds[0].play();

    var speech = this.data.correct ? 'right' : 'wrong';

    //TODO this kind of workds but seems nuclear, though in some ways it kind of makes sense to destroy it.
    game.global.jinnySpeech.destroy();
    game.global.jinnySpeech = game.world.add(new game.global.SpeechBubble(game, game.global.jinny.right, game.world.y + game.global.logoText.height*2, game.world.width - (game.global.jinny.width*2), game.global.hostComments[speech][Math.floor(Math.random() * game.global.hostComments[speech].length)] + '\n', true, false));


    //if answered wrong, highlight the right answer
    if(!this.data.correct){
      game.global.choiceBubbles.forEach( function(item){
        if(item.data.correct){
          var arrow = game.add.sprite(game.world.x - game.world.width, item.centerY, 'arrow');
          arrow.height = arrow.width = game.global.borderFrameSize * 3;
          arrow.anchor.setTo(0.5,0.5);
          game.global.questionUI.add(arrow);
          game.add.tween(arrow).to({x: item.x}, 200, Phaser.Easing.Default, true, 250);
        }
      });
    }

    //increment number of answered questions
    game.global.questionsAnswered++;
    console.log('pressed ' + this.data.letter + ', correct?: ' + this.data.correct, '; answered ' + game.global.questionsAnswered + ' Qs');

    game.global.timer.stop();
    game.global.timer.add(1000, playState.animateOut, this);
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
    // update player's score on their spot in the chars array for easier animation etc
    game.global.chars[0].score = game.global.totalStats.score;
  },

  animateOut : function(){
    game.add.tween(game.global.questionUI).to({x: game.world.x - game.world.width}, 500, Phaser.Easing.Default, true, 250);
    playState.updateScores(this.data.correct);

    //make progress bars and animate them
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
    removeAnswers = function(){
      //remove answers from screen
      game.global.answersShown = false;
      game.global.answerBubbles.destroy();
      game.global.answerBubbles = game.add.group();
    };
    game.global.timer.add(750, removeAnswers, playState);
    game.global.timer.add(1500, playState.nextQuestion, playState);
    game.global.timer.start();
  },

  nextQuestion : function(){
    playState.removeQuestion();
    //show the next question if there is one
    if (game.global.questionsAnswered < game.global.numQuestions){
      game.global.jinnySpeech.bitmapText.text = 'Next question...';
      this.showQuestion(game.global.questions[game.global.questionsAnswered]);
    } else {
      //if no questions left in the game, game is over
      game.state.start('endOfGame', false, false);
    }
  },

  removeQuestion : function(){
    game.global.questionUI.destroy();
    game.global.questionShown = false;
  }
};

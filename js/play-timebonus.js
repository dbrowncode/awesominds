//copy original playState and then modify it to create the state for Time Bonus mode
var playStateTB = Object.create(playState);

playStateTB.timesAnswered = 0;

playStateTB.btnClick = function(){
  //set cursor back to default; gets stuck as 'hand' otherwise
  game.canvas.style.cursor = "default";
  //disable each button
  game.global.choiceBubbles.forEach( function(item){ item.inputEnabled = false; } );
  //disable timer
  game.state.getCurrentState().timerOn = false;
  game.global.pointsToAdd = (typeof game.state.getCurrentState().seconds === 'undefined') ? 25 : game.state.getCurrentState().seconds + 15; //capture time remaining to use as score; if time hasn't been set yet because the user is that fast, give them full points
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
};

playStateTB.timeUp = function(){
  game.global.jinnySpeech.destroy();
  game.global.jinnySpeech = game.world.add(new game.global.SpeechBubble(game, game.global.jinny.right + (game.global.borderFrameSize * 2), game.global.chapterText.bottom, game.world.width - (game.global.jinny.width*2), "Time's up! No bonus this time!", true, false, null, false, null, true));
  this.timerOn = false;
  this.timeLabel.destroy();
  game.state.getCurrentState().seconds = 0; //bad fix for timer bonus bug; timer then doesn't give any bonus on next question?
};

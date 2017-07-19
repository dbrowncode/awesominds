//copy original playState and then modify it
var playStateSU = Object.create(playState);

playStateSU.timesAnswered = 0;

playStateSU.btnClick = function(){
  //set cursor back to default; gets stuck as 'hand' otherwise
  game.canvas.style.cursor = "default";
  //disable this button
  this.inputEnabled = false;

  if(this.data.correct){
    //increment number of answered questions
    game.global.questionsAnswered++;
  }

  game.state.getCurrentState().timesAnswered++;
  console.log(game.state.getCurrentState().timesAnswered);

  function btnClickShowAnswers(){
    //show AI answers if not already shown
    if(!game.global.answersShown){
      game.state.getCurrentState().showAnswers(true);
      game.global.answeredBeforeAI = true;
    }else{
      game.global.answeredBeforeAI = false;
    }
  }

  function btnClickSymbolFeedback(){
    //bring in a symbol of right or wrong
    game.global.symbol = game.add.sprite(game.world.x - game.world.width, this.centerY, this.data.correct ? 'check' : 'x');
    game.global.symbol.height = game.global.symbol.width = game.global.borderFrameSize * 3;
    game.global.symbol.anchor.setTo(0.5,0.5);
    game.global.questionUI.add(game.global.symbol);
    game.add.tween(game.global.symbol).to({x: this.x, y: this.y + game.global.symbol.height/2}, 300, Phaser.Easing.Default, true, 0);
    var sounds = this.data.correct ? game.global.rightsounds : game.global.wrongsounds;
    //play sound
    sounds[0].play();
  }

  function btnClickHostFeedback(){
    //set host's attitude based on right or wrong answer
    var speech = this.data.correct ? 'right' : 'wrong';
    game.global.jinny.frame = this.data.correct ? 2 : 1;

    game.global.jinnySpeech.destroy();
    game.global.jinnySpeech = game.world.add(new game.global.SpeechBubble(game, game.global.jinny.right + (game.global.borderFrameSize * 2), game.global.logoText.bottom, game.world.width - (game.global.jinny.width*2), game.global.hostComments[speech][Math.floor(Math.random() * game.global.hostComments[speech].length)] + '\n', true, false, null, false, null, true));

    //points graphic
    if(this.data.correct){
      //set the number of points earned here, use it to load the appropriate graphic and to update the score later
      switch (game.state.getCurrentState().timesAnswered) {
        case 1:
          game.global.pointsToAdd = 25;
          break;
        case 2:
          game.global.pointsToAdd = 15;
          break;
        default:
          game.global.pointsToAdd = 5;
          break;
      }
      var ptsImage = game.add.sprite(game.world.centerX, game.world.height, game.global.pointsToAdd + 'pts');
      // ptsImage.scale.setTo(dpr);
      var tweenA = game.add.tween(ptsImage).to({x: Math.floor(game.world.centerX - ptsImage.width/2), y: Math.floor(game.world.centerY - ptsImage.height/2)}, 300, Phaser.Easing.Default, false, 0);
      var tweenB = game.add.tween(ptsImage).to({alpha: 0}, 300, Phaser.Easing.Default, false, 300);
      tweenA.chain(tweenB);
      tweenA.start();
      game.global.questionUI.add(ptsImage);
    }
  }

  game.global.timer.stop();
  game.global.timer.add(100, btnClickShowAnswers, this);
  game.global.timer.add(100, btnClickSymbolFeedback, this);
  game.global.timer.add(500, btnClickHostFeedback, this);
  if(this.data.correct){
    game.global.choiceBubbles.forEach( function(item){ item.inputEnabled = false; } );
    game.global.timer.add(2500, game.state.getCurrentState().animateOut, this, false);
  }
  game.global.timer.start();
};

playStateSU.updateScores = function(answerCorrect, didntAnswer){
  for(i = 1 ; i < 4; i++){
    if(game.global.chars[i].correct){
      game.global.chars[i].score += 25;
    }
  }

  game.global.totalStats.numRight++;

  //ticks -- possibly remove them in this mode? because always correct eventually...
  correct = game.add.sprite((game.global.lXOffset),((game.height - 200) - (50 * game.global.numCor)) ,'right');
  correct.scale.setTo(.1,.1);
  this.ticks.add(correct);
  if(game.global.numCor == 5){
    game.global.numCor = 1;
  }
  if(game.global.totalStats.numRight !=0 && (game.global.totalStats.numRight % 5 == 0)){
    game.global.lXOffset +=6;
    game.global.numCor = -1;
  }
  game.global.numCor++;

  //update player score
  game.global.totalStats.score += game.global.pointsToAdd;

  game.state.getCurrentState().timesAnswered = 0;
  game.global.chars[0].score = game.global.totalStats.score;
}

playStateSU.createTimer = function(){}; //emptied to remove timer visuals

playStateSU.updateTimer = function(){}; //emptied; not using this timer in this mode

playStateSU.update = function(){
  for (var i = 0; i < game.global.chars.length; i++) {
    var n = parseInt(game.global.chars[i].scoreText.text);
    if (n < game.global.chars[i].score){
      n++;
      game.global.chars[i].scoreText.text = n;
    }
    game.global.chars[i].scoreText.x = Math.floor(game.global.chars[i].sprite.right + game.global.borderFrameSize);
    game.global.chars[i].scoreText.y = Math.floor(game.global.chars[i].sprite.centerY + (11*dpr));
    game.global.chars[i].name.x = Math.floor(game.global.chars[i].sprite.centerX - game.global.chars[i].name.width/2);
    game.global.chars[i].name.y = Math.floor(game.world.height - game.global.chars[i].name.height*2);
  }
};

playStateSU.showAnswers = function(fromButton) {
  if((!game.global.answersShown) && game.global.questionShown){
    for(i=1;i<game.global.chars.length;i++){
      game.add.tween(game.global.chars[i].answerBubble).to({width: game.global.answerBubbleWidth }, 100, Phaser.Easing.Default, true, 250 * i);
    }
    game.global.answersShown = true;
  }
}

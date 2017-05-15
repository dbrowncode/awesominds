var playState = {
  create: function(){
    game.global.background = game.add.sprite(0, 0, 'sky');
    game.global.roundScoreText = game.add.text(16, game.height - 24, 'Round Score: 0', game.global.mainFont);
    game.global.questionsAnswered = 0;
    game.global.showQuestion(game.global.questions[game.global.questionsAnswered]);
  },
  update: function(){
    //update score text
    game.global.roundScoreText.text = 'Round Score: ' + game.global.roundStats[game.global.currentRound].score;
    //update answer text position to keep each one moving with its button
    for (var i = 0; i < game.global.buttons.length; i++) {
      game.global.buttons[i].data.text.x = Math.floor(game.global.buttons[i].x + game.global.buttons[i].width / 2);
      game.global.buttons[i].data.text.y = Math.floor(game.global.buttons[i].y + game.global.buttons[i].height / 2);
    }
  },
  resize: function(width, height){
    //TODO: redraw things on window resize; think about resizing text and elements
    // the approach below kindof works, but leaving it commented out for now while debugging other things

    // game.global.removeQuestion();
    // if(game.global.questionsAnswered < game.global.qPerRound){
    //   game.global.showQuestion(game.global.questions[game.global.questionsAnswered]);
    // }
  }
};

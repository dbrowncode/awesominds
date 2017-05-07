var playState = {
  create: function(){
    game.global.background = game.add.sprite(0, 0, 'sky');
    game.global.roundScoreText = game.add.text(16, game.height - 24, 'Round Score: 0', game.global.mainFont);
    game.global.questionsAnswered = 0;
    game.global.showQuestion(game.global.questions[game.global.questionsAnswered]);
  },
  update: function(){
    game.global.roundScoreText.text = 'Round Score: ' + game.global.roundStats[game.global.currentRound].score;
  }
};

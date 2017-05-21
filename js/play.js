var playState = {
  create: function(){
    game.global.jinny = game.add.sprite(0,0, 'jinny');
    game.global.jinny.scale.setTo(.1,.1);
    game.global.roundText = game.add.text(0, 0, 'Round ' + (game.global.currentRound + 1), game.global.rightSideFont);
    game.global.roundText.setTextBounds(0, 0, game.width-10, game.height-10);
    //set up game characters
    if(game.global.currentRound == 0){
      game.global.chars = [];
    }
    var sprites = ['beaver','rabbit','cat','beaver']
    for(var i = 0; i < 4; i++){
      if(game.global.currentRound == 0){
      	game.global.chars[i] = {};
      }
    	game.global.chars[i].sprite = game.add.sprite((((game.width/4)*(i+1)-game.width/4)) ,game.height - 110,sprites[i]);
    	game.global.chars[i].sprite.scale.setTo(.3,.3);
      game.global.chars[i].score = 0;
      game.global.chars[i].scoreText = game.add.text((((game.width/4)*(i+1)-game.width/4)+game.global.chars[i].sprite.width), game.global.chars[i].sprite.centerY, game.global.chars[i].score, game.global.mainFont);
     	if(i!=0){
    		//placeholder text to make kill not break game.
    		game.global.chars[i].answer = game.add.text(0,0,'');
    		//need to set this percent during preload states probably.
    		game.global.chars[i].chance = 20 * i;
    	}
    }

    game.global.showQuestion(game.global.questions[game.global.questionsAnswered]);
  },
  update: function(){
    //update score text
    for (var i = 1; i < game.global.chars.length; i++) {
      game.global.chars[i].scoreText.text = game.global.chars[i].score;
    }
    game.global.chars[0].scoreText.text = game.global.roundStats[game.global.currentRound].score;
    //update answer text position to keep each one moving with its button
    for (var i = 0; i < game.global.buttons.length; i++) {
      game.global.buttons[i].data.text.x = Math.floor(game.global.buttons[i].centerX);
      game.global.buttons[i].data.text.y = Math.floor(game.global.buttons[i].centerY);
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

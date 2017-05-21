var playState = {
  create: function(){
    //game.global.background = game.add.sprite(0, 0, 'sky');

    game.global.jinny = game.add.sprite(0,0, 'jinny');
    game.global.jinny.scale.setTo(.1,.1);
//set up game characters
    game.global.chars = [];
    var sprites = ['beaver','rabbit','cat','beaver']
    for(var i = 0; i < 4; i++){
    	game.global.chars[i] = {};
    	game.global.chars[i].sprite = game.add.sprite((((game.width/4)*(i+1)-game.width/4)) ,game.height - 110,sprites[i]);
    	game.global.chars[i].sprite.scale.setTo(.3,.3);
    	game.global.chars[i].score = game.add.text((((game.width/4)*(i+1)-game.width/4)+game.global.chars[i].sprite.width),game.height - 50, '0', game.global.mainFont);
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
    game.global.chars[0].score.text = game.global.roundStats[game.global.currentRound].score;
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

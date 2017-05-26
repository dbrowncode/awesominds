var playState = {
  create: function(){
    console.log('state: play');
    game.global.questions = game.global.shuffleArray(game.global.questions);
    // set the number of questions per game
    game.global.numQuestions = 2;
    game.global.questionsAnswered = 0;
    game.global.totalStats = {
      numRight: 0,
      numWrong: 0,
      score: 0
    };

    game.global.jinny = game.add.sprite(0,0, 'jinny');
    game.global.jinny.scale.setTo(.1,.1);
    game.global.questionNumText = game.add.text(0, 5, 'Question ' + (game.global.questionsAnswered + 1) + ' of ' + game.global.numQuestions, game.global.rightSideFont);
    game.global.questionNumText.setTextBounds(0, 5, game.width-10, game.height-10);
    //set up game characters

    //create array of chances
    //shuffle for replayability.
    //assign win % to NPC
    var winChances = [20, 40, 60, 75];
    winChances = game.global.shuffleArray(winChances);

    game.global.chars = [];
    var sprites = ['beaver','rabbit','cat','beaver'];
    var characterPercent = 0;
    for(var i = 0; i < 4; i++){
    	game.global.chars[i] = {};
      game.global.chars[i].sprite = game.add.sprite((((game.width/4)*(i+1)-game.width/4)) ,game.height - 110,sprites[i]);
      game.global.chars[i].sprite.scale.setTo(.3,.3);
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

    game.global.showQuestion(game.global.questions[game.global.questionsAnswered]);
  },
  update: function(){
    //update score text
    for (var i = 1; i < game.global.chars.length; i++) {
      game.global.chars[i].scoreText.text = game.global.chars[i].score;
    }
    game.global.chars[0].scoreText.text = game.global.totalStats.score;
    //update answer text position to keep each one moving with its button
    for (var i = 0; i < game.global.buttons.length; i++) {
      game.global.buttons[i].data.text.x = Math.floor(game.global.buttons[i].centerX);
      game.global.buttons[i].data.text.y = Math.floor(game.global.buttons[i].centerY);
    }
    game.global.questionNumText.text = 'Question ' + (game.global.questionsAnswered + 1) + ' of ' + game.global.numQuestions;

  },
  resize: function(width, height){
    //TODO: redraw things on window resize; think about resizing text and elements
  }
};

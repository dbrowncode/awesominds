var endOfGameState = {
  create: function(){
    console.log('state: endofgame');
    game.global.jinny = game.add.sprite(0,0, 'jinny');
    game.global.jinny.scale.setTo(.1,.1);

    for(var i = 0; i < 4; i++){
    	game.global.chars[i].sprite = game.add.sprite((((game.width/4)*(i+1)-game.width/4)) ,game.height - 110, game.global.oppImageKeys[i]);
    	game.global.chars[i].sprite.scale.setTo(dpr/4,dpr/4);
      game.global.chars[i].scoreText = game.add.text((((game.width/4)*(i+1)-game.width/4)+game.global.chars[i].sprite.width),game.height - 50, game.global.chars[i].score, game.global.mainFont);
      game.add.tween(game.global.chars[i].sprite).to({x: game.world.x + game.width/4, y: ((game.height/4)*(i+1))-game.height/4}, 250, Phaser.Easing.Default, true, 250);
    }
    game.global.chars[0].scoreText.text = game.global.totalStats.score;

    game.global.playAgainClick = function(){
      game.state.start('play');
    };

    game.global.chooseCourseClick = function(){
      game.state.start('menuCourse');
    };

    game.global.playAgainBtn = game.add.button(game.world.width + 1000, game.world.height - 180, 'button', game.global.playAgainClick, game.global.playAgainBtn, 2, 1, 0);
    game.global.playAgainBtn.data.text = game.add.text(0, 0, "Play Again", game.global.optionFont);
    game.global.playAgainBtn.data.text.anchor.set(0.5);

    game.global.chooseCourseBtn = game.add.button(game.world.width + 1000, game.world.height - 80, 'button', game.global.chooseCourseClick, game.global.chooseCourseBtn, 2, 1, 0);
    game.global.chooseCourseBtn.data.text = game.add.text(0, 0, "Choose Course", game.global.optionFont);
    game.global.chooseCourseBtn.data.text.anchor.set(0.5);

    //animate buttons coming in
    game.add.tween(game.global.playAgainBtn).to({x: game.world.width - (game.global.playAgainBtn.width + 80)}, 500, Phaser.Easing.Default, true, 250);
    game.add.tween(game.global.chooseCourseBtn).to({x: game.world.width - (game.global.chooseCourseBtn.width + 80)}, 500, Phaser.Easing.Default, true, 250);

  },
  update: function(){
    for(var i = 0; i < 4; i++){
      game.global.chars[i].scoreText.x = game.global.chars[i].sprite.x + (game.global.chars[i].sprite.width);
      game.global.chars[i].scoreText.y = game.global.chars[i].sprite.centerY;
    }
    game.global.playAgainBtn.data.text.x = Math.floor(game.global.playAgainBtn.centerX);
    game.global.playAgainBtn.data.text.y = Math.floor(game.global.playAgainBtn.centerY);
    game.global.chooseCourseBtn.data.text.x = Math.floor(game.global.chooseCourseBtn.centerX);
    game.global.chooseCourseBtn.data.text.y = Math.floor(game.global.chooseCourseBtn.centerY);
  }
};

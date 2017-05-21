var endOfRoundState = {
  create: function(){
    console.log('state: endofround');
    game.global.jinny = game.add.sprite(0,0, 'jinny');
    game.global.jinny.scale.setTo(.1,.1);

    var sprites = ['beaver','rabbit','cat','beaver']
    for(var i = 0; i < 4; i++){
    	game.global.chars[i].sprite = game.add.sprite((((game.width/4)*(i+1)-game.width/4)) ,game.height - 110,sprites[i]);
    	game.global.chars[i].sprite.scale.setTo(.3,.3);
      game.global.chars[i].scoreText = game.add.text((((game.width/4)*(i+1)-game.width/4)+game.global.chars[i].sprite.width),game.height - 50, game.global.chars[i].score, game.global.mainFont);
      game.add.tween(game.global.chars[i].sprite).to({x: game.world.x + game.width/4, y: ((game.height/4)*(i+1))-game.height/4}, 250, Phaser.Easing.Default, true, 250);
    }
    game.global.chars[0].scoreText.text = game.global.roundStats[game.global.currentRound].score;

    game.global.nextRoundClick = function(){
      game.global.currentRound++;
      game.state.start('play');
    };

    game.global.nextRoundBtn = game.add.button(game.world.width + 1000, game.world.height - 80, 'button', game.global.nextRoundClick, game.global.nextRoundBtn, 2, 1, 0);
    game.global.nextRoundBtn.data.text = game.add.text(0, 0, "Next Round", game.global.optionFont);
    game.global.nextRoundBtn.data.text.anchor.set(0.5);

    //animate button coming in
    game.add.tween(game.global.nextRoundBtn).to({x: game.world.width - (game.global.nextRoundBtn.width + 80)}, 500, Phaser.Easing.Default, true, 250);

  },
  update: function(){
    for(var i = 0; i < 4; i++){
      game.global.chars[i].scoreText.x = game.global.chars[i].sprite.x + (game.global.chars[i].sprite.width);
      game.global.chars[i].scoreText.y = game.global.chars[i].sprite.centerY;
    }
    game.global.nextRoundBtn.data.text.x = Math.floor(game.global.nextRoundBtn.centerX);
    game.global.nextRoundBtn.data.text.y = Math.floor(game.global.nextRoundBtn.centerY);
  }
};

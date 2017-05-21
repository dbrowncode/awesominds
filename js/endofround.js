var endOfRoundState = {
  create: function(){
    console.log('state: endofround');
    game.global.jinny = game.add.sprite(0,0, 'jinny');
    game.global.jinny.scale.setTo(.1,.1);

    game.global.chars = [];
    var sprites = ['beaver','rabbit','cat','beaver']
    for(var i = 0; i < 4; i++){
    	game.global.chars[i] = {};
    	game.global.chars[i].sprite = game.add.sprite((((game.width/4)*(i+1)-game.width/4)) ,game.height - 110,sprites[i]);
    	game.global.chars[i].sprite.scale.setTo(.3,.3);
    	//game.global.chars[i].score = game.add.text((((game.width/4)*(i+1)-game.width/4)+game.global.chars[i].sprite.width),game.height - 50, '0', game.global.mainFont);
      game.add.tween(game.global.chars[i].sprite).to({x: game.world.x + game.width/4, y: ((game.height/4)*(i+1))-game.height/4}, 250, Phaser.Easing.Default, true, 250);
    }
    //TODO: score graph, ui to continue/quit?

  },
  update: function(){

  }
};

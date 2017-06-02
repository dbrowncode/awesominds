var menuState = {
	create: function() {
    var menuItems = [
      { name: 'Start Game', onClick: menuState.playGame },
      { name: 'Add Questions', onClick: menuState.addQuestions },
    ];
    var prevHeights = 0;
    for (var i = 0; i < menuItems.length; i++) {
      var b = game.world.add(new game.global.SpeechBubble(game, game.world.width + 1000, game.global.logoText.y + game.global.logoText.height*2, game.world.width * .8, menuItems[i].name, false, true, menuItems[i].onClick));
      b.y += prevHeights;
      prevHeights += b.bubbleheight;

      //animate button coming in
      game.add.tween(b).to({x: Math.floor(game.world.centerX - b.bubblewidth/2)}, 500, Phaser.Easing.Default, true, 250 * i);
    }
	},
	playGame: function(){
		game.state.start('menuChapter');
	},
	addQuestions: function(){
		console.log("add questions button pressed");
    //open the upload form modal
    //TODO: figure out why this stops being a function after it has been clicked once??
    $('#uploadModal').modal();
	},
};

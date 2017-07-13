var menuModeState = {
  create: function(){
    console.log('state: menuMode');

    var text = game.add.text(game.world.centerX + 1000, game.global.logoText.bottom, 'Select a Game Mode', game.global.whiteFont);
    game.add.tween(text).to({x: game.world.centerX - (text.width/2)}, 100, Phaser.Easing.Default, true, 0);
    text.setShadow(2, 2, 'rgba(0,0,0,0.5)', 5);
    text.padding.x = 5;

    var back = game.world.add(new game.global.SpeechBubble(game, game.world.x, game.world.y, game.world.width, 'Back', false, true, menuChapterState.backButton));

    var courseText = game.add.bitmapText(back.bubblewidth + game.global.borderFrameSize*2, game.world.y, '8bitoperator', game.global.selectedCourseName, 11 * dpr);
    courseText.tint = 0x000000;

    var chapterText = game.add.bitmapText(game.global.pauseButton.left, game.world.y, '8bitoperator', 'Chapter ' + game.global.selectedChapter, 11 * dpr);
    chapterText.x -= chapterText.width + game.global.borderFrameSize;
    chapterText.tint = 0x000000;

    var modes = [
      { name: 'Classic', prestate: 'pregame', gamestate: 'play'},
      { name: 'Wild, Wild, Guess', prestate: 'pregameSU', gamestate: 'playSU'},
    ];
    var prevHeights = 0;
    for (var i = 0; i < modes.length; i++) {
      var b = game.world.add(new game.global.SpeechBubble(game, game.world.width + 1000, text.bottom, game.world.width * .8, modes[i].name, false, true, this.btnClick));
      b.y += prevHeights;
      prevHeights += b.bubbleheight;
      b.data = modes[i];

      //animate button coming in
      game.add.tween(b).to({x: Math.floor(game.world.centerX - b.bubblewidth/2)}, 350, Phaser.Easing.Default, true, 150 * i);
    }

  },
  btnClick: function(){
    game.global.selectedMode = this.data;
    game.state.start(this.data.prestate);
  },
  backButton: function(){
    game.state.start('menuChapter');
  }
}

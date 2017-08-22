var menuModeState = {
  create: function(){
    console.log('state: menuMode');

    var text = game.add.text(game.world.centerX + 1000, Math.floor(game.global.logoText.bottom), 'Select a Game Mode', game.global.whiteFont);
    game.add.tween(text).to({x: Math.floor(game.world.centerX - (text.width/2))}, 100, Phaser.Easing.Default, true, 0);
    text.setShadow(2, 2, 'rgba(0,0,0,0.5)', 5);
    text.padding.x = 5;

    var back = game.world.add(new game.global.SpeechBubble(game, game.world.x, game.world.y, game.world.width, 'Back', false, true, menuModeState.backButton));

    var courseText = game.add.text(game.global.pauseButton.left, game.world.y, game.global.selectedCourseName, game.global.smallerWhiteFont);
    courseText.x = Math.round(courseText.x - courseText.width - game.global.borderFrameSize);
    courseText.setShadow(2, 2, 'rgba(0,0,0,0.5)', 5);
    courseText.padding.x = 5;

    var chapterText = game.add.text(game.global.pauseButton.left, Math.floor(courseText.bottom - 5), 'Chapter ' + game.global.selectedChapter, game.global.smallerWhiteFont);
    chapterText.x = Math.round(chapterText.x - chapterText.width - game.global.borderFrameSize);
    chapterText.setShadow(2, 2, 'rgba(0,0,0,0.5)', 5);
    chapterText.padding.x = 5;

    var modes = [
      { name: 'Countdown', prestate: 'pregame', gamestate: 'play', id: 0, endstate: 'endOfGame'},
      { name: 'Wild Wild Guess', prestate: 'pregameSU', gamestate: 'playSU', id: 1, endstate: 'endOfGameWWG'},
    ];
    var prevHeights = 10 * dpr;
    for (var i = 0; i < modes.length; i++) {
      var b = game.world.add(new game.global.SpeechBubble(game, game.world.width + 1000, text.bottom, game.world.width * .8, modes[i].name, false, true, this.btnClick));
      b.y += prevHeights;
      prevHeights += b.bubbleheight + 10 * dpr;
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

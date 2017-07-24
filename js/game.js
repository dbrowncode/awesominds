var dpr = Math.floor(window.devicePixelRatio);
var game = new Phaser.Game(Math.floor(window.innerWidth * dpr), Math.floor(window.innerHeight * dpr), Phaser.AUTO, 'gameDiv', null, true, true);
game.global = {}; // create global object we can add properties to and access from any state

game.global.mapNum = function (num, in_min, in_max, out_min, out_max) {
  return Math.floor((num - in_min) * (out_max - out_min) / (in_max - in_min) + out_min);
}

WebFontConfig = {
  google: {
    //add any google fonts here
    families: ['Roboto', 'Varela Round', 'Material Icons']
  }
}

// add game states
game.state.add('preload', preloadState);
game.state.add('menuCourse', menuCourseState);
game.state.add('menuChapter', menuChapterState);
game.state.add('menuMode', menuModeState);
game.state.add('play', playState);
game.state.add('playSU', playStateSU);
game.state.add('endOfGame', endOfGameState);
game.state.add('pregame', preGameState);
game.state.add('pregameSU', preGameStateSU);

game.global.session = phpSession;
game.state.start('preload');

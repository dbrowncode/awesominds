var game = new Phaser.Game(800, 600, Phaser.AUTO, 'gameDiv', null, true);
game.global = {}; // create global object we can add properties to and access from any state

// add game states
game.state.add('preload', preloadState);
game.state.add('menuCourse', menuCourseState);
game.state.add('menu', menuState);
game.state.add('load', loadState);
game.state.add('play', playState);
game.state.add('endOfGame', endOfGameState);

// call first state
game.state.start('preload');

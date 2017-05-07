var game = new Phaser.Game(800, 600, Phaser.AUTO, 'gameDiv');
game.global = {}; // create global object we can add properties to and access from any state

// add game states
game.state.add('load', loadState);
game.state.add('play', playState);

// call first state
game.state.start('load');

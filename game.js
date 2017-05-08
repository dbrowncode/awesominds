var game = new Phaser.Game(800, 600, Phaser.AUTO, 'gameDiv');
//add all gamestates here.
game.state.add('preload', preloadState);
game.state.add('menu', menuState);
//game.state.add('upload',uploadState);
//game.state.add('play', playState);

game.state.start('preload');

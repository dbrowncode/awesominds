var preloadState = {
	 preload: function() {

		game.load.image('sky', 'assets/sky.png');
		game.load.spritesheet('button', 'assets/button_sprite_sheet.png', 193, 71);
	},
	
	create: function() {
		game.state.start('menu');
	},
};

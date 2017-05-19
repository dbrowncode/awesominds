var preloadState = {
	 preload: function() {
		console.log('state: preload');
		game.load.image('sky', 'assets/sky.png');
		game.load.image('jinny', 'assets/animal.png');
		game.load.image('cat', 'assets/cat.png');
		game.load.image('beaver', 'assets/beaver.png');
		game.load.image('rabbit', 'assets/rabbit.png');

		game.load.spritesheet('button', 'assets/button_sprite_sheet.png', 193, 71);
	},

	create: function() {
		game.global.mainFont = { font: 'Arial', fontSize: '18px', fill: '#000', align: 'center' };
		game.global.optionFont = { font: 'Arial', fontSize: '16px', fill: '#fff', align: 'center'};
		game.state.start('menuCourse');
	},
};

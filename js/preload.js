var preloadState = {
	 preload: function() {
		console.log('state: preload');
		game.load.image('sky', 'assets/sky.png');
		game.load.image('jinny', 'assets/animal.png');
		game.load.image('cat', 'assets/cat.png');
		game.load.image('beaver', 'assets/beaver.png');
		game.load.image('rabbit', 'assets/rabbit.png');
		game.load.image('right', 'assets/right.png');
		game.load.image('wrong', 'assets/wrong.png');
    game.load.image('check', 'assets/check.png');
    game.load.image('x', 'assets/x.png');

		game.load.spritesheet('button', 'assets/button_sprite_sheet.png', 193, 71);
	},

	create: function() {
    //TODO: dynamic font sizes for responsiveness?
		game.global.mainFont = { font: 'Arial', fontSize: '18px', fill: '#000', align: 'center', wordWrap: true, wordWrapWidth: game.width * .75};
		game.global.optionFont = { font: 'Arial', fontSize: '16px', fill: '#fff', align: 'center', wordWrap: true, wordWrapWidth: 193};
    game.global.rightSideFont = { font: 'Arial', fontSize: '16px', fill: '#000', align: 'right', boundsAlignH: 'right', boundsAlignV: 'top'};

    game.global.shuffleArray = function(array) {
      for (var i = array.length - 1; i > 0; i--) {
        var j = Math.floor(Math.random() * (i + 1));
        var temp = array[i];
        array[i] = array[j];
        array[j] = temp;
      }
      return array;
    };

		game.state.start('menuCourse');
	},
};

var menuState = {

	create: function() {

	
	game.add.tileSprite(0, 0, 1000, 600, 'sky');
	var nameLabel = game.add.text(80,80, 'AWESOMINDS', {font: '50px Arial', fill: '#ffffff'});
	
	var gameText = game.add.text(160,240,'Start Game', {font: '32px Arial', fill: '#ffffff'});
	gameText.anchor.set(0.5);
	gameText.inputEnabled = true;
	gameText.events.onInputDown.add(this.playGame,this);
	
	var questionText= game.add.text(160,320,'Add Questions', {font: '32px Arial', fill: '#ffffff'});
	questionText.anchor.set(0.5);
	questionText.inputEnabled = true;
	questionText.events.onInputDown.add(this.addQuestions,this);
	
	},

	playGame: function(){
		console.log("start Game");
	},
	addQuestions: function(){
		console.log("add questions");	
	},
};

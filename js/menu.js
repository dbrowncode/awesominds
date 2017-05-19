var menuState = {

	create: function() {
	game.add.tileSprite(0, 0, 1000, 600, 'sky');

	var nameLabel = game.add.text(game.world.centerX,80, 'AWESOMINDS', {font: '50px Arial', fill: '#ffffff', align: "center"});
	nameLabel.anchor.set(0.5);

	var gameText = game.add.text(game.world.centerX,240,'Start Game', {font: '32px Arial', fill: '#ffffff', align: "center"});
	gameText.anchor.set(0.5);
	gameText.inputEnabled = true;
	gameText.events.onInputOver.add(this.over,this);
	gameText.events.onInputOut.add(this.out,this);
	gameText.events.onInputDown.add(this.playGame,this);

	var questionText= game.add.text(game.world.centerX,320,'Add Questions', {font: '32px Arial', fill: '#ffffff', align: "center"});
	questionText.anchor.set(0.5);
	questionText.inputEnabled = true;
	questionText.events.onInputOver.add(this.over, this);
	questionText.events.onInputOut.add(this.out, this);
	questionText.events.onInputDown.add(this.addQuestions,this);

	},
	//seems to only function while clicking.
	over: function(item){
		item.fill = '#ffff44';
	},
	out: function(item){
		item.fill = '#ffffff';
	},
	playGame: function(){
		game.state.start('load');
		console.log("start Game");
	},
	addQuestions: function(){
		//game.state.start('addQ');
		console.log("add questions");
	},

};

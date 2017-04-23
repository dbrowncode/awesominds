<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
    <title>PhaserTest</title>
	<script type="text/javascript" src="js/phaser.min.js"></script>
    <style type="text/css">
        body {
            margin: 0;
        }
    </style>
</head>
<body>

<script type="text/javascript">

var game = new Phaser.Game(800, 600, Phaser.AUTO, '', { preload: preload, create: create, update: update });

function preload() {
  game.load.image('sky', 'assets/sky.png');
  game.load.spritesheet('button', 'assets/button_sprite_sheet.png', 193, 71);
}

var background;
var buttons;
var questionText;
var questions = [
    {
  		"question":"What is the chemical symbol for the element oxygen?",
  		"choices":[
  			"ox2",
  			"O",
  			"X",
  			"G"
  		],
  		"answer":1
    },
    {
			"question":"What comes after a million, billion and trillion?",
			"choices":[
				"Quatrillion",
				"Quadrillion",
				"Fourtrillion",
				"Fourthrillion"
			],
			"answer":1
		},
    {
			"question":"How many options does a binary choice offer?",
			"choices":[
				"One",
				"Two",
				"Three",
				"None"
			],
			"answer":1
		}
  ];

function create() {
  background = game.add.sprite(0, 0, 'sky');
  mainFont = { font: 'Arial', fontSize: '20px', fill: '#000' };
  optionFont = { font: 'Arial', fontSize: '32px', fill: '#fff', align: 'center'};

  showQuestion(questions[2]);
}

function update() {

}

function btnClick(){
  console.log('pressed ' + this.data.letter + ', correct?: ' + this.data.correct);
}

function showQuestion(question){
  questionText = game.add.text(16, 16, question.question, mainFont);
  buttons = [];
  letters = ['A', 'B', 'C', 'D'];
  var i;
  //Create a button for each choice, and put some data into it in case we need it
  for (i = 0; i < question.choices.length; i++) {
    buttons[i] = game.add.button(game.world.centerX - 95, 100 + (71 * i), 'button', btnClick, buttons[i], 2, 1, 0);
    //Set the letter of this option
    buttons[i].data.letter = letters[i];
    //Storing the option text as part of this button's data just in case we need to access it later.
    //Center text over the button
    buttons[i].data.text = game.add.text(buttons[i].x + buttons[i].width/2, buttons[i].y + buttons[i].height/2, question.choices[i], optionFont);
    buttons[i].data.text.anchor.set(0.5);

    //Store a boolean that indicates whether this is the correct answer
    buttons[i].data.correct = (i == question.answer);
  }
}

</script>

</body>
</html>

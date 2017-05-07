<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
    <title>PhaserTest</title>
	<script type="text/javascript" src="js/phaser.min.js"></script>
  <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <style type="text/css">
        body {
            margin: 0;
        }
    </style>
</head>
<body>

<script type="text/javascript">

var game = new Phaser.Game(800, 600, Phaser.AUTO, '', { preload: preload, create: create, update: update });
var background;
var buttons;
var questionText;
var questions = [];

function preload() {
  game.load.image('sky', 'assets/sky.png');
  game.load.spritesheet('button', 'assets/button_sprite_sheet.png', 193, 71);
}

function create() {
  background = game.add.sprite(0, 0, 'sky');
  mainFont = { font: 'Arial', fontSize: '20px', fill: '#000' };
  optionFont = { font: 'Arial', fontSize: '26px', fill: '#fff', align: 'center'};

  // get a chapter of questions from the database and load them into the questions array
  $(function (){
    $.ajax({
      type: 'POST',
      url: 'getquestion.php',
      data: { 'courseid': 150, 'chapter': 1 },
      dataType: 'json',
      success: function(data){
        for (var i = 0; i < data.length; i++) {
          questions[i] = $.parseJSON(data[i]["question"]);
        }
        //due to async nonsense, i have to call the showQuestion function in here for now.
        //later should move the question loading to a separate state from when they're shown, probably?
        //also currently showing a random question just for fun
        showQuestion(questions[Math.floor(Math.random()*questions.length)]);
      }
    });
  });

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
  //Create a button for each choice, and put some data into it in case we need it
  for (var i = 0; i < 4; i++) {
    buttons[i] = game.add.button(game.world.centerX - 95, 100 + (71 * i), 'button', btnClick, buttons[i], 2, 1, 0);
    //Set the letter of this option
    buttons[i].data.letter = letters[i];
    //Storing the option text as part of this button's data just in case we need to access it later.
    //Center text over the button
    buttons[i].data.text = game.add.text(buttons[i].x + buttons[i].width/2, buttons[i].y + buttons[i].height/2, letters[i] + ") " + question.choices[letters[i]], optionFont);
    buttons[i].data.text.anchor.set(0.5);

    //Store a boolean that indicates whether this is the correct answer
    buttons[i].data.correct = (letters[i] == question.answer[0]);
  }
}

</script>
</body>
</html>

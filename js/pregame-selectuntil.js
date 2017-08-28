//copy original pregame state and modify it
var preGameStateSU = Object.create(preGameState);

preGameStateSU.instructLines = [
  "Welcome to Wild Wild Guess. I'm your guide Annabelle.\n"
  + "To survive on the frontier, you have to be good at guessing.\n"
  + "The three of you will answer a series of questions\n"
  + "Click/tap the question to reveal the options\n"
  + "Keep choosing options 'till you get it right, but more guesses means less points\n"
  + "Your goal is to outperform your competition\n"
];

preGameStateSU.makeHost = function(){
  game.global.jinny = game.add.sprite(0,0, 'annabelle', 0);
};

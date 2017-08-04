//copy original pregame state and modify it
var preGameStateSU = Object.create(preGameState);

preGameStateSU.instructLines = [
  "Hi, I'm Annabelle. Welcome to 'Wild, Wild, Guess', ",
  "To survive on the frontier we need people with good instincts, people who are good at guessin'.",
  "I'm gonna show you some questions to see which of you is best at guessin'.",
  "Keep choosin' until yer right, but more guesses means less points.",
  "Meet yer competition!"
];

preGameStateSU.makeHost = function(){
  game.global.jinny = game.add.sprite(0,0, 'annabelle', 0);
};

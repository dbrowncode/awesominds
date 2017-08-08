//copy original end of game state and modify it
var endOfGameStateWWG = Object.create(endOfGameState);

endOfGameStateWWG.hostMindStates = [
  { min: 70, max: 100, mind: "You have thrived on the frontier!", label: "Thrive", gameOver: false, bonus: 50},
  { min: 50, max: 69, mind: "You have survived on the frontier!", label: "Survive", gameOver: false, bonus: 0},
  { min: 0, max: 49, mind: "You have died on the frontier!", label: "Die", gameOver: true, bonus: 0}
];

endOfGameStateWWG.optionButtons = function(gameOver){
  var buttonsTemplate = [
    { text: 'Select Different Course', function: game.state.getCurrentState().chooseCourseClick },
    { text: 'Select Different Game', function: game.state.getCurrentState().chooseChapterClick },
    { text: 'Log Out', function: game.state.getCurrentState().logOutClick }
  ];
  var buttons = [];

  if(!gameOver){
    buttons.push({ text: 'Play Round ' + (game.global.roundNum + 1), function: game.state.getCurrentState().playAgainClick });
  }

  for (var i = 0; i < buttonsTemplate.length; i++) {
    buttons.push(buttonsTemplate[i]);
  }

  return buttons;
};

endOfGameStateWWG.getStatLines = function(gameOver){
  var statLines = [
    game.global.session.play_name,
    "Round " + game.global.roundNum + " Stats:",
    "Score This Round: " + game.global.totalStats.score,
    "Your Highest Score: " + game.global.scoreData["high_score"],
    "Total Points Earned: " + game.global.scoreData["total_score"]
  ];
  if(!gameOver){
    statLines.push("Round " + (game.global.roundNum + 1) + " Loaded and Ready");
  }
  return statLines;
}

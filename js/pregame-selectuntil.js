//copy original pregame state and modify it
var preGameStateSU = Object.create(preGameState);

preGameStateSU.instructLines = [
  "How to play:\nA question will appear.\nClick/tap the question to make the answer choices appear.\nChoose the right answer.\nIf you are incorrect, keep guessing.\nYou'll score more points with fewer guesses.\n \nEach round has 10 questions.\n \nGoal:\nTo win the round, score more points than your opponents\nThe first to win 5 rounds wins the game."
];

preGameStateSU.makeHost = function(){
  game.global.jinny = game.add.sprite(0,0, 'annabelle', 0);
  game.global.hostText = game.add.text(0, 0, 'Annabelle', game.global.smallerWhiteFont);
  game.global.hostText.setShadow(2, 2, 'rgba(0,0,0,0.5)', 5);
  game.global.hostText.padding.x = 5;
};

preGameStateSU.update = function(){
  for (var i = 0; i < game.global.chars.length; i++) {
    game.global.chars[i].name.x = Math.floor(game.global.chars[i].sprite.right + (10*dpr));
    game.global.chars[i].name.y = Math.floor(game.global.chars[i].sprite.centerY + (10*dpr));
  }
}

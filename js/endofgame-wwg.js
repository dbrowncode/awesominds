//copy original end of game state and modify it
var endOfGameStateWWG = Object.create(endOfGameState);

endOfGameStateWWG.hostMindStates = [
  { min: 90, max: 100, mind: "n awesomind", label: "Awesome!"},
  { min: 70, max: 89, mind: " great mind", label: "Great"},
  { min: 50, max: 69, mind: " good mind", label: "Good"},
  { min: 0, max: 49, mind: " mediocre mind", label: "Mediocre"}
];

var dpr = Math.floor(window.devicePixelRatio);
var game = new Phaser.Game(window.innerWidth * dpr, window.innerHeight * dpr, Phaser.AUTO, 'gameDiv', null, true, true);
game.global = {}; // create global object we can add properties to and access from any state

WebFontConfig = {
  google: {
    //add any google fonts here
    families: ['Roboto', 'Varela Round']
  }
}

// add game states
game.state.add('preload', preloadState);
game.state.add('menuCourse', menuCourseState);
game.state.add('menuChapter', menuChapterState);
game.state.add('load', loadState);
game.state.add('play', playState);
game.state.add('endOfGame', endOfGameState);
game.state.add('pregame',preGameState);

$(function (){
  $.ajax({
    url: 'getsession.php',
    success: function(data){
      game.global.session = $.parseJSON(data);
      game.state.start('preload');
    }
  });
});

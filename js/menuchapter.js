var menuChapterState = {
  create: function(){
    console.log('state: menuChapter');

    var text = game.add.bitmapText(game.world.centerX + 1000, game.global.logoText.y + game.global.logoText.height*2, '8bitoperator', 'Select a Chapter', 22 * dpr);
    text.x -= text.width/2;
    text.smoothed = false;
    game.add.tween(text).to({x: game.world.centerX - (text.width/2)}, 100, Phaser.Easing.Default, true, 250);

    var back = game.world.add(new game.global.SpeechBubble(game, game.world.x, game.world.y, game.world.width, 'Back', false, true, menuChapterState.backButton));

    var courseText = game.add.bitmapText(back.bubblewidth + game.global.borderFrameSize*2, game.world.y, '8bitoperator', game.global.selectedCourseName, 11 * dpr);
    courseText.tint = 0x000000;

    var chapters = [];
    $(function (){
      $.ajax({
        url: 'getchapters.php',
        data: 'courseid=' + game.global.selectedCourse,
        success: function(data){
          console.log(data);
          chapters = $.parseJSON(data);
          var prevHeights = 0;
          for (var i = 0; i < chapters.length; i++) {
            var b = game.world.add(new game.global.SpeechBubble(game, game.world.width + 1000, text.y + text.height*2, game.world.width * .8, 'Chapter ' + chapters[i].chapter, false, true, menuChapterState.chapterBtnClick));
            b.y += prevHeights;
            prevHeights += b.bubbleheight;
            b.data.chapter = chapters[i];

            //animate button coming in
            game.add.tween(b).to({x: Math.floor(game.world.centerX - b.bubblewidth/2)}, 500, Phaser.Easing.Default, true, 250 * i);
          }
        }
      });
    });
  },
  chapterBtnClick: function(){
    game.global.selectedChapter = this.data.chapter.chapter;
    console.log('selected chapter id: ' + game.global.selectedChapter);
    game.state.start('pregame');
  },
  backButton: function(){
    game.state.start('menu');
  }
}

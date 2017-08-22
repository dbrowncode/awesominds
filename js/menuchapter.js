var menuChapterState = {
  create: function(){
    console.log('state: menuChapter');

    var text = game.add.text(game.world.centerX + 1000, Math.floor(game.global.logoText.bottom), 'Select a Chapter/Game', game.global.whiteFont);
    game.add.tween(text).to({x: Math.floor(game.world.centerX - (text.width/2))}, 100, Phaser.Easing.Default, true, 0);
    text.setShadow(2, 2, 'rgba(0,0,0,0.5)', 5);
    text.padding.x = 5;

    var back = game.world.add(new game.global.SpeechBubble(game, game.world.x, game.world.y, game.world.width, 'Back', false, true, menuChapterState.backButton));
    game.add.tween(game.global.logoText).to({x: Math.floor(game.world.x + back.bubblewidth + game.global.borderFrameSize)}, 60, Phaser.Easing.Default, true, 0);

    var courseText = game.add.text(game.global.pauseButton.left, game.world.y, game.global.selectedCourseName, game.global.smallerWhiteFont);
    courseText.x = Math.round(courseText.x - courseText.width - game.global.borderFrameSize);
    courseText.setShadow(2, 2, 'rgba(0,0,0,0.5)', 5);
    courseText.padding.x = 5;

    var chapters = [];
    $(function (){
      $.ajax({
        url: 'getchapters.php',
        data: 'courseid=' + game.global.selectedCourse,
        success: function(data){
          console.log(data);
          chapters = $.parseJSON(data);
          var prevHeights = 10 * dpr;
          for (var i = 0; i < chapters.length; i++) {
            var b = game.world.add(new game.global.SpeechBubble(game, game.world.width + 1000, text.bottom, game.world.width * .8, 'Chapter ' + chapters[i].chapter, false, true, menuChapterState.chapterBtnClick));
            b.y += prevHeights;
            prevHeights += b.bubbleheight + 10 * dpr;
            b.data.chapter = chapters[i];

            //animate button coming in
            game.add.tween(b).to({x: Math.floor(game.world.centerX - b.bubblewidth/2)}, 350, Phaser.Easing.Default, true, 150 * i);
          }
        }
      });
    });
  },
  chapterBtnClick: function(){
    game.global.selectedChapter = this.data.chapter.chapter;
    console.log('selected chapter id: ' + game.global.selectedChapter);
    game.state.start('menuMode');
  },
  backButton: function(){
    game.state.start('menuCourse');
  }
}

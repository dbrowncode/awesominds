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
      $.ajax({ //get list of chapters from the database and create a button for each one
        url: 'getchapters.php',
        data: 'courseid=' + game.global.selectedCourse,
        success: function(data){
          console.log(data);
          chapters = $.parseJSON(data);
          var prevHeights = 10 * dpr;
          var now = moment();
          for (var i = 0; i < chapters.length; i++) {
            //check availability date start and end; if the current time is between the two, the chapter should be available to play
            var isAvailable = now.isBetween(moment(chapters[i].date_start), moment(chapters[i].date_end));
            var b = game.world.add(new game.global.SpeechBubble(game, game.world.width + 1000, text.bottom, game.world.width * .8, chapters[i].chapterid + ' - ' + chapters[i].chaptername, false, isAvailable, menuChapterState.chapterBtnClick));
            b.y += prevHeights;
            prevHeights += b.bubbleheight + 10 * dpr;
            b.data.chapter = chapters[i];
            if(!isAvailable){
              b.tint = 0x999999;
              var unavailText = game.add.text(b.x, b.y + b.bubbleheight, '(Currently Unvailable)', game.global.smallerWhiteFont);
              unavailText.setShadow(2, 2, 'rgba(0,0,0,0.5)', 5);
              unavailText.padding.x = 5;
              prevHeights += unavailText.height;
            }

            //animate button coming in
            game.add.tween(b).to({x: Math.floor(game.world.centerX - b.bubblewidth/2)}, 350, Phaser.Easing.Default, true, 150 * i);
            if(!isAvailable) game.add.tween(unavailText).to({x: Math.floor(game.world.centerX - unavailText.width/2)}, 350, Phaser.Easing.Default, true, 150 * i);
          }
        }
      });
    });
  },
  chapterBtnClick: function(){
    game.global.selectedChapter = this.data.chapter.chapterid;
    console.log('selected chapter id: ' + game.global.selectedChapter);
    game.state.start('menuMode');
  },
  backButton: function(){
    game.state.start('menuCourse');
  }
}

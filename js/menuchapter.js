var menuChapterState = {
  create: function(){
    console.log('state: menuChapter');
    game.global.chooseChapterText = game.add.text(game.world.width + 1000, 40, 'Select a Chapter', game.global.mainFont);
    game.global.chooseChapterText.anchor.set(0.5);
    game.add.tween(game.global.chooseChapterText).to({x: game.world.centerX}, 100, Phaser.Easing.Default, true, 250);

    game.global.chapterButtons = [];

    game.global.chapterBtnClick = function(){
      game.global.selectedChapter = this.data.chapter.chapter;
      console.log('selected chapter id: ' + game.global.selectedChapter);
      game.state.start('load');
    }

    game.global.chapters = [];
    $(function (){
      $.ajax({
        url: 'getchapters.php',
        data: 'courseid=' + game.global.selectedCourse,
        success: function(data){
          console.log(data);
          game.global.chapters = $.parseJSON(data);

          for (var i = 0; i < game.global.chapters.length; i++) {
            game.global.chapterButtons[i] = game.add.button(game.world.width + 1000, 100 + (71 * i), 'button', game.global.chapterBtnClick, game.global.chapterButtons[i], 2, 1, 0);

            game.global.chapterButtons[i].data.text = game.add.text(0, 0, 'Chapter ' +  game.global.chapters[i].chapter, game.global.optionFont);
            game.global.chapterButtons[i].data.text.anchor.set(0.5);

            game.global.chapterButtons[i].data.chapter = game.global.chapters[i];

            //animate button coming in
            game.add.tween(game.global.chapterButtons[i]).to({x: game.world.centerX - game.global.chapterButtons[i].width/2}, 500, Phaser.Easing.Default, true, 250 * i);
          }
        }
      });
    });
  },
  update: function(){
    //update text position to keep each one moving with its button
    for (var i = 0; i < game.global.chapterButtons.length; i++) {
      game.global.chapterButtons[i].data.text.x = Math.floor(game.global.chapterButtons[i].centerX);
      game.global.chapterButtons[i].data.text.y = Math.floor(game.global.chapterButtons[i].centerY);
    }
  }
}

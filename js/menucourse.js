var menuCourseState = {
  create: function(){
    game.global.music.stop();
    game.global.music = game.add.audio('menu');
    game.global.music.volume = 0.5;
    game.global.music.loop = true;
    game.global.music.play();
    console.log('state: menuCourse');

    var text = game.add.bitmapText(game.world.centerX + 1000, game.global.logoText.y + game.global.logoText.height*2, '8bitoperator', 'Select a Course', 22 * dpr);
    text.x -= text.width/2;
    text.smoothed = false;
    game.add.tween(text).to({x: game.world.centerX - (text.width/2)}, 100, Phaser.Easing.Default, true, 250);

    var courses = [];
    $(function (){
      $.ajax({
        url: 'getcourses.php',
        success: function(data){
          console.log(data);
          courses = $.parseJSON(data);
          var prevHeights = 0;
          for (var i = 0; i < courses.length; i++) {
            var b = game.world.add(new game.global.SpeechBubble(game, game.world.width + 1000, text.y + text.height*2, game.world.width * .8, courses[i].name, false, true, menuCourseState.courseBtnClick));
            b.y += prevHeights;
            prevHeights += b.bubbleheight;
            b.data.course = courses[i];

            //animate button coming in
            game.add.tween(b).to({x: Math.floor(game.world.centerX - b.bubblewidth/2)}, 500, Phaser.Easing.Default, true, 250 * i);
          }
        }
      });
    });
  },
  courseBtnClick: function(){
    game.global.selectedCourse = this.data.course.courseid;
    console.log('selected course id: ' + game.global.selectedCourse);
    game.state.start('menu');
  }
}
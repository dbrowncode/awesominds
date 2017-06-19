var menuCourseState = {
  create: function(){
    // game.global.logoText.destroy();
    game.global.logoText = game.add.text(game.world.centerX, 0, 'Awesominds', game.global.whiteFont);
    game.global.logoText.fontWeight = 'bold';
    game.global.logoText.fontSize = 26 * dpr;
    game.global.logoText.centerX = game.world.centerX;
    game.global.logoText.setShadow(3, 3, 'rgba(0,0,0,0.5)', 5);
    game.global.logoText.padding.x = 5;
    game.stage.addChild(game.global.logoText);

    game.global.music = game.add.audio('menu');
    game.global.music.volume = 0.5;
    game.global.music.loop = true;
    game.global.music.play();
    console.log('state: menuCourse');

    var text = game.add.text(game.world.centerX + 1000, game.global.logoText.bottom, 'Select a Course', game.global.whiteFont);
    game.add.tween(text).to({x: game.world.centerX - (text.width/2)}, 100, Phaser.Easing.Default, true, 0);
    text.setShadow(2, 2, 'rgba(0,0,0,0.5)', 5);
    text.padding.x = 5;

    var courses = [];
    $(function (){
      $.ajax({
        url: 'getcourses.php',
        success: function(data){
          console.log(data);
          courses = $.parseJSON(data);
          var prevHeights = 10 * dpr;
          for (var i = 0; i < courses.length; i++) {
            var b = game.world.add(new game.global.SpeechBubble(game, game.world.width + 1000, text.bottom, game.world.width * .8, courses[i].name, false, true, menuCourseState.courseBtnClick));
            b.y += prevHeights;
            prevHeights += b.bubbleheight + 10 *dpr;
            b.data.course = courses[i];

            //animate button coming in
            game.add.tween(b).to({x: Math.floor(game.world.centerX - b.bubblewidth/2)}, 200, Phaser.Easing.Default, true, 250 * i);
          }
        }
      });
    });
    game.global.pauseButton = game.world.add(new game.global.SpeechBubble(game, game.width, 0, game.width, 'II', false, true, game.global.pauseMenu));
    game.global.pauseButton.x -= game.global.pauseButton.bubblewidth + game.global.borderFrameSize;
    game.stage.addChild(game.global.pauseButton);

    game.global.unpauseButton = game.world.add(new game.global.SpeechBubble(game, game.global.pauseButton.x, game.global.pauseButton.y, game.width, 'I>', false, true, game.global.unpause));
    game.global.unpauseButton.visible = false;
    game.stage.addChild(game.global.unpauseButton);

    game.global.pauseButton.visible = true;
  },

  courseBtnClick: function(){
    game.global.selectedCourse = this.data.course.courseid;
    game.global.selectedCourseName = this.data.course.name;
    console.log('selected course id: ' + game.global.selectedCourse);
    $(function (){
      $.ajax({
        type: 'POST',
        url: 'setcourse.php',
        data: { course: game.global.selectedCourse },
        success: function(data){
          //setcourse.php returns the session again with the course added
          game.global.session = $.parseJSON(data);
          game.state.start('menuChapter');
        }
      });
    });
  }
 }

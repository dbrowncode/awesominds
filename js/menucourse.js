var menuCourseState = {
  create: function(){
    console.log('state: menuCourse');
    game.global.background = game.add.sprite(0, 0, 'sky');
    game.global.courseButtons = [];

    game.global.courseBtnClick = function(){
      game.global.selectedCourse = this.data.course.courseid;
      console.log('selected course id: ' + game.global.selectedCourse);
      game.state.start('menu');
    }

    game.global.courses = [];
    game.global.courses[0] = {
      courseid: 150,
      name: "Psychology"
    };
    //TODO: pull available courses from database, remove hardcoded stuff
    // $(function (){
    //   $.ajax({
    //     type: 'POST',
    //     url: 'getcourses.php',
    //     success: function(data){
    //       game.global.courses = [];
    //       console.log(data);
    //       for (var i = 0; i < data.length; i++) {
    //         game.global.courses[i] = $.parseJSON(data[i]["courseid"]);
    //         console.log(game.global.courses[i]);
    //       }
    //     }
    //   });
    // });

    for (var i = 0; i < game.global.courses.length; i++) {
      game.global.courseButtons[i] = game.add.button(game.world.width + 1000, 100 + (71 * i), 'button', game.global.courseBtnClick, game.global.courseButtons[i], 2, 1, 0);

      game.global.courseButtons[i].data.text = game.add.text(0, 0, game.global.courses[i].name, game.global.optionFont);
      game.global.courseButtons[i].data.text.anchor.set(0.5);

      game.global.courseButtons[i].data.course = game.global.courses[i];

      //animate button coming in
      game.add.tween(game.global.courseButtons[i]).to({x: game.world.centerX - game.global.courseButtons[i].width/2}, 500, Phaser.Easing.Default, true, 250 * i, 0, false);
    }
  },
  update: function(){
    //update text position to keep each one moving with its button
    for (var i = 0; i < game.global.courseButtons.length; i++) {
      game.global.courseButtons[i].data.text.x = Math.floor(game.global.courseButtons[i].x + game.global.courseButtons[i].width / 2);
      game.global.courseButtons[i].data.text.y = Math.floor(game.global.courseButtons[i].y + game.global.courseButtons[i].height / 2);
    }
  }
}

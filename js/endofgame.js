var endOfGameState = {
  create: function(){
    console.log('state: endofgame');

    //She aint pretty she just looks that way.
    var mindStates = [
      { min: 90, max: 100, mind: "n awesomind", label: "Awesome!"},
      { min: 70, max: 89, mind: " very good mind", label: "Great"},
      { min: 50, max: 69, mind: " good mind", label: "Good"},
      { min: 0, max: 49, mind: "n okay mind", label: "Meh"}
    ];
    var score = Math.floor(((game.global.totalStats.score) / (game.global.numQuestions * 25)) * 100);
    var lineGfx = game.add.graphics(0,0);
    lineGfx.lineStyle(1, 0x333333, 1);

    game.global.mapNum = function (num, in_min, in_max, out_min, out_max) {
      return Math.floor((num - in_min) * (out_max - out_min) / (in_max - in_min) + out_min);
    }

    for (var i = 0; i < mindStates.length; i++) {
      if(score >= mindStates[i].min && score <= mindStates[i].max){
        game.global.jinnySpeech.destroy();
        game.global.jinnySpeech = game.world.add(new game.global.SpeechBubble(game, game.global.jinny.right, game.world.y + game.global.logoText.height*2, game.world.width - (game.global.jinny.width*2),  "You have a" + mindStates[i].mind + "!", true, false));
      }
      var y = game.global.mapNum(mindStates[i].max, 0, 100, game.global.chars[0].sprite.top, game.global.jinny.bottom);
      lineGfx.moveTo(0, y);
      lineGfx.lineTo(game.world.width, y);
      var label = game.add.bitmapText(game.world.centerX, y, '8bitoperator', mindStates[i].label, 22 * dpr);
      label.x -= label.width/2;
    }

    for (var i = 0; i < game.global.chars.length; i++) {
      var h = game.global.mapNum(game.global.chars[i].score, 0, game.global.numQuestions * 25, 0, game.world.height - game.global.jinny.height - game.global.chars[0].sprite.height);
      game.add.tween(game.global.chars[i].barSprite).to({height: Math.max(h, 1)}, 500, Phaser.Easing.Default, true, 250);

      var s = Math.floor(((game.global.chars[i].score) / (game.global.numQuestions * 25)) * 100);
      var y = game.global.mapNum(s, 0, 100, game.global.chars[0].sprite.top, game.global.jinny.bottom);
      scorePercentLabel = game.add.bitmapText(game.global.chars[i].sprite.centerX, game.global.chars[i].sprite.top, '8bitoperator', s + '%', 11 * dpr);
      scorePercentLabel.tint = 0x000044;
      game.add.tween(scorePercentLabel).to({y: y}, 500, Phaser.Easing.Default, true, 250);
    }

    var playAgainBtn = game.world.add(new game.global.SpeechBubble(game, game.world.width + 1000, game.global.jinnySpeech.y, Math.floor(game.world.width - (game.global.jinny.width*2)), 'Play Again', false, true, endOfGameState.playAgainClick));
    game.add.tween(playAgainBtn).to({x: game.world.width - (playAgainBtn.bubblewidth + game.global.borderFrameSize)}, 500, Phaser.Easing.Default, true, 250);

    var chooseCourseBtn = game.world.add(new game.global.SpeechBubble(game, game.world.width + 1000, playAgainBtn.y + playAgainBtn.height + game.global.borderFrameSize, Math.floor(game.world.width - (game.global.jinny.width*2)), 'Courses', false, true, endOfGameState.chooseCourseClick));
    game.add.tween(chooseCourseBtn).to({x: game.world.width - (chooseCourseBtn.bubblewidth + game.global.borderFrameSize)}, 500, Phaser.Easing.Default, true, 250);

    var logOutBtn = game.world.add(new game.global.SpeechBubble(game, game.world.width + 1000, chooseCourseBtn.y + chooseCourseBtn.height + game.global.borderFrameSize, Math.floor(game.world.width - (game.global.jinny.width*2)), 'Log Out', false, true, endOfGameState.logOutClick));
    game.add.tween(logOutBtn).to({x: game.world.width - (logOutBtn.bubblewidth + game.global.borderFrameSize)}, 500, Phaser.Easing.Default, true, 250);
  },

  playAgainClick: function(){
    game.state.start('play');
  },

  chooseCourseClick: function(){
    game.global.music.stop();
    game.state.start('menuCourse');
  },

  logOutClick: function(){
    window.location.href = "logout.php";
  }
};

var endOfGameState = {
  create: function(){
    console.log('state: endofgame');

    //create ui group to destroy when switching back to play state
    this.endGameUI = game.add.group();
    for (var i = 0; i < game.global.chars.length; i++) {
      this.endGameUI.add(game.global.chars[i].gfx);
      this.endGameUI.add(game.global.chars[i].barSprite);
    }
    $(function (){
      $.ajax({
        url: 'getscore.php',
        data: 'courseid=' + game.global.selectedCourse + '&chapter=' + game.global.selectedChapter,
        success: function(data){
          game.global.scoreData = $.parseJSON(data);
          //if no data is returned, set up new data and insert it
          if(game.global.scoreData == null){
            game.global.scoreData = {
              chapter: game.global.selectedChapter,
              courseid: game.global.selectedCourse,
              high_score: game.global.totalStats.score,
              total_score: game.global.totalStats.score
            };

            $(function (){
              $.ajax({
                type: 'POST',
                url: 'insertscore.php',
                data: game.global.scoreData,
                success: function(data){
                  endOfGameState.makeStatUI();
                }
              });
            });

          }else{
            //if we got data, it's in game.global.scoreData and can be updated
            game.global.scoreData["total_score"] = parseInt(game.global.scoreData["total_score"]) + game.global.totalStats.score;
            game.global.scoreData["high_score"] = Math.max(parseInt(game.global.scoreData["high_score"]), game.global.totalStats.score);
            $(function (){
              $.ajax({
                type: 'POST',
                url: 'updatescore.php',
                data: game.global.scoreData,
                success: function(data){
                  endOfGameState.makeStatUI();
                }
              });
            });
          }

        }
      });
    });

    //She aint pretty she just looks that way.
    var mindStates = [
      { min: 90, max: 100, mind: "n awesomind", label: "Awesome!"},
      { min: 70, max: 89, mind: " great mind", label: "Great"},
      { min: 50, max: 69, mind: " good mind", label: "Good"},
      { min: 0, max: 49, mind: " meh mind", label: "Meh"}
    ];
    var score = Math.min(100, Math.floor(((game.global.totalStats.score) / (game.global.numOrigQuestions * 25)) * 100));
    var lineGfx = game.add.graphics(0,0);
    this.endGameUI.add(lineGfx);
    lineGfx.lineStyle(1, 0x333333, 1);

    if(score > mindStates[0].min){
      //if awesomind, be happy
      game.global.jinny.frame = 2;
    }

    // set up visual areas for score ranges
    for (var i = 0; i < mindStates.length; i++) {
      if(score >= mindStates[i].min && score <= mindStates[i].max){
        game.global.jinnySpeech.destroy();
        game.global.jinnySpeech = game.world.add(new game.global.SpeechBubble(game, game.global.jinny.right + (game.global.borderFrameSize * 2), game.global.logoText.bottom, game.world.width - (game.global.jinny.width*2), "You have a" + mindStates[i].mind + "!", true, false, null, false, null, true));
        this.endGameUI.add(game.global.jinnySpeech);
      }
      var lineYposition = game.global.mapNum(mindStates[i].max, 0, 100, game.global.chars[0].sprite.top, game.global.jinny.bottom);
      lineGfx.moveTo(0, lineYposition);
      lineGfx.lineTo(game.world.width, lineYposition);
      var label = game.add.text(game.world.centerX, lineYposition, mindStates[i].label, game.global.whiteFont);
      label.x -= label.width/2;
      label.setShadow(2, 2, 'rgba(0,0,0,0.5)', 5);
      label.padding.x = 5;
      this.endGameUI.add(label);
    }

    // convert score + progress bars to percentage
    for (var i = 0; i < game.global.chars.length; i++) {
      var topBar = Math.min(game.global.chars[i].score, game.global.numOrigQuestions * 25);
      var scorePercent = Math.floor(((topBar) / (game.global.numOrigQuestions * 25)) * 100);
      var y = game.global.mapNum(scorePercent, 0, 100, game.global.chars[i].sprite.top, game.global.jinny.bottom);
      scorePercentLabel = game.add.bitmapText(game.global.chars[i].sprite.centerX, game.global.chars[i].sprite.top, '8bitoperator', scorePercent + '%', 11 * dpr);
      scorePercentLabel.centerX = Math.floor(game.global.chars[i].sprite.centerX);
      scorePercentLabel.tint = 0x000044;
      this.endGameUI.add(scorePercentLabel);
      game.add.tween(scorePercentLabel).to({y: y}, 500, Phaser.Easing.Default, true, 250);
      game.add.tween(game.global.chars[i].barSprite).to({height: Math.max(game.global.chars[i].sprite.top - y, 1)}, 500, Phaser.Easing.Default, true, 250);
      this.endGameUI.add(game.global.chars[i].barSprite);
    }
  },

  makeStatUI: function(){
    var viewStatsBtn = game.world.add(new game.global.SpeechBubble(game, game.world.width + 1000, game.global.jinnySpeech.y, Math.floor(game.world.width - (game.global.jinny.width*2)), 'Stats & Options', false, true, endOfGameState.viewStatsClick));
    game.add.tween(viewStatsBtn).to({x: game.world.width - (viewStatsBtn.bubblewidth + game.global.borderFrameSize)}, 500, Phaser.Easing.Default, true, 250);
    endOfGameState.endGameUI.add(viewStatsBtn);

    endOfGameState.statsUI = game.add.group();
    endOfGameState.statsUI.visible = false;
    var statBG = game.add.graphics(0, 0);
    statBG.lineStyle(2, 0x000000, 1);
    statBG.beginFill(0x078EB7, 1);
    var rect = statBG.drawRoundedRect(game.world.x + 10, game.global.jinny.bottom, game.world.width - 20, game.world.height - game.global.jinny.height - 10, 10);
    endOfGameState.statsUI.add(statBG);

    var statLines = [
      game.global.session.play_name,
      "Chapter " + game.global.selectedChapter + " Stats:",
      "Total Points Earned: " + game.global.scoreData["total_score"],
      "High Score: " + game.global.scoreData["high_score"]
    ];

    var prevHeights = game.global.jinny.bottom;
    for (var i = 0; i < statLines.length; i++) {
      var t = game.add.text(game.world.centerX, prevHeights, statLines[i], game.global.whiteFont);
      t.x -= t.width/2;
      t.y += t.height;
      t.x = Math.round(t.x);
      t.y = Math.round(t.y);
      t.setShadow(2, 2, 'rgba(0,0,0,0.5)', 5);
      t.padding.x = 5;
      prevHeights += t.height;
      endOfGameState.statsUI.add(t);
    }
    prevHeights += t.height;

    var buttons = [
      { text: 'Play Again', function: endOfGameState.playAgainClick },
      { text: 'Courses', function: endOfGameState.chooseCourseClick },
      { text: 'Log Out', function: endOfGameState.logOutClick }
    ];

    for (var i = 0; i < buttons.length; i++) {
      var b = game.world.add(new game.global.SpeechBubble(game, game.world.centerX, prevHeights + game.global.borderFrameSize, Math.floor(game.world.width - (game.global.jinny.width*2)), buttons[i].text, false, true, buttons[i].function));
      b.centerX -= Math.floor(b.bubblewidth/2);
      prevHeights += b.bubbleheight + game.global.borderFrameSize;
      endOfGameState.statsUI.add(b);
    }

    endOfGameState.endGameUI.add(endOfGameState.statsUI);
  },

  playAgainClick: function(){
    endOfGameState.endGameUI.destroy();
    game.global.isRehash = false;
    game.global.rehashQuestions = [];
    game.state.start(game.global.selectedMode.gamestate, false, false);
  },

  chooseCourseClick: function(){
    game.global.music.stop();
    game.state.start('menuCourse');
  },

  logOutClick: function(){
    window.location.href = "logout.php";
  },

  viewStatsClick: function(){
    endOfGameState.statsUI.visible = !endOfGameState.statsUI.visible;
  }
};

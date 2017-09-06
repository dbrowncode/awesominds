var endOfGameState = {
  hostMindStates : [
    { min: 70, max: 100, mind: "You have earned a jewel for your crown!", label: "Achievement", gameOver: false, bonus: 0},
    { min: 50, max: 69, mind: "You may continue to the next village!", label: "Continue", gameOver: false, bonus: 0},
    { min: 0, max: 49, mind: "You have been banished from the realm!", label: "Banishment", gameOver: true, bonus: 0}
  ],

  optionButtons: function(gameOver){
    var buttonsTemplate = [
      { text: 'Select Different Course', function: game.state.getCurrentState().chooseCourseClick },
      { text: 'Select Different Game', function: game.state.getCurrentState().chooseChapterClick },
      { text: 'Log Out', function: game.state.getCurrentState().logOutClick }
    ];
    var buttons = [];

    if(!gameOver){
      buttons.push({ text: 'Play Round ' + (game.global.roundNum + 1), function: game.state.getCurrentState().playAgainClick });
    }

    for (var i = 0; i < buttonsTemplate.length; i++) {
      buttons.push(buttonsTemplate[i]);
    }

    return buttons;
  },

  getStatLines: function(gameOver){
    var statLines = [
      game.global.session.play_name,
      "Round " + game.global.roundNum + " Stats:",
      "Score This Round: " + game.global.totalStats.score,
      "Your Highest Score: " + game.global.scoreData["high_score"],
      "Total Points Earned: " + game.global.scoreData["total_score"],
    ];
    if(!gameOver){
      statLines.push("Round " + (game.global.roundNum + 1) + " Loaded and Ready");
    }
    return statLines;
  },

  gameOver: false,

  create: function(){
    console.log('state: endofgame');

    //create ui group to destroy when switching back to play state
    this.endGameUI = game.add.group();

    $(function (){
      $.ajax({
        url: 'getscore.php',
        data: 'courseid=' + game.global.selectedCourse + '&chapter=' + game.global.selectedChapter + '&game_mode=' + game.global.selectedMode.id,
        success: function(data){
          game.global.scoreData = $.parseJSON(data);
          //if no data is returned, set up new data and insert it
          if(game.global.scoreData == null){
            game.global.scoreData = {
              chapter: game.global.selectedChapter,
              courseid: game.global.selectedCourse,
              high_score: game.global.totalStats.score,
              total_score: game.global.totalStats.score,
              game_mode: game.global.selectedMode.id,
              times_played: 1
            };

            $(function (){
              $.ajax({
                type: 'POST',
                url: 'insertscore.php',
                data: game.global.scoreData,
                success: function(data){
                  game.state.getCurrentState().makeStatUI();
                }
              });
            });

          }else{
            //if we got data, it's in game.global.scoreData and can be updated
            game.global.scoreData["total_score"] = parseInt(game.global.scoreData["total_score"]) + game.global.totalStats.score;
            game.global.scoreData["high_score"] = Math.max(parseInt(game.global.scoreData["high_score"]), game.global.totalStats.score);
            game.global.scoreData["times_played"] = parseInt(game.global.scoreData["times_played"]) + 1;
            $(function (){
              $.ajax({
                type: 'POST',
                url: 'updatescore.php',
                data: game.global.scoreData,
                success: function(data){
                  game.state.getCurrentState().makeStatUI();
                }
              });
            });
          }

        }
      });
    });
  },

  isGameOver: function(mindStateGameOver){
    var winnerFound = false;
    for (var i = 0; i < game.global.chars.length; i++) {
      if(game.global.chars[i].numJewels >= 5){
        winnerFound = true;
        break;
      }
    }
    return (mindStateGameOver || winnerFound);
  },

  makeStatUI: function(){
    var mindStates = game.state.getCurrentState().hostMindStates.slice();
    var score = Math.min(100, Math.floor(((game.global.totalStats.score) / (game.global.numOrigQuestions * game.global.selectedMode.maxPtsPerQ)) * 100));

    if(score > mindStates[0].min){
      //if awesomind, be happy
      game.global.jinny.frame = 2;
    }

    var mindStateToUse = mindStates[mindStates.length];
    // set up visual areas for score ranges
    for (var i = 0; i < mindStates.length; i++) {
      if(score >= mindStates[i].min && score <= mindStates[i].max){
        mindStateToUse = mindStates[i];
        break;
      }
    }

    var winningScore = 0;
    for (var i = 0; i < game.global.chars.length; i++) { // calculate top score first for tie purposes
      winningScore = Math.max(winningScore, game.global.chars[i].score);
      var thisCharScore = Math.min(100, Math.floor(((game.global.chars[i].score) / (game.global.numOrigQuestions * game.global.selectedMode.maxPtsPerQ)) * 100));
      if(thisCharScore >= mindStates[0].min && thisCharScore <= mindStates[0].max){ //char is in top score range
        if(game.global.selectedMode.id == 0){ //id for countdown crown mode
          game.global.chars[i].crown.frame = ++game.global.chars[i].numJewels; //increment number of jewels and use that frame of the crown spritesheet
          console.log('char ' + i + ' now has ' + game.global.chars[i].numJewels + ' jewels');
        }
      }
    }

    var gameOver = game.state.getCurrentState().isGameOver(mindStateToUse.gameOver);
    game.state.getCurrentState().buttons = game.state.getCurrentState().optionButtons(gameOver);
    game.state.getCurrentState().statLines = game.state.getCurrentState().getStatLines(gameOver);
    game.global.bonus = mindStateToUse.bonus;

    var btns = [ {text: 'Stats', clickFunction: game.state.getCurrentState().viewStatsClick} ];
    if(!gameOver) btns.push({text: 'Play Next Round', clickFunction: game.state.getCurrentState().playAgainClick});
    btns.push({text: 'Quit', clickFunction: game.state.getCurrentState().chooseCourseClick});

    var prevHeightsBtns = game.global.chapterText.bottom;
    var maxBtnWidth = 0;
    for (var b in btns) {
      var btn = game.world.add(new game.global.SpeechBubble(game, game.world.width, prevHeightsBtns, Math.floor(game.world.width - (game.global.jinny.width*2)), btns[b].text, false, true, btns[b].clickFunction));
      btn.x = Math.floor(game.world.width - (btn.bubblewidth + game.global.borderFrameSize));
      game.state.getCurrentState().endGameUI.add(btn);
      prevHeightsBtns += btn.bubbleheight + 5;
      maxBtnWidth = Math.max(maxBtnWidth, btn.bubblewidth);
    };

    game.global.jinnySpeech.destroy();
    game.global.jinnySpeech = game.world.add(new game.global.SpeechBubble(game, game.global.jinny.right + (game.global.borderFrameSize * 2), game.global.chapterText.bottom, game.world.width - (game.global.jinny.width + maxBtnWidth + 10), mindStateToUse.mind, true, false, null, false, null, true));
    this.endGameUI.add(game.global.jinnySpeech);

    // convert score + progress bars to percentage
    for (var i = 0; i < game.global.chars.length; i++) {
      this.endGameUI.add(game.global.chars[i].gfx);
      this.endGameUI.add(game.global.chars[i].barSprite);
      var topBar = Math.min(game.global.chars[i].score, game.global.numOrigQuestions * game.global.selectedMode.maxPtsPerQ);
      var scorePercent = Math.floor(((topBar) / (game.global.numOrigQuestions * game.global.selectedMode.maxPtsPerQ)) * 100);
      var y = game.global.mapNum(scorePercent, 0, 100, (game.global.selectedMode.id == 0) ? game.global.chars[i].crown.y : game.global.chars[i].sprite.y, prevHeightsBtns + 5);
      scorePercentLabel = game.add.bitmapText(game.global.chars[i].sprite.centerX, (game.global.selectedMode.id == 0) ? game.global.chars[i].crown.y : game.global.chars[i].sprite.y, '8bitoperator', scorePercent + '%', 11 * dpr);
      scorePercentLabel.x = Math.floor(game.global.chars[i].sprite.centerX - scorePercentLabel.width/2);
      scorePercentLabel.y = Math.floor(((game.global.selectedMode.id == 0) ? game.global.chars[i].crown.y : game.global.chars[i].sprite.y) - (scorePercentLabel.height*2));
      scorePercentLabel.tint = 0x000044;
      this.endGameUI.add(scorePercentLabel);
      // game.add.tween(scorePercentLabel).to({y: y}, 500, Phaser.Easing.Default, true, 250); game.global.chars[i].numJewels
      game.add.tween(game.global.chars[i].barSprite).to({height: Math.max(((game.global.selectedMode.id == 0) ? game.global.chars[i].crown.y : game.global.chars[i].sprite.y) - y, 1)}, 500, Phaser.Easing.Default, true, 250);
      this.endGameUI.add(game.global.chars[i].barSprite);
      if(game.global.chars[i].score == winningScore){
        var medal = game.add.sprite(game.global.chars[i].sprite.x, (game.global.selectedMode.id == 0) ? game.global.chars[i].crown.y : game.global.chars[i].sprite.y, 'medal');
        medal.width = game.global.chars[i].sprite.width;
        medal.height = game.global.chars[i].sprite.height;
        this.endGameUI.add(medal);
        game.add.tween(medal).to({y: y + (scorePercentLabel.height*2)}, 500, Phaser.Easing.Default, true, 250);
      }
    }

    var lineGfx = game.add.graphics(0,0);
    this.endGameUI.add(lineGfx);
    lineGfx.lineStyle(1, 0x333333, 1);

    //loop mindstates again to add the labels on top of the progress bars
    for (var i = 0; i < mindStates.length; i++) {
      var lineYposition = game.global.mapNum(mindStates[i].max, 0, 100, (game.global.selectedMode.id == 0) ? game.global.chars[0].crown.y : game.global.chars[0].sprite.y, prevHeightsBtns + 5);
      lineGfx.moveTo(0, lineYposition);
      lineGfx.lineTo(game.world.width, lineYposition);
      var label = game.add.text(game.world.centerX, lineYposition, mindStates[i].label, game.global.whiteFont);
      label.x -= label.width/2;
      label.setShadow(2, 2, 'rgba(0,0,0,0.5)', 5);
      label.padding.x = 5;
      label.z++;
      this.endGameUI.add(label);
    }

    game.state.getCurrentState().statsUI = game.add.group();
    game.state.getCurrentState().statsUI.visible = false;
    var statBG = game.add.graphics(0, 0);
    statBG.lineStyle(2, 0x000000, 1);
    statBG.beginFill(0x078EB7, 1);
    var rect = statBG.drawRoundedRect(game.world.x + 10, game.global.jinnySpeech.y + game.global.jinnySpeech.bubbleheight + 5, game.world.width - 20, game.world.height - game.global.jinny.height - 10, 10);
    game.state.getCurrentState().statsUI.add(statBG);

    var statLines = game.state.getCurrentState().statLines;

    var prevHeights = game.global.jinnySpeech.y + game.global.jinnySpeech.bubbleheight + 5;
    for (var i = 0; i < statLines.length; i++) {
      var t = game.add.text(game.world.centerX, prevHeights, statLines[i], game.global.whiteFont);
      t.x -= t.width/2;
      t.y += t.height;
      t.x = Math.round(t.x);
      t.y = Math.round(t.y);
      t.setShadow(2, 2, 'rgba(0,0,0,0.5)', 5);
      t.padding.x = 5;
      prevHeights += t.height;
      game.state.getCurrentState().statsUI.add(t);
    }
    prevHeights += t.height;

    var buttons = game.state.getCurrentState().buttons;

    for (var i = 0; i < buttons.length; i++) {
      var b = game.world.add(new game.global.SpeechBubble(game, game.world.centerX, prevHeights + game.global.borderFrameSize, Math.floor(game.world.width - (game.global.jinny.width*2)), buttons[i].text, false, true, buttons[i].function));
      b.centerX -= Math.floor(b.bubblewidth/2);
      prevHeights += b.bubbleheight + game.global.borderFrameSize;
      game.state.getCurrentState().statsUI.add(b);
    }

    game.state.getCurrentState().endGameUI.add(game.state.getCurrentState().statsUI);
  },

  playAgainClick: function(){
    game.state.getCurrentState().endGameUI.destroy();
    game.global.isRehash = false;
    game.global.rehashQuestions = [];
    game.global.roundNum++;
    game.state.start(game.global.selectedMode.gamestate, false, false);
  },

  chooseCourseClick: function(){
    game.global.music.stop();
    game.state.start('menuCourse');
  },

  chooseChapterClick: function(){
    game.global.music.stop();
    game.global.music = game.add.audio('menu');
    game.global.music.volume = 0.5;
    game.global.music.play();
    game.state.start('menuChapter');
  },

  logOutClick: function(){
    window.location.href = "logout.php";
  },

  viewStatsClick: function(){
    game.state.getCurrentState().statsUI.visible = !game.state.getCurrentState().statsUI.visible;
  }
};

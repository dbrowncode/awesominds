var preGameState = {
  instructLines : [
    "Welcome to Countdown Crown. I'm your guide Jin.\n"
    + "This Realm is looking for a new monarch, one who is quick and bright.\n"
    + "Each Village will present the three of you with a series of questions.\n"
    + "Click/tap the question to reveal the options.\n"
    + "The more quickly you answer correctly, the more preeminence points you earn.\n"
    + "Your goal is to impress the most villages, and become the new monarch of this realm."
  ],

  makeHost: function(){
    game.global.jinny = game.add.sprite(0,0, 'jin', 0);
  },

  create: function(){
    console.log("state: pregame");
    //Host
    // game.global.jinny = game.add.sprite(0,0, 'jin', 0);
    game.global.bonus = 0;
    game.state.getCurrentState().makeHost();
    if (dpr >=2) game.global.jinny.scale.setTo(dpr/4, dpr/4);
    game.add.tween(game.global.logoText).to({x: Math.floor(game.global.jinny.right + game.global.borderFrameSize)}, 60, Phaser.Easing.Default, true, 0);
    this.pregameUI = game.add.group();

    var instructLines = game.state.getCurrentState().instructLines.slice();
    // instructLines[0] += game.global.session['play_name'] + "!";

    var courseText = game.add.text(game.global.pauseButton.left, game.world.y, game.global.selectedCourseName, game.global.smallerWhiteFont);
    courseText.x = Math.round(courseText.x - courseText.width - game.global.borderFrameSize);
    courseText.setShadow(2, 2, 'rgba(0,0,0,0.5)', 5);
    courseText.padding.x = 5;

    game.global.chapterText = game.add.text(game.global.pauseButton.left, Math.floor(courseText.bottom - 5), 'Chapter ' + game.global.selectedChapter, game.global.smallerWhiteFont);
    game.global.chapterText.x = Math.round(game.global.chapterText.x - game.global.chapterText.width - game.global.borderFrameSize);
    game.global.chapterText.setShadow(2, 2, 'rgba(0,0,0,0.5)', 5);
    game.global.chapterText.padding.x = 5;

    var prevHeights = 0;
    var speechX = Math.floor(game.global.jinny.right + (game.global.borderFrameSize * 2));
    var speechWidth = Math.floor(game.world.width - (game.global.jinny.width*1.5));
    var sbtweens = [];
    var bubbles = [];
    for (var i = 0; i < instructLines.length; i++) {
      bubbles[i] = game.world.add(new game.global.SpeechBubble(game, speechX, game.global.chapterText.bottom + prevHeights, speechWidth, instructLines[i], true, false, null, false, null, true));
      prevHeights += Math.floor(bubbles[i].bubbleheight + (10 * dpr));
      this.pregameUI.add(bubbles[i]);
      var w = bubbles[i].width;
      bubbles[i].width = 0;
      sbtweens[i] = game.add.tween(bubbles[i]).to({width: w}, 500, Phaser.Easing.Default, false, (i==0) ? 0 : 1000);
      if(i>0){
        sbtweens[i-1].chain(sbtweens[i]);
      }
    }

    var winChances = [65, 75];
    winChances = game.global.shuffleArray(winChances);
    winChances.unshift(0); //loop below doesn't use first index of winchances, so put garbage in there
    game.global.chars = [];
    game.global.oppImageKeys = game.global.shuffleArray(game.global.oppImageKeys);

    //Dirty fix for opponents being on screen for smaller devices
    game.global.imagecheck = game.add.sprite((game.width + game.width) ,(game.height + game.height), game.global.oppImageKeys[1].imageKey);
    if(dpr>=2) game.global.imagecheck.scale.setTo(dpr/4,dpr/4);
    var image = game.global.imagecheck;

    prevHeights += 10*dpr;

    for(var i = 0; i < 3; i++){
      game.global.chars[i] = {};
      game.global.chars[i].sprite = game.add.sprite(0 - game.world.width, prevHeights, (i==0) ? 'opp' + game.global.session['avatarnum'] : game.global.oppImageKeys[i].imageKey);
      if(dpr>=2) game.global.chars[i].sprite.scale.setTo(dpr/4,dpr/4);
      game.global.chars[i].score = 0;
      game.global.chars[i].scoreText = game.add.bitmapText(Math.floor(game.global.chars[i].sprite.right + game.global.borderFrameSize), Math.floor(game.global.chars[i].sprite.centerY + 20), '8bitoperator', '0', 11 * dpr);
      game.global.chars[i].scoreText.tint = 0x000000;
      game.global.chars[i].name = game.add.bitmapText(0 - game.world.width, 0 - game.world.height, '8bitoperator', 'You', 11 * dpr);
      game.global.chars[i].name.tint = 0x000000;
      if(i!=0){
        prevHeights += Math.floor(image.height + (10 * dpr));
        game.global.chars[i].name.text = game.global.oppImageKeys[i].name;
        game.global.chars[i].chance = winChances[i];
        game.global.chars[i].correct = false;
      }
    }

    //loop again to add ai tweens; needs to be done after the sprites were made in this case
    for (var i = 0; i < game.global.chars.length; i++) {
      game.global.chars[i].tween = game.add.tween(game.global.chars[i].sprite).to({x: Math.floor(((game.width/game.global.chars.length)*(i+1) -game.width/game.global.chars.length)+(game.width/25)), y: (game.height - image.height - game.global.chars[i].name.height*2)}, 250, Phaser.Easing.Default, false);
      if(i==0){
        sbtweens[sbtweens.length - 1].chain(game.global.chars[i].tween);
      }else{
        game.global.chars[i-1].tween.chain(game.global.chars[i].tween);
      }
    }

    var skip = game.world.add(new game.global.SpeechBubble(game, game.world.centerX, game.height, game.width, "Play", false, true, this.skipFunction));
    skip.x = Math.floor(skip.x - (skip.bubblewidth/2));
    skip.y = Math.floor(bubbles[bubbles.length-1].y + bubbles[bubbles.length-1].bubbleheight + (10*dpr));
    this.pregameUI.add(skip);

    sbtweens[0].start();
  },
  skipFunction: function(){
    // get a chapter of questions from the database and load them into the questions array
    $.ajax({
      type: 'GET',
      url: 'getquestion.php',
      data: { 'courseid': game.global.selectedCourse, 'chapter': game.global.selectedChapter },
      dataType: 'json',
      success: function(data){
        game.global.questions = [];
        game.global.origQuestions = [];
        for (var i = 0; i < data.length; i++) {
          game.global.origQuestions[i] = $.parseJSON(data[i]["question"]);
        }
        //once the questions are successfully loaded, move to the play state
        game.state.getCurrentState().pregameUI.destroy();
        game.global.isRehash = false;
        game.global.rehashQuestions = [];
        game.global.roundNum = 1;
        game.state.start(game.global.selectedMode.gamestate, false, false);
      }
    });
  },
  update: function(){
    for (var i = 0; i < game.global.chars.length; i++) {
      game.global.chars[i].name.x = Math.floor(game.global.chars[i].sprite.right + (10*dpr));
      game.global.chars[i].name.y = Math.floor(game.global.chars[i].sprite.centerY + (10*dpr));
    }
  }
};

var preGameState = {
  //TODO add proper sprite and finish the timer
  create: function(){
    console.log("state: pregame");
    //Host
    game.global.jinny = game.add.sprite(0,0, 'jinny');
    game.global.jinny.scale.setTo(.1,.1);
    this.pregameUI = game.add.group();

    var instructLines = [
      "Hi! I'm your host, Jinny. Welcome to Awesominds, " + game.global.session['play_name'] + "!",
      "Earn points by correctly answering each question.",
      "Answer before your competitors to earn full point value.",
      "Meet your competition!"
    ];

    var prevHeights = 0;
    var speechX = Math.floor(game.global.jinny.right);
    var speechY = Math.floor(game.world.y + game.global.logoText.height*2);
    var speechWidth = Math.floor(game.world.width - (game.global.jinny.width*2));
    var sbtweens = [];
    var bubbles = [];
    for (var i = 0; i < instructLines.length; i++) {
      bubbles[i] = game.world.add(new game.global.SpeechBubble(game, speechX, speechY + prevHeights, speechWidth, instructLines[i], true, false));
      prevHeights += Math.floor(bubbles[i].bubbleheight + (10 * dpr));
      this.pregameUI.add(bubbles[i]);
      var w = bubbles[i].width;
      bubbles[i].width = 0;
      sbtweens[i] = game.add.tween(bubbles[i]).to({width: w}, 500, Phaser.Easing.Default, false, (i==0) ? 0 : 2000);
      if(i>0){
        sbtweens[i-1].chain(sbtweens[i]);
      }
    }

    //TODO decide if we want characters to be loaded here or in play state?
    //move NPC to vertical positions, could tween them horizontal after if
    //chapter menu is before this
    //
    var winChances = [20, 40, 60, 75];
    winChances = game.global.shuffleArray(winChances);
    game.global.chars = [];
    game.global.oppImageKeys = game.global.shuffleArray(game.global.oppImageKeys);

    //Dirty fix for opponents being on screen for smaller devices
    game.global.imagecheck = game.add.sprite((game.width + game.width) ,(game.height + game.height), game.global.oppImageKeys[1]);
    game.global.imagecheck.scale.setTo(dpr/4,dpr/4);
    var image = game.global.imagecheck;

    prevHeights += bubbles[bubbles.length - 1].bubbleheight + (10*dpr);
    for(var i = 0; i < 4; i++){
      game.global.chars[i] = {};
      game.global.chars[i].sprite = game.add.sprite(0 - game.world.width, prevHeights, (i==0) ? 'opp' + game.global.session['avatarnum'] : game.global.oppImageKeys[i]);
      game.global.chars[i].sprite.scale.setTo(dpr/4,dpr/4);
      game.global.chars[i].score = 0;
      game.global.chars[i].scoreText = game.add.bitmapText(Math.floor(game.global.chars[i].sprite.right + game.global.borderFrameSize), Math.floor(game.global.chars[i].sprite.centerY + 20), '8bitoperator', ' ', 11 * dpr);
      game.global.chars[i].scoreText.tint = 0x000000;
      //TODO: get character names from array or something once they have names
      game.global.chars[i].name = game.add.bitmapText(0 - game.world.width, 0 - game.world.height, '8bitoperator', 'You', 11 * dpr);
      game.global.chars[i].name.tint = 0x000000;
      if(i!=0){
        prevHeights += Math.floor(image.height + (10 * dpr));
        game.global.chars[i].chance = winChances[i];
        game.global.chars[i].correct = false;
      }
    }

    //loop again to add ai tweens; needs to be done after the sprites were made in this case
    for (var i = 1; i < game.global.chars.length; i++) {
      game.global.chars[i].tween = game.add.tween(game.global.chars[i].sprite).to({x: game.global.jinny.right}, 300, Phaser.Easing.Default, false, 500);
      if(i==1){
        sbtweens[sbtweens.length - 1].chain(game.global.chars[i].tween);
      }else{
        game.global.chars[i-1].tween.chain(game.global.chars[i].tween);
      }
    }

    var skip = game.world.add(new game.global.SpeechBubble(game, game.width, game.height - 50, game.width, "Continue", false, true, this.skipFunction));
    skip.x -= skip.bubblewidth + (game.global.borderFrameSize * 3);
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
        for (var i = 0; i < data.length; i++) {
          game.global.questions[i] = $.parseJSON(data[i]["question"]);
        }
        console.log('chapter ' + game.global.selectedChapter + '; ' + game.global.questions.length + ' questions loaded');
        //once the questions are successfully loaded, move to the play state
        preGameState.pregameUI.destroy();
        game.state.start('play', false, false);
      }
    });
  },
  update: function(){
    for (var i = 1; i < game.global.chars.length; i++) {
      game.global.chars[i].name.x = Math.floor(game.global.chars[i].sprite.right + (10*dpr));
      game.global.chars[i].name.y = Math.floor(game.global.chars[i].sprite.centerY + (10*dpr));
    }
  }
};

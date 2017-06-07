var preGameState = {
  //TODO add proper sprite and finish the timer
  create: function(){
    console.log("state: pregame");
    //Host
    game.global.jinny = game.add.sprite(0,0, 'jinny');
    game.global.jinny.scale.setTo(.1,.1);
    
    game.global.jinnySpeech = game.world.add(new game.global.SpeechBubble(game, game.global.jinny.right, game.world.y + game.global.logoText.height*2, game.world.width - (game.global.jinny.width*2), 'Hi, I\'m you host Jinny.\nWelcome to Awesominds, ' + game.global.session['play_name'] + '!', true, false));
    
    //TODO either add another timer/ or make this a repeatable timer and give function parameters
    delay = game.time.create(false);
    delay.add(1000, this.nextLine, this);
    delay.start(); 
    delay.start(); 
    
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
                 
    for(var i = 1; i < 4; i++){
                                               
      game.global.chars[i] = {};
      game.global.chars[i].sprite = game.add.sprite((((game.width/4)*(i+1) -game.width/3)+(game.width/20)) ,(game.height - image.height), (i==0) ? 'opp' + game.global.session['avatarnum'] : game.global.oppImageKeys[i]);
      game.global.chars[i].sprite.scale.setTo(dpr/4,dpr/4);
      game.global.chars[i].score = 0;
      game.global.chars[i].scoreText = game.add.bitmapText(Math.floor(game.global.chars[i].sprite.right + game.global.borderFrameSize), Math.floor(game.global.chars[i].sprite.centerY + 20), '8bitoperator', game.global.chars[i].score, 11 * dpr);
      game.global.chars[i].scoreText.tint = 0x000000;
      if(i!=0){
       game.global.chars[i].chance = winChances[i];
       game.global.chars[i].correct = false;
      }
    }
    
  //TODO figure out why text isn't in bubbles... probably to do with y ppositioning. 
  var back = game.world.add(new game.global.SpeechBubble(game,100, game.height - 50, 1, "Back",false,true, this.backFunction)); 
  var skip = game.world.add(new game.global.SpeechBubble(game,game.width- 100, game.height - 50, 1, "Continue",false,true, this.skipFunction)); 
  },
  skipFunction: function(){
    game.state.start("menuChapter");
  },
  backFunction: function(){
    game.state.start("menu");
  },
  nextLine: function(){
    console.log("timer tick");
    game.global.jinnySpeech = game.world.add(new game.global.SpeechBubble(game, game.global.jinny.right, game.world.y + game.global.logoText.height*4, game.world.width - (game.global.jinny.width*4), 'Hi, I\'m you host Jinny.\nWelcome to Awesominds, ' + game.global.session['play_name'] + '!', true, false));
 }

};

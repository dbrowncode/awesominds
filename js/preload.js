var preloadState = {
  preload: function() {
	  console.log('state: preload');
    game.scale.scaleMode = Phaser.ScaleManager.RESIZE;
    game.scale.pageAlignHorizontally = true;
    game.scale.pageAlignVertically = false;
    game.scale.windowConstraints.bottom = "visual";
    game.stage.disableVisibilityChange = true;

    game.load.image('sky', 'assets/sky.png');
    game.load.image('jinny', 'assets/animal.png');
    game.load.image('cat', 'assets/cat.png');
    game.load.image('beaver', 'assets/beaver.png');
    game.load.image('rabbit', 'assets/rabbit.png');
    game.load.image('right', 'assets/right.png');
    game.load.image('wrong', 'assets/wrong.png');
    game.load.image('check', 'assets/check.png');
    game.load.image('arrow', 'assets/arrow.png');
    game.load.image('x', 'assets/x.png');

    game.load.audio('play',['assets/music/Mushroom.m4a','assets/music/Mushroom.ogg']);
    game.load.audio('menu',['assets/music/Crystal.m4a','assets/music/Crystal.ogg']);
    game.load.audio('wrong1',['assets/music/WrongAns1.m4a','assets/music/WrongAns1.ogg']);
    game.load.audio('wrong2',['assets/music/WrongAns2.m4a','assets/music/WrongAns2.ogg']);
    game.load.audio('wrong3',['assets/music/WrongAns3.m4a','assets/music/WrongAns3.ogg']);
    game.load.audio('question',['assets/music/QuestionEnters.m4a','assets/music/QuestionEnters.ogg']);
    game.load.audio('endGame',['assets/music/EndOFGame.m4a','assets/music/EndOFGame.ogg']);
    game.load.audio('drums',['assets/music/DrumsAndWhoo.m4a','assets/music/DrumsAndWhoo.ogg']);
    game.load.audio('correct',['assets/music/CorrectAns.m4a','assets/music/CorrectAns.ogg']);
    game.load.audio('correct2',['assets/music/CorrectAns2.m4a','assets/music/CorrectAns2.ogg']);
    game.load.audio('applause',['assets/music/playerWins.m4a','assets/music/PlayerWins.ogg']);

    game.load.spritesheet('button', 'assets/button_sprite_sheet.png', 193, 71);
    game.load.start();
    
    game.global.wrongsounds = [];
    game.global.rightsounds = [];

    var numOppImages = 16;
    game.global.oppImageKeys = [];
    for (var i = 1; i <= numOppImages; i++) {
      game.load.image('opp' + i, 'assets/opp/opp' +  i + '.png');
      if(i != game.global.session['avatarnum']){
        game.global.oppImageKeys.push('opp' + i);
      }
    }

    game.global.borderFrameSize = 9 * dpr;
    game.load.spritesheet('bubble-border','assets/bubbleborder' + dpr + '.png', game.global.borderFrameSize, game.global.borderFrameSize);
    game.load.image('bubble-tail', 'assets/bubbletailleft' + dpr + '.png');
    game.load.bitmapFont('8bitoperator', 'assets/8bitoperator.png', 'assets/8bitoperator.xml');
  },

	create: function() {

    game.global.logoText = game.add.bitmapText(game.world.centerX, 0, '8bitoperator', 'Awesominds', 22 * dpr);
    game.global.logoText.x -= game.global.logoText.width/2;
    game.stage.addChild(game.global.logoText);


    game.global.wrongsounds.push(game.add.audio('wrong1'),game.add.audio('wrong2'),game.add.audio('wrong3'));
    game.global.rightsounds.push(game.add.audio('correct'),game.add.audio('correct2'));
    game.global.music = game.add.audio('menu');
    game.sound.volume = 0;
    //TODO: dynamic font sizes for responsiveness?
		game.global.mainFont = { font: 'Arial', fontSize: '18px', fill: '#000', align: 'center', wordWrap: true, wordWrapWidth: game.width * .75};
		game.global.optionFont = { font: 'Arial', fontSize: '16px', fill: '#fff', align: 'center', wordWrap: true, wordWrapWidth: 193};
    game.global.rightSideFont = { font: 'Arial', fontSize: '16px', fill: '#000', align: 'right', boundsAlignH: 'right', boundsAlignV: 'top'};

    game.global.shuffleArray = function(array) {
      for (var i = array.length - 1; i > 0; i--) {
        var j = Math.floor(Math.random() * (i + 1));
        var temp = array[i];
        array[i] = array[j];
        array[j] = temp;
      }
      return array;
    };

    game.global.SpeechBubble = function(game, x, y, width, text, withTail, asButton, clickFunction) {
      Phaser.Sprite.call(this, game, x, y);

      // Some sensible minimum defaults
      width = width || game.global.borderFrameSize * 3;
      var height = game.global.borderFrameSize * 2;

      // Set up our text and run our custom wrapping routine on it
      this.bitmapText = game.make.bitmapText(x + game.global.borderFrameSize + 3, y + 5, '8bitoperator', text, 11 * dpr);
      game.global.SpeechBubble.wrapBitmapText(this.bitmapText, width);

      // Calculate the width and height needed for the edges
      var bounds = this.bitmapText.getLocalBounds();
      if (bounds.width + 18 > width) {
        width = bounds.width + 18;
      }else{
    	  width = bounds.width + 18;
    	}
      if (bounds.height + 14 > height) {
        height = bounds.height + 14;
      }

      // Create all of our corners and edges
      this.borders = [
        game.make.tileSprite(x + game.global.borderFrameSize, y + game.global.borderFrameSize, width - game.global.borderFrameSize, height - game.global.borderFrameSize, 'bubble-border', 4),
        game.make.image(x, y, 'bubble-border', 0),
        game.make.image(x + width, y, 'bubble-border', 2),
        game.make.image(x + width, y + height, 'bubble-border', 8),
        game.make.image(x, y + height, 'bubble-border', 6),
        game.make.tileSprite(x + game.global.borderFrameSize, y, width - game.global.borderFrameSize, game.global.borderFrameSize, 'bubble-border', 1),
        game.make.tileSprite(x + game.global.borderFrameSize, y + height, width - game.global.borderFrameSize, game.global.borderFrameSize, 'bubble-border', 7),
        game.make.tileSprite(x, y + game.global.borderFrameSize, game.global.borderFrameSize, height - game.global.borderFrameSize, 'bubble-border', 3),
        game.make.tileSprite(x + width, y + game.global.borderFrameSize, game.global.borderFrameSize, height - game.global.borderFrameSize, 'bubble-border', 5)
      ];

      // Add all of the above to this sprite
      for (var b = 0, len = this.borders.length; b < len; b++) {
        this.addChild(this.borders[b]);
      }

      if(withTail){
        // Add the tail
        this.tail = this.addChild(game.make.image(x - game.cache.getImage("bubble-tail").width*.7, y + bounds.centerY, 'bubble-tail'));
        // this.tail.angle = 90;
      }

      // Add our text last so it's on top
      this.addChild(this.bitmapText);
      this.bitmapText.tint = 0x000000;

      this.pivot.set(x, y);

      //make some properties public for positioning purposes
      this.bounds = bounds;
      this.bubbleheight = height;
      this.bubblewidth = width;

      if(asButton){
        //enable input if this is a button
        this.inputEnabled = true;
        this.input.useHandCursor = true;
        //functions to be used if this is a button
        this.over = function(){
          for (var b in this.borders) {
            this.borders[b].tint = 0x5AC5E8;
          }
        };

        this.out = function(){
          for (var b in this.borders) {
            this.borders[b].tint = 0xffffff;
          }
        };

        this.click = function(){
          for (var b in this.borders) {
            this.borders[b].tint = 0xffffaa;
          }
          clickFunction.call(this);
        }

        this.events.onInputOver.add(this.over, this);
        this.events.onInputOut.add(this.out, this);
        this.events.onInputUp.add(this.click, this);
      }
    };

    game.global.SpeechBubble.prototype = Object.create(Phaser.Sprite.prototype);
    game.global.SpeechBubble.prototype.constructor = game.global.SpeechBubble;

    game.global.SpeechBubble.wrapBitmapText = function (bitmapText, maxWidth) {
      var words = bitmapText.text.split(' '), output = "", test = "";

      for (var w = 0, len = words.length; w < len; w++) {
        test += words[w] + " ";
        bitmapText.text = test;
        bitmapText.updateText();
        if (bitmapText.textWidth > maxWidth) {
          output += "\n" + words[w] + " ";
        }
        else {
          output += words[w] + " ";
        }
        test = output;
      }

      output = output.replace(/(\s)$/gm, ""); // remove trailing spaces
      bitmapText.text = output;
      bitmapText.updateText();
    };

    // raise volume for all sound
    //TODO: separate volume for music/fx?
    game.global.volumeUp = function(){
      if(game.paused && game.global.inputInside(this)){
        if(game.sound.volume < 0.9){
          if(game.sound.mute){
            game.global.muteSound.call(this);
          }
          game.sound.volume += 0.1;
          game.global.volText.kill();
          game.global.muteText.kill();
          game.global.makeVolText();
        }
      }
    };

    // lower volume for all sound
    //TODO: separate volume for music/fx?
    game.global.volumeDown = function(){
      if(game.paused && game.global.inputInside(this)){
        if(game.sound.volume > 0.1){
          if(game.sound.mute){
            game.global.muteSound.call(this);
          }
          game.sound.volume -= 0.1;
          game.global.volText.text = game.sound.volume;
          //game.global.muteText.kill();
          game.global.makeVolText();
        }
      }
    };

    // mute or unmute all sound
    //TODO: separate mutes for music/fx?
    game.global.muteSound = function(){
      if(game.paused && game.global.inputInside(this)){
        game.sound.mute = !game.sound.mute;
        game.global.volText.kill();
        game.global.muteText.kill();
        game.global.makeVolText();
      }
    };

    game.global.btnOver = function(){
      if(game.paused && game.global.inputInside(this)){
        this.over.call(this);
      }
    };
    game.global.makeVolText = function(){
      game.global.volText = game.world.add(new game.global.SpeechBubble(game, game.world.centerX, game.global.pausedText.y + game.global.pausedText.height*2, game.world.width * .8, 'Volume: ' +  Math.round( game.sound.volume * 10), false, false));
      game.global.volText.x -= Math.floor(game.global.volText.bubblewidth/2);
      game.global.pauseUI.add(game.global.volText);

      var t = game.sound.mute ? 'Unmute' : 'Mute';
      game.global.muteText = game.world.add(new game.global.SpeechBubble(game, game.world.centerX, game.global.volText.y *1.5, game.world.width * .8, t, false, false));
      game.global.muteText.x -= Math.floor(game.global.muteText.bubblewidth/2);
      game.global.pauseUI.add(game.global.muteText);
    };


    game.global.pauseMenu = function(){

      game.input.onDown.add(game.global.unpause, game.global.unpauseButton);
      var ismuted = game.sound.mute;
      this.visible = false;
      game.global.unpauseButton.visible = true;
      game.paused = true;
      game.sound.mute = ismuted;
      game.global.pauseUI = game.add.group();

      var pauseBG = game.add.graphics(0, 0);
      pauseBG.lineStyle(2, 0x000000, 1);
      pauseBG.beginFill(0x078EB7, 1);
      pauseBG.drawRoundedRect(game.world.x + 10, game.global.logoText.y + game.global.logoText.height*2, game.world.width - 20, game.world.height - (game.global.logoText.y + game.global.logoText.height*2) - 10, 10);
      game.global.pauseUI.add(pauseBG);

      game.global.pausedText = game.add.bitmapText(game.world.centerX, game.global.logoText.y + game.global.logoText.height*2, '8bitoperator', 'Paused', 22 * dpr);
      game.global.pausedText.x -= game.global.pausedText.width/2;
      game.global.pauseUI.add(game.global.pausedText);

      game.global.makeVolText();
      game.input.onDown.add(game.global.muteSound, game.global.muteText);

      var volBtnUp = game.world.add(new game.global.SpeechBubble(game, game.global.volText.x + game.global.volText.bubblewidth + 10, game.global.volText.y, game.world.width * .8, '+', false, true, game.global.volumeUp));
      game.global.pauseUI.add(volBtnUp);
      game.input.onDown.add(game.global.volumeUp, volBtnUp);

      var volBtnDown = game.world.add(new game.global.SpeechBubble(game, game.global.volText.x, game.global.volText.y, game.world.width * .8, '-', false, true, game.global.volumeDown));
      volBtnDown.x -= volBtnDown.bubblewidth + 10;
      game.global.pauseUI.add(volBtnDown);
      game.input.onDown.add(game.global.volumeDown, volBtnDown);

      var courseSelectBtn = game.world.add(new game.global.SpeechBubble(game, game.world.centerX, game.global.volText.y * 2, game.world.width * .8, 'Quit to Course Select', false, true, game.global.quitToCourseSelect));
      courseSelectBtn.x -= Math.floor(courseSelectBtn.bubblewidth/2);
      game.global.pauseUI.add(courseSelectBtn);
      game.input.onDown.add(game.global.quitToCourseSelect, courseSelectBtn);

      var logOutBtn = game.world.add(new game.global.SpeechBubble(game, game.world.centerX, game.global.volText.y * 2.5, game.world.width * .8, 'Log Out', false, true, game.global.quitToCourseSelect));
      logOutBtn.x -= Math.floor(logOutBtn.bubblewidth/2);
      game.global.pauseUI.add(logOutBtn);
      game.input.onDown.add(game.global.logOut, logOutBtn);
     
    };

    //function to check if a click occurs inside a SpeechBubble
    //necessary for any input while the game is paused
    game.global.inputInside = function(item){
      return (game.input.x > item.x && game.input.x < item.x + item.bubblewidth + game.global.borderFrameSize && game.input.y > item.y && game.input.y < item.y + item.bubbleheight + game.global.borderFrameSize);
    };

        game.global.unpause = function(){
      if(game.paused && game.global.inputInside(this)){
        game.global.unpauseButton.visible = false;
        game.global.pauseButton.visible = true;
        game.global.pauseUI.destroy();
        game.input.onDown.removeAll();
        game.paused = false;
      }
    };

    game.global.quitToCourseSelect = function(){
      if(game.paused && game.global.inputInside(this)){
        this.data.func = function(){
          game.global.unpauseButton.visible = false;
          game.global.pauseButton.visible = true;
          game.global.pauseUI.destroy();
          game.input.onDown.removeAll();
          game.paused = false;
          endOfGameState.chooseCourseClick.call(this);
        }
        game.global.areYouSure(this);
      }
    };

    game.global.logOut = function(){
      if(game.paused && game.global.inputInside(this)){
        this.data.func = function(){
          window.location.href = "logout.php";
        }
        game.global.areYouSure(this);
      }
    };

    game.global.areYouSure = function(btn){
      var sureUI = game.add.group();
      var sureGfx = game.add.graphics(0, 0);
      sureGfx.lineStyle(2, 0x000000, 1);
      sureGfx.beginFill(0x078EB7, 1);
      sureGfx.drawRoundedRect(game.world.x + 10, game.global.logoText.y + game.global.logoText.height*2, game.world.width - 20, game.world.height - (game.global.logoText.y + game.global.logoText.height*2) - 10, 10);
      sureUI.add(sureGfx);

      var txt = game.add.bitmapText(game.world.centerX, game.global.logoText.y + game.global.logoText.height*2, '8bitoperator', btn.bitmapText.text, 22 * dpr);
      txt.x -= txt.width/2;
      sureUI.add(txt);

      var txt2 = game.add.bitmapText(game.world.centerX, txt.y + txt.height, '8bitoperator', 'Are you sure?', 11 * dpr);
      txt2.x -= txt2.width/2;
      sureUI.add(txt2);

      var btnResult = function(btn){
        if(game.paused && game.global.inputInside(this)){
          var v = this.data.value;
          var b = this.data.btn;
          sureUI.destroy();
          if(v){
            console.log(b.data);
            b.data.func.call(this.data.btn);
          }
        }
      };

      var yesBtn = game.world.add(new game.global.SpeechBubble(game, game.world.centerX, txt2.y + txt2.height + game.global.borderFrameSize, game.world.width * .8, 'Yes', false, true, btnResult));
      yesBtn.data.value = true;
      yesBtn.data.btn = btn;
      //console.log(yesBtn.data);
      yesBtn.x -= yesBtn.bubblewidth;
      sureUI.add(yesBtn);
      game.input.onDown.add(btnResult, yesBtn);

      var noBtn = game.world.add(new game.global.SpeechBubble(game, game.world.centerX, yesBtn.y, game.world.width * .8, 'No', false, true, btnResult));
      noBtn.data.value = false;
      noBtn.x += noBtn.bubblewidth;
      sureUI.add(noBtn);
      game.input.onDown.add(btnResult, noBtn);
    };
    game.global.pauseButton = game.world.add(new game.global.SpeechBubble(game, game.width -40, 0, 30, '||', false, true, game.global.pauseMenu));
    game.stage.addChild(game.global.pauseButton);
    game.global.pauseButton.visible = false;

    game.global.unpauseButton = game.world.add(new game.global.SpeechBubble(game, game.global.pauseButton.x, game.global.pauseButton.y, 30, '|>', false, true, game.global.unpause));
    game.global.unpauseButton.visible = false;
    game.stage.addChild(game.global.unpauseButton);
    
    //PROTOTYPE SPLASHSCREEN
    //TODO make it better
    //maybe add loading bar??
    logo = game.add.sprite(game.world.centerX/2+game.world.centerX/4, game.world.centerY -game.cache.getImage('check').height/2, 'check');
    logo.scale.setTo(.5,.5);  
    text = game.add.text(game.world.centerX, game.world.centerY, ' AWESOMINDS ');
    text.anchor.set(0.5);
    text.align='center';
    text.font = 'Arial Black';
    text.fontSize = 70;
    text.fontWeight = 'bold';
    text.fill = '#ec008c';
    text.setShadow(-5,5, 'rgba(0,0,0,0.5)',0);
    this.progress = 0;
    this.loader = game.add.graphics(0,0);
    this.loader.beginFill(0x02c487,1);
    this.loader.anchor.set(.5);
    this.loadText = game.add.text(game.world.centerX, game.height - game.height/3, this.progress + '%');
    this.loadText.fontSize = 15;
  },
  startGame: function(){
    	game.state.start('menuCourse');
 
  },
  //Mock loading bar. It's a masterpiece.
  update: function(){
    if(this.progress <= 99){
    this.progress+=1;
    this.loadText.setText(this.progress + '%');
    this.loader.drawRect(game.width/2 - 100,game.height - game.height/3, this.progress*2, 20);
    }else{
     this.startGame();
    }
  }
};

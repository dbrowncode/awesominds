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
    //game.load.spritesheet('volUp','assets/up.png',1438,720,2);
    //game.load.spritesheet('volDown','assets/down.png',1438,720,2);
    //game.load.image('mute','assets/mute.png');
    //game.load.image('volume','assets/volume.png');
    game.load.image('x', 'assets/x.png');
    game.load.audio('mush',['assets/music/Mushroom.m4a','assets/music/Mushroom.ogg']);
    game.load.audio('crystal',['assets/music/Crystal.m4a','assets/music/Crystal.ogg']);
  	game.load.spritesheet('button', 'assets/button_sprite_sheet.png', 193, 71);

    var numOppImages = 11;
    game.global.oppImageKeys = [];
    for (var i = 1; i <= numOppImages; i++) {
      game.load.image('opp' + i, 'assets/opp/opp' +  i + '.png');
      game.global.oppImageKeys[i-1] = 'opp' + i;
    }

  	game.global.music = [];

    game.global.borderFrameSize = 9 * dpr;
    game.load.spritesheet('bubble-border','assets/bubbleborder' + dpr + '.png', game.global.borderFrameSize, game.global.borderFrameSize);
    game.load.image('bubble-tail', 'assets/bubbletailleft' + dpr + '.png');
    game.load.bitmapFont('8bitoperator', 'assets/8bitoperator.png', 'assets/8bitoperator.xml');
  },

	create: function() {
    game.global.music['menu'] = game.add.audio('crystal');
    game.global.music['play'] = game.add.audio('mush');
    game.global.music['menu'].volume = 0 //0.5;
    game.global.music['play'].volume = 0 //0.5;

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

    game.global.SpeechBubble = function(game, x, y, width, text, withTail) {
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

      // Offset the position to be centered on the end of the tail
      this.pivot.set(x + 25, y + height + 24);
      this.bounds = bounds;
      this.bubbleheight = height;
      this.bubblewidth = width;
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

		game.state.start('menuCourse');
	},
};

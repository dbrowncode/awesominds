var optionState = {
	create: function() {
    
	    var nameLabel = game.add.text(game.world.centerX,80, 'Options', {font: '50px Arial', fill: '#ffffff', align: "center"});
	    nameLabel.anchor.set(0.5);

	    var menuText = game.add.text(game.world.centerX, 170 , 'Menu Volume', {font: '32px Arial', fill: '#ffffff', align: "center"});
	    menuText.anchor.set(0.5);
        var menuVolume = game.add.text(game.world.centerX,200, game.global.music['menu'].volume * 100  + '%', {font: '32px Arial', fill: '#ffffff', align: "center"});
        var playVolume = game.add.text(game.world.centerX,330, game.global.music['play'].volume * 100  + '%', {font: '32px Arial', fill: '#ffffff', align: "center"});

	    var volumeUpText = game.add.text(game.world.centerX + 100,220,'UP', {font: '32px Arial', fill: '#ffffff', align: "center"});
	    volumeUpText.anchor.set(0.5);
	    volumeUpText.inputEnabled = true;
	    volumeUpText.events.onInputOver.add(this.over, this);
	    volumeUpText.events.onInputOut.add(this.out, this);
	    volumeUpText.events.onInputDown.add(this.VolumeUp,{state: 'menu', text: menuVolume});
    
      var volumeDownText = game.add.text(game.world.centerX - 60,220,'DOWN', {font: '32px Arial', fill: '#ffffff', align: "center"});
      volumeDownText.anchor.set(0.5);
	    volumeDownText.inputEnabled = true;
	    volumeDownText.events.onInputOver.add(this.over, this);
	    volumeDownText.events.onInputOut.add(this.out, this);
      volumeDownText.events.onInputDown.add(this.VolumeDown,{state:'menu', text: menuVolume});
        
       
	    var gameText = game.add.text(game.world.centerX, 300,'Game Volume', {font: '32px Arial', fill: '#ffffff', align: "center"});
	    gameText.anchor.set(0.5);
    
	    var vUpText = game.add.text(game.world.centerX + 100,350,'UP', {font: '32px Arial', fill: '#ffffff', align: "center"});
	    vUpText.anchor.set(0.5);
	    vUpText.inputEnabled = true;
	    vUpText.events.onInputOver.add(this.over, this);
	    vUpText.events.onInputOut.add(this.out, this);
    	vUpText.events.onInputDown.add(this.VolumeUp,{state: 'play', text: playVolume});
    
      var vDownText = game.add.text(game.world.centerX - 60,350,'DOWN', {font: '32px Arial', fill: '#ffffff', align: "center"});
      vDownText.anchor.set(0.5);
      vDownText.inputEnabled = true;
      vDownText.events.onInputOver.add(this.over, this);
      vDownText.events.onInputOut.add(this.out, this);
      vDownText.events.onInputDown.add(this.VolumeDown,{state: 'play',text: playVolume});

	    var backText = game.add.text(game.world.centerX,450,'back', {font: '32px Arial', fill: '#ffffff', align: "center"});
	    backText.anchor.set(0.5);
	    backText.inputEnabled = true;
	    backText.events.onInputOver.add(this.over, this);
	    backText.events.onInputOut.add(this.out, this);
	    backText.events.onInputDown.add(this.Back,this);
    
    },

	over: function(item){
		item.fill = '#ffff44';
	},
	out: function(item){
		item.fill = '#ffffff';
	},
  /*
    VolumeDown: function(){
       if(game.global.music[this.state].volume >= .1){
         game.global.music[this.state].volume -= 0.1;
         this.text.setText(Math.round(game.global.music[this.state].volume *100)+'%');
       }
       else{
        this.text.setText('0%');  
       }
             
    },
    VolumeUp: function(){
     if(game.global.music[this.state].volume <= .9){
         game.global.music[this.state].volume += 0.1;
         this.text.setText(Math.round(game.global.music[this.state].volume *100)+'%');
     }
      else{
       this.text.setText('100%');  
     }
     
    },
    */
    Back: function(){
        game.state.start('menu');
    },
};


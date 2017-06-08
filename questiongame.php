<?php
  session_start();
  if(!$_SESSION['logged_in']){
    header("location: index.php");
  }
?>

<title>Awesominds</title>
<link rel="stylesheet" href="css/jquery.modal.css" type="text/css" media="screen" />
<script type="text/javascript" src="js/phaser.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="js/jquery.modal.min.js"></script>
<script type="text/javascript" src="js/menu.js"></script>
<script type="text/javascript" src="js/menuchapter.js"></script>
<script type="text/javascript" src="js/menucourse.js"></script>
<script type="text/javascript" src="js/load.js"></script>
<script type="text/javascript" src="js/play.js"></script>
<script type="text/javascript" src="js/endofgame.js"></script>
<script type="text/javascript" src="js/preload.js"></script>
<script type="text/javascript" src="js/options.js"></script>
<script type="text/javascript" src="js/pregame.js"></script>
<script type="text/javascript" src="js/game.js"></script>

<style type="text/css">
  body {
    margin: 0;
  }
  #wrapper {
    background: rgba(0,133,173,1);
    background: -moz-linear-gradient(top, rgba(0,133,173,1) 0%, rgba(0,133,173,1) 1%, rgba(41,184,229,1) 25%, rgba(188,224,238,1) 71%, rgba(252,252,252,1) 100%);
    background: -webkit-gradient(left top, left bottom, color-stop(0%, rgba(0,133,173,1)), color-stop(1%, rgba(0,133,173,1)), color-stop(25%, rgba(41,184,229,1)), color-stop(71%, rgba(188,224,238,1)), color-stop(100%, rgba(252,252,252,1)));
    background: -webkit-linear-gradient(top, rgba(0,133,173,1) 0%, rgba(0,133,173,1) 1%, rgba(41,184,229,1) 25%, rgba(188,224,238,1) 71%, rgba(252,252,252,1) 100%);
    background: -o-linear-gradient(top, rgba(0,133,173,1) 0%, rgba(0,133,173,1) 1%, rgba(41,184,229,1) 25%, rgba(188,224,238,1) 71%, rgba(252,252,252,1) 100%);
    background: -ms-linear-gradient(top, rgba(0,133,173,1) 0%, rgba(0,133,173,1) 1%, rgba(41,184,229,1) 25%, rgba(188,224,238,1) 71%, rgba(252,252,252,1) 100%);
    background: linear-gradient(to bottom, rgba(0,133,173,1) 0%, rgba(0,133,173,1) 1%, rgba(41,184,229,1) 25%, rgba(188,224,238,1) 71%, rgba(252,252,252,1) 100%);
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#0085ad', endColorstr='#fcfcfc', GradientType=0 );
    margin: 0;
    padding: 0;
  }
  #gamediv {
    margin: 0;
    padding: 0;
    width: 100%;
    height: 100%;
  }
  </style>
</head>
<body>
<div id="wrapper">
  <!-- button in game will use this link open the upload modal -->
  <a href="upload.html" rel="modal" id="uploadModal"></a>
  <div id="gameDiv"></div>
</div>

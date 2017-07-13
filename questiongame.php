<?php
  session_start();
  if(!$_SESSION['logged_in'] || !$_SESSION['active']){
    header("location: index.php");
  }
?>

<title>Awesominds</title>
<link href="https://fonts.googleapis.com/css?family=Roboto+Mono" rel="stylesheet">
<link rel="stylesheet" href="css/jquery.modal.css" type="text/css" media="screen" />

<script type="text/javascript" src="js/phaser.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="js/jquery.modal.min.js"></script>
<script type="text/javascript" src="js/phaser-scrollable.min.js"></script>

<?php
  if ($_SESSION['devmode']) {
    echo '<script type="text/javascript" src="js/devvars.js"></script>';
  } else {
    echo '<script>var devmode = false;</script>';
  }
?>

<script type="text/javascript" src="js/menuchapter.js"></script>
<script type="text/javascript" src="js/menucourse.js"></script>
<script type="text/javascript" src="js/play.js"></script>
<script type="text/javascript" src="js/play-selectuntil.js"></script>
<script type="text/javascript" src="js/endofgame.js"></script>
<script type="text/javascript" src="js/preload.js"></script>
<script type="text/javascript" src="js/pregame.js"></script>
<script type="text/javascript" src="js/game.js"></script>

<style type="text/css">
  @font-face {
    font-family: 'roboto_monoregular';
    src: url('assets/roboto/robotomono-regular-webfont.woff2') format('woff2'),
         url('assets/roboto/robotomono-regular-webfont.woff') format('woff');
    font-weight: normal;
    font-style: normal;
  }
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
<div style="font-family:roboto_monoregular;position:absolute; left:-100000px">Font Loaded</div>
<div id="wrapper">
  <!-- button in game will use this link open the upload modal -->
  <a href="upload.html" rel="modal" id="uploadModal"></a>
  <div id="gameDiv"></div>
</div>

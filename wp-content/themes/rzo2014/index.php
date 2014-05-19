<!DOCTYPE html>
<html ng-app="rzo">
<head>
	<script src="<?php bloginfo('template_directory'); ?>/app/bower_components/angular/angular.min.js"></script>
	<script src="<?php bloginfo('template_directory'); ?>/app/app.js"></script>
	<script src="<?php bloginfo('template_directory'); ?>/app/controllers.js"></script>
	<script src="<?php bloginfo('template_directory'); ?>/app/directives.js"></script>
	<script src="<?php bloginfo('template_directory'); ?>/app/bower_components/ngDialog/js/ngDialog.min.js"></script>
	<script src="<?php bloginfo('template_directory'); ?>/app/bower_components/angular-bootstrap/ui-bootstrap.min.js"></script>
	<script src="<?php bloginfo('template_directory'); ?>/app/bower_components/angular-audio-player/angular-audio-player.min.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/style.css" />
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/app/bower_components/ngDialog/css/ngDialog.css" />
</head>

<body ng-controller="mainController">
	<div>
		<audio data-player-control="audio1" data-playlist="playlist1" data-player-name="audio1" audio-player>
		  <source src="http://archive.org/download/LaSoireeDuPodcast-2013-2014-Episode8/Episode08.mp3" type="audio/mp3">
		</audio>
		<span ng-show="audio1.playing">Player status: Playing</span>
		<span ng-show="!audio1.playing">Player status: Paused</span>
	<div>
</body>
</html>
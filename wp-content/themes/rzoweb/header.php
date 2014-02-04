<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>RZO</title>
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>?v=6" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('template_directory'); ?>/fonts.css" />
<link rel="apple-touch-icon" href="<?php bloginfo('template_directory'); ?>/images/ipad.png" />
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery.tools.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/functions.js?v=1"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/konami.1.3.3.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/js/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />

<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/dropit/dropit.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('template_directory'); ?>/js/dropit/dropit.css" />
<link href='http://fonts.googleapis.com/css?family=Titillium+Web' rel='stylesheet' type='text/css'>
<!--[if IE]>
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('template_directory'); ?>/ie.css" />
<![endif]-->
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('template_directory'); ?>/firefox.css" />

<script src="http://www.google.com/jsapi"></script>
<script>google.load("swfobject", "2.2");</script>
<script type="text/javascript">var switchTo5x=true;</script>
<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
<script type="text/javascript">stLight.options({publisher: "5f09ccac-f7f7-437b-95a5-07ae14f8d35f", doNotHash: true, doNotCopy: true, hashAddressBar: false});</script>
</head>

<body>
<?php require_once('analytics.php'); ?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=451512251565494";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div id="top_menu">
	<div id="top_menu_content">
		<div id="top_logo">
        	<a href="<?php bloginfo('url'); ?>" style="float:left;" onclick="_gaq.push(['_trackEvent', 'Top Menu', 'Page', 'Logo RZO']);"><img src="<?php bloginfo('template_directory'); ?>/images/header/logo_rzo.png" alt="" /></a>
        	<a href="http://www.yoink.ca" target="_blank" style="float:left;" onclick="_gaq.push(['_trackEvent', 'Top Menu', 'Page', 'Yoink Media']);"><img src="<?php bloginfo('template_directory'); ?>/images/header/logo_yoink.png" alt="" /></a>
        </div>
		<ul id="menu">
        	<li style="margin-left:38px;">
            	<a href="#"><img src="<?php bloginfo('template_directory'); ?>/images/header/btn_emissions.png" alt="" /></a>
            	<ul class="drop_menu">
                	<li onclick="_gaq.push(['_trackEvent', 'Dropdown Menu', 'Show', '3 Bieres']);location.href='<?php bloginfo('url'); ?>/show/3-bieres/'">3 Bi&egrave;res</li>
                    <li onclick="_gaq.push(['_trackEvent', 'Dropdown Menu', 'Show', 'AlcoJeux']);location.href='<?php bloginfo('url'); ?>/show/alcojeux/'">AlcoJeux</li>
                    <li onclick="_gaq.push(['_trackEvent', 'Dropdown Menu', 'Show', 'Boulevard Brutal']);location.href='<?php bloginfo('url'); ?>/show/boulevard-brutal/'">Boulevard Brutal</li>
                    <li onclick="_gaq.push(['_trackEvent', 'Dropdown Menu', 'Show', 'Des Si Et Des Rais']);location.href='<?php bloginfo('url'); ?>/show/des-si-et-des-rais/'">Des Si Et Des Rais</li>
                    <li onclick="_gaq.push(['_trackEvent', 'Dropdown Menu', 'Show', 'Fun-Regarder-Films']);location.href='<?php bloginfo('url'); ?>/show/fun-regarder-films/'">Fun-Regarder-Films</li>
                    <li onclick="_gaq.push(['_trackEvent', 'Dropdown Menu', 'Show', 'Geek-o-Rama']);location.href='<?php bloginfo('url'); ?>/show/geek-o-rama/'">Geek-o-Rama</li>
                    <li onclick="_gaq.push(['_trackEvent', 'Dropdown Menu', 'Show', 'Horreur Gamer']);location.href='<?php bloginfo('url'); ?>/show/horreur-gamer/'">Horreur Gamer</li>
                    <li onclick="_gaq.push(['_trackEvent', 'Dropdown Menu', 'Show', 'Je Joue Le Jeu']);location.href='<?php bloginfo('url'); ?>/show/je-joue-le-jeu/'">Je Joue Le Jeu</li>
                    <li onclick="_gaq.push(['_trackEvent', 'Dropdown Menu', 'Show', 'Lepee Legendaire']);location.href='<?php bloginfo('url'); ?>/show/lepee-legendaire/'">L'&Eacute;p&eacute;e L&eacute;gendaire</li>
                    <li onclick="_gaq.push(['_trackEvent', 'Dropdown Menu', 'Show', 'Lerreur 400 Cast']);location.href='<?php bloginfo('url'); ?>/show/lerreur-400-cast/'">L'erreur 400 Cast</li>
                    <li onclick="_gaq.push(['_trackEvent', 'Dropdown Menu', 'Show', 'Le Dernier Des Podcasts']);location.href='<?php bloginfo('url'); ?>/show/le-dernier-des-podcasts/'">Le Dernier Des Podcasts</li>
                    <li onclick="_gaq.push(['_trackEvent', 'Dropdown Menu', 'Show', 'Le Shack A Boisson']);location.href='<?php bloginfo('url'); ?>/show/le-shack-a-boisson/'">Le Shack &Agrave; Boisson</li>
                    <li onclick="_gaq.push(['_trackEvent', 'Dropdown Menu', 'Show', 'La Commission Des Geekeurs']);location.href='<?php bloginfo('url'); ?>/show/la-commission-des-geekeurs/'">La Commission Des Geekeurs</li>
                    <li onclick="_gaq.push(['_trackEvent', 'Dropdown Menu', 'Show', 'La Soiree Du Podcast']);location.href='<?php bloginfo('url'); ?>/show/la-soiree-du-podcast/'">La Soirée Du Podcast</li>
                    <li onclick="_gaq.push(['_trackEvent', 'Dropdown Menu', 'Show', 'Les Mysterieux Etonnants']);location.href='<?php bloginfo('url'); ?>/show/les-mysterieux-etonnants/'">Les Myst&eacute;rieux &Eacute;tonnants</li>
                    <li onclick="_gaq.push(['_trackEvent', 'Dropdown Menu', 'Show', 'MacQuebec']);location.href='<?php bloginfo('url'); ?>/show/macquebec/'">MacQu&eacute;bec</li>
                    <li onclick="_gaq.push(['_trackEvent', 'Dropdown Menu', 'Show', 'Objectif Numerique']);location.href='<?php bloginfo('url'); ?>/show/objectif-numerique/'">Objectif Num&eacute;rique</li>
                    <li onclick="_gaq.push(['_trackEvent', 'Dropdown Menu', 'Show', 'Pod Probleme']);location.href='<?php bloginfo('url'); ?>/show/pod-probleme/'">Pod Probl&egrave;me</li>
                    <li onclick="_gaq.push(['_trackEvent', 'Dropdown Menu', 'Show', 'Point De Vues']);location.href='<?php bloginfo('url'); ?>/show/point-de-vues/'">Point De Vues</li>
                    <li onclick="_gaq.push(['_trackEvent', 'Dropdown Menu', 'Show', 'Retro Nouveau']);location.href='<?php bloginfo('url'); ?>/show/retro-nouveau/'">R&eacute;tro Nouveau</li>
                    <li onclick="_gaq.push(['_trackEvent', 'Dropdown Menu', 'Show', 'Souper De Filles']);location.href='<?php bloginfo('url'); ?>/show/souper-de-filles/'">Souper De Filles</li>
                    <li onclick="_gaq.push(['_trackEvent', 'Dropdown Menu', 'Show', 'Testeur Alpha']);location.href='<?php bloginfo('url'); ?>/show/testeur-alpha/'">Testeur Alpha</li>
                </ul>
            </li>
           	<!--<div style="margin-left:31px;"><a href="#"><img src="<?php //bloginfo('template_directory'); ?>/images/header/btn_blogue.png" alt="" /></a></div>-->
        	<!--<li style="margin-left:290px;"><a href="<?php bloginfo('url'); ?>/soumettre"><img src="<?php bloginfo('template_directory'); ?>/images/header/btn_soumettre.png" alt="" /></a></li>-->
        	<li style="margin-left:350px;margin-top:-1px;"><a href="<?php bloginfo('url'); ?>/a-propos" onclick="_gaq.push(['_trackEvent', 'Top Menu', 'Page', 'A Propos']);"><img src="<?php bloginfo('template_directory'); ?>/images/header/btn_about.png" alt="" /></a></li>
            <li style="margin-left:30px;"><a href="<?php bloginfo('url'); ?>/contact" onclick="_gaq.push(['_trackEvent', 'Top Menu', 'Page', 'Contact']);"><img src="<?php bloginfo('template_directory'); ?>/images/header/btn_contact.png" alt="" /></a></li>
            <li style="margin-left:20px;">
            	<a href="<?php bloginfo('url'); ?>/feed/" target="_blank" onclick="_gaq.push(['_trackEvent', 'Top Menu', 'Page', 'RSS']);"><img src="<?php bloginfo('template_directory'); ?>/images/header/icon_rss.png" style="margin-right:5px;" /></a>
            	<a href="https://www.facebook.com/pages/RZO/188117141371924" target="_blank" onclick="_gaq.push(['_trackEvent', 'Top Menu', 'Page', 'Facebook']);"><img src="<?php bloginfo('template_directory'); ?>/images/header/icon_fb.png" style="margin-right:5px;" /></a>
            	<a href="https://twitter.com/RZOweb" target="_blank" onclick="_gaq.push(['_trackEvent', 'Top Menu', 'Page', 'Twitter']);"><img src="<?php bloginfo('template_directory'); ?>/images/header/icon_twitter.png" /></a>
            </li>        
        </ul>
	</div>
</div>
<div id="container">
	<div id="content">
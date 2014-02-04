<?php
set_post_thumbnail_size( 983, 318, true );
add_theme_support( 'post-thumbnails' );
/*
 if (class_exists('MultiPostThumbnails')) {
        new MultiPostThumbnails(
            array(
                'label' => 'Slideshow Image',
                'id' => 'secondary-image',
                'post_type' => 'post'
            )
        );
    }
add_image_size('post-secondary-image-thumbnail', 274, 153);
*/
function get_episodes(){
	/*$args= array('orderby'=>'post_date',
	             'order'=>'DESC',
				 'category'=>36,
				 'numberposts'=>12);
	
	return get_posts( $args ); */
	return query_posts("cat=36&showposts=13");
}

function getEpisodesByCategory($catId)
{
	return query_posts("cat=".$catId."&showposts=1000");	
}

function getAudioEpisodesByCategory($showId)
{
	return query_posts(array('category__and'=>array($showId,10),'posts_per_page' => -1));	
}

function getVideoEpisodesByCategory($showId)
{
	return query_posts(array('category__and'=>array($showId,11),'posts_per_page' => -1));	
}

function get_cat_posts($cat_id){
	$args = array(
					'numberposts' => -1,
					'offset' => 0,
					'category' => $cat_id,
					'orderby' => 'post_date',
					'order' => 'DESC',
					'post_type' => 'post',
					'post_status' => 'publish',
					'suppress_filters' => true );
		
		return get_posts($args);
}

function get_homepage_banners(){
	$cats= array(27,6,8,25,24,28,7,5,26,3,22,23,21,4,1191,1195,1192,1194,1196,1197,1198,1190);
	$posts= array();
	
	foreach($cats as $catId)
	{
		$args = array(
					'numberposts' => 1,
					'offset' => 0,
					'category' => $catId,
					'orderby' => 'post_date',
					'order' => 'DESC',
					'post_type' => 'post',
					'post_status' => 'publish',
					'suppress_filters' => true );
		
		$post= get_posts($args);
		if($post)
		{
			$posts[]= $post[0];
		}
	}

	usort($posts, 'my_sort_function');
	
	return $posts;
}

function my_sort_function($a, $b)
{
    return $a->post_date < $b->post_date;
}

function showHasAudioAndVideo($showId)
{
	$audio= query_posts(array('category__and'=>array($showId,10)));
	$video= query_posts(array('category__and'=>array($showId,11)));

	if(count($audio) && count($video))
	{
		return true;
	}
	else
	{
		return false;
	}
}

function getShowInfosByEpisode($episode)
{
	$cats= wp_get_post_categories($episode->ID);
	$show= getShowByCategories($cats);

	return $show;
}

function getShowByCategories($cats)
{
	if(in_array(27,$cats)){$code='3bieres';}
	elseif(in_array(6,$cats)){$code='boulbrutal';}
	elseif(in_array(8,$cats)){$code='frf';}
	elseif(in_array(25,$cats)){$code='horreur';}
	elseif(in_array(24,$cats)){$code='epee';}
	elseif(in_array(28,$cats)){$code='geekeurs';}
	elseif(in_array(7,$cats)){$code='lsdp';}
	elseif(in_array(5,$cats)){$code='dernierpodcasts';}
	elseif(in_array(26,$cats)){$code='shack';}
	elseif(in_array(3,$cats)){$code='mysterieux';}
	elseif(in_array(22,$cats)){$code='macquebec';}
	elseif(in_array(23,$cats)){$code='objnumerique';}
	elseif(in_array(21,$cats)){$code='podprobleme';}
	elseif(in_array(4,$cats)){$code='retronouveau';}
	elseif(in_array(1191,$cats)){$code='alcojeux';}
	elseif(in_array(1195,$cats)){$code='rais';}
	elseif(in_array(1192,$cats)){$code='geekorama';}
	elseif(in_array(1194,$cats)){$code='jouelejeu';}
	elseif(in_array(1196,$cats)){$code='e4c';}
	elseif(in_array(1197,$cats)){$code='pointdevues';}
	elseif(in_array(1198,$cats)){$code='souperdefilles';}
	elseif(in_array(1190,$cats)){$code='testeuralpha';}
	
	return getShowInfosByCode($code);
}

function getShowByName($name)
{
	switch($name)
	{
		case '3-bieres':
			$code= '3bieres';
			break;
		case 'boulevard-brutal':
			$code= 'boulbrutal';
			break;
		case 'fun-regarder-films':
			$code= 'frf';
			break;
		case 'horreur-gamer':
			$code= 'horreur';
			break;
		case 'lepee-legendaire':
			$code= 'epee';
			break;
		case 'la-soiree-du-podcast':
			$code= 'lsdp';
			break;
		case 'la-commission-des-geekeurs':
			$code= 'geekeurs';
			break;
		case 'le-dernier-des-podcasts':
			$code= 'dernierpodcasts';
			break;
		case 'le-shack-a-boisson':
			$code= 'shack';
			break;
		case 'les-mysterieux-etonnants':
			$code= 'mysterieux';
			break;
		case 'macquebec':
			$code= 'macquebec';
			break;
		case 'objectif-numerique':
			$code= 'objnumerique';
			break;
		case 'pod-probleme':
			$code= 'podprobleme';
			break;
		case 'retro-nouveau':
			$code= 'retronouveau';
			break;
		case 'alcojeux':
			$code= 'alcojeux';
			break;
		case 'des-si-et-des-rais':
			$code= 'rais';
			break;
		case 'geek-o-rama':
			$code= 'geekorama';
			break;
		case 'je-joue-le-jeu':
			$code= 'jouelejeu';
			break;
		case 'lerreur-400-cast':
			$code= 'e4c';
			break;
		case 'point-de-vues':
			$code= 'pointdevues';
			break;
		case 'souper-de-filles':
			$code= 'souperdefilles';
			break;
		case 'testeur-alpha':
			$code= 'testeuralpha';
			break;	
	}
	
	return getShowInfosByCode($code);
}

function getShowInfosByCode($code = '')
{
	$shows = array('3bieres'=>array('id'=>27,
	                                'code'=>'3bieres',
	                                'name'=>'3 Bi&egrave;res',
									'url'=>'http://www.3bieres.com',
									'show_link'=>'3-bieres',
									'twitter'=>'http://twitter.com/3bieres',
									'facebook'=>'http://www.facebook.com/3bieres',
									'itunes'=>'https://itunes.apple.com/ca/podcast/3-bieres-propulse-par-baladoquebec.ca/id473528238',
									'promo_id'=>'TrPxezzm7i0',
									'rss_audio'=>'http://itunes.baladoquebec.ca/3bieres',
									'subtitle'=>'Podcast humoristique sur des sujets envoyés par le public',
									'description'=>'3 Bières est une émission de discussion franche et saugrenue où Yannick, Pierre-Luc et Gabrielle parlent de VOS sujets le temps d\'une bière! 
Récoltés directement des réseaux sociaux, emails ou signaux de fumée, les sujets sont réunis dans un sac duquel vos animateurs pigent
et discutent le temps d\'une bière, trois fois! Humour, métaphores douteuses et découvertes étranges sont au rendez-vous!',
									'with'=>'Pierre-Luc, Yannick Belzil et Gabrielle Caron'),
				   'boulbrutal'=>array('id'=>6,
				                       'code'=>'boulbrutal',
	                                   'name'=>'Boulevard Brutal',
									   'url'=>'http://www.boulevardbrutal.com',
									   'show_link'=>'boulevard-brutal',
									   'twitter'=>'http://twitter.com/boulevardbrutal',
									   'facebook'=>'http://www.facebook.com/boulevardbrutal',
									   'promo_id'=>'FX2IbGpg3aA',
									   'subtitle'=>'Podcast sur le métal',
									   'description'=>'Vous aimez la musique brutale? La vraie? Seb, Burn et Steve vous parlent de métal sous toutes ses formes. Que ce soit par des entrevues avec des artistes de la scène internationale, des discussions sur les sujets chauds du métal ou en parlant du parcours des plus grands bands, Boulevard Brutal assure. Un podcast qui torche.',
									   'with'=>'Seb Brutal, Burn et Steve Dallaire'),
				   'frf'=>array('id'=>8,
				                'code'=>'frf',
	                            'name'=>'Fun-Regarder-Films',
								'url'=>'http://www.funregarderfilms.ca',
								'show_link'=>'fun-regarder-films',
								'twitter'=>'https://twitter.com/funregarderfilm',
								'facebook'=>'https://www.facebook.com/pages/Fun-Regarder-Films/412602988756871',
								'promo_id'=>'nZiB_NcEmvc',
								'subtitle'=>'Une fois que vous aurez vu les films comme Sébastien les voit, vos classiques ne seront plus jamais les mêmes.',
								'description'=>'En compagnie de son caméraman monosyllabique Fred, Sébastien s\'amuse à trouver le ridicule dans les films qui ont marqué son imaginaire. Et le ridicule, il est partout.',
								'with'=>'Sébastien Bouchard'),
				   'horreur'=>array('id'=>25,
				                    'code'=>'horreur',
	                                'name'=>'Horreur Gamer',
									'url'=>'http://www.horreur-gamer.com/',
									'show_link'=>'horreur-gamer',
									'twitter'=>'https://twitter.com/HorreurGamer',
									'facebook'=>'https://www.facebook.com/pages/Horreur-Gamer/257093344370273',
									'promo_id'=>'A85_vmEQtVw',
									'itunes'=>'https://itunes.apple.com/ca/podcast/horreur-gamer-podcast/id728121559?l=fr&mt=2',
									'rss_audio'=>' http://www.horreur-gamer.com/?feed=rss2&cat=5',
									'subtitle'=>'',
									'description'=>'Horreur Gamer est bien plus qu\'un podcast sur l\'horreur et le gaming ; c\'est une communauté de passionnés. Avec un site web dynamique et une page Facebook où le public est invité à participer aux discussions, l\'équipe d\'Horreur Gamer s\'intéresse à tout ce qui touche de près ou de loin à l\'horreur. Dans le podcast, Six et ses complices analysent en profondeur des films de toute époque et provenance, jasent de jeux vidéos, et comparent des œuvres à leur remake.',
									'with'=>'Six et ses complices'),
				   'epee'=>array('id'=>24,
				                 'code'=>'epee',
	                             'name'=>'L&eacute;p&eacute;e L&eacute;gendaire',
								 'url'=>'http://epeelegendaire.com/',
								 'show_link'=>'lepee-legendaire',
								 'twitter'=>'http://twitter.com/epeelegendaire',
								 'facebook'=>'http://www.facebook.com/EpeeLegendaire',
								 'promo_id'=>'ih-hIidkor4',
								 'rss_audio'=>'http://feeds.feedburner.com/epeelegendaire-balado',
								 'itunes'=>'https://itunes.apple.com/ca/podcast/lepee-legendaire-balado/id454056411',
								 'subtitle'=>'Podcast sur les jeux vidéo et autres geekitudes',
								 'description'=>'Votre rendez-vous hebdomadaire de jeux vidéo et de geekitudes. Notre équipe d\'animateurs vous livre actualités et critiques avec une touche de professionnalisme, mais surtout avec beaucoup de passion. Bienvenue dans notre royaume !',
								 'with'=>'Maxime Tremblay, Anthony Gravel, Mélanie Boutin Chartier et François Dominic Laramée'),
				   'geekeurs'=>array('id'=>28,
				                     'code'=>'geekeurs',
	                                 'name'=>'La Commission Des Geekeurs',
									 'url'=>'',
									 'show_link'=>'la-commission-des-geekeurs',
									 'twitter'=>'http://twitter.com/les_geekeurs',
									 'facebook'=>'https://www.facebook.com/LesGeekeurs',
									 'promo_id'=>'guqlkbOocZw',
									 'itunes'=>'https://itunes.apple.com/ca/podcast/la-commission-des-geekeurs/id421087199',
									 'subtitle'=>'',
									 'description'=>'La Commission des Geekeurs est un breuvage d\'une qualité exceptionnelle. Proposant un goût douteux, elle demeure un des fiers joyaux du terroir canadien-français. Son apparence naturellement trouble et son caractère robuste demeure toutefois très rafraichisseux. Accord mets-podcast : Saucisson de Bologne, rillette de porc, ordre de toasts sur nid de fèves au lard, œufs dans le vinaigre.',
									 'with'=>'ISRAËL D. FLAGEOLE, ERIC LAFONTAINE, MATHIEU CÔTÉ ET CASSEUX POULAIN. <a href="http://ultrapterodactyle.bandcamp.com/track/ce-gentleman-sophistiqu" target="_blank">Thème musical : Ultraptérodactyle</a>'),
				   'lsdp'=>array('id'=>7,
				                 'code'=>'lsdp',
	                             'name'=>'La Soir&eacute;e Du Podcast',
								 'url'=>'http://lasoireedupodcast.ca',
								 'show_link'=>'la-soiree-du-podcast',
								 'twitter'=>'http://twitter.com/SoireeDuPodcast',
								 'facebook'=>'http://www.facebook.com/LaSoireeDuPodcast',
								 'rss_audio'=>'http://feeds.feedburner.com/LaSoireeDuPodcast',
								 'itunes'=>'https://itunes.apple.com/ca/podcast/la-soiree-du-podcast-propulse/id605857384',
								 'promo_id'=>'t9df60EcCp4',
								 'subtitle'=>'Discussions les activités des Canadiens de Montréal',
								 'description'=>'Quatre chums se réunissent autour d\'une (plusieurs) bière pour jaser ouvertement des Canadiens de Montréal et la LNH. On vous parle de hockey avec passion et boisson! Une production Yoink! Média.',
								 'with'=>'Jean-François Cromp, David Thibaudeau, Alexandre Galipeau et Martin Verreault'),
				   'dernierpodcasts'=>array('id'=>5,
				                            'code'=>'dernierpodcasts',
	                                        'name'=>'Le Dernier Des Podcasts',
									        'url'=>'',
											'show_link'=>'le-dernier-des-podcasts',
											'twitter'=>'http://twitter.com/DernierPodcast',
											'facebook'=>'https://www.facebook.com/LeDernierDesPodcasts',
											'itunes'=>'https://itunes.apple.com/ca/podcast/le-dernier-des-podcasts/id502761151',
											'promo_id'=>'pWm_9PsiUP4',
											'subtitle'=>'Le John McClane des podcasts de films d\'action!',
											'description'=>'Chaque semaine, on critique, décortique, dissèque un film d\'action récent ou classique. Occasionnellement, on sort un vieux classique des boules à mites lors de notre désormais célèbre série Les Classiques de la rate. Disciples du légendaire Joe Hallenbeck, nous ne connaissons qu\'une seule façon de faire ou défaire un film : alors, la tête ou la rate?',
											'with'=>'Eric Lafontaine et Maxime Paiement'),
				   'shack'=>array('id'=>26,
				                  'code'=>'shack',
	                              'name'=>'Le Shack &Agrave; Boisson',
								  'url'=>'https://www.facebook.com/shackaboisson',
								  'show_link'=>'le-shack-a-boisson',
								  'promo_id'=>'TAGCSlI0wxc',
								  'twitter'=>'https://twitter.com/Shackaboisson',
								  'facebook'=>'https://facebook.com/shackaboisson',
								  'subtitle'=>'Web-télé / Capsules Youtube de l\'interweb en constante évolution!',
								  'description'=>'À travers ses nombreux festivals brassicoles, ses expériences inusitées et ses entrevues sympathiques, M. Boisson vous propose une façon différente de voir et de découvrir le monde enivrant de la "bouésson".',
								  'with'=>'M. Boisson'),
				   'mysterieux'=>array('id'=>3,
				                       'code'=>'mysterieux',
	                                   'name'=>'Les Myst&eacute;rieux &Eacute;tonnants',
									   'url'=>'http://www.mysterieuxetonnants.com',
									   'show_link'=>'les-mysterieux-etonnants',
									   'twitter'=>'http://twitter.com/MysterieuxE',
									   'facebook'=>'http://www.facebook.com/LesMysterieuxEtonnants',
									   'promo_id'=>'uLee6ik7Ohg',
									   'itunes'=>'https://itunes.apple.com/ca/podcast/les-mysterieux-etonnants-choq.fm/id390095778',
									   'subtitle'=>'Bandes dessinées, Comic Books, cinéma, pop culture',
									   'description'=>'Les Mystérieux étonnants est un projet multiplateforme dédié à vous informer sur l\'univers de la culture populaire. BD, cinéma, jeux vidéo, on couvre tout ce qui touche de près ou de loin à la sphère geek. Nous vous invitons à nous suivre chaque semaine sur podcast, sur notre site internet et en Webtélé.',
									   'with'=>'Benoit Mercier, Simon Chénier, Jeik Dion et Yoann-Karl Whissell'),
				   'macquebec'=>array('id'=>22,
				                      'code'=>'macquebec',
	                                  'name'=>'MacQu&eacute;bec',
									  'url'=>'http://www.macquebec.com',
									  'show_link'=>'macquebec',
									  'twitter'=>'http://twitter.com/macquebec',
									  'facebook'=>'http://www.facebook.com/macquebec',
									  'itunes'=>'https://itunes.apple.com/ca/podcast/le-podcast-de-macquebec-aac/id219105395',
									  'subtitle'=>'Le podcast Macquebec est votre source bimensuelle de nouvelle Apple.',
									  'description'=>'De l\'information contextualisé, des concours et beaucoup de rire dans ce podcast audio, enregistré "devant publique" en vidéo.',
									  'with'=>'Alexis Cornellier, Jean-François Roy et Jean-Michel Boily '),
				   'objnumerique'=>array('id'=>23,
				                         'code'=>'objnumerique',
	                                     'name'=>'Objectif Num&eacute;rique',
									     'url'=>'http://www.objectifnumerique.com/',
										 'show_link'=>'objectif-numerique',
										 'twitter'=>'http://www.twitter.com/ONumerique',
										 'facebook'=>'https://www.facebook.com/ObjectifNumerique',
										 'promo_id'=>'U01Pjr1mVrk',
										 'itunes'=>'https://itunes.apple.com/ca/podcast/objectif-numerique-podcast/id519571370',
										 'subtitle'=>'Podcast sur la photographie pour les débutants comme pour les plus expérimentés',
										 'description'=>'Podcast sur la photographie numérique utilisant un langage accessible aux débutants comme aux plus expérimentés. Au menu : trucs et conseils, tests d\'appareils et accessoires, ainsi que des invités qui viennent partager leur passion.',
										 'with'=>'François Blanchette, Christian Jarry et Stéphane Vaillancourt'),
				   'podprobleme'=>array('id'=>21,
				                        'code'=>'podprobleme',
	                                    'name'=>'Pod Probl&egrave;me',
									    'url'=>'https://www.facebook.com/podprobleme',
										'show_link'=>'pod-probleme',
										'twitter'=>'http://twitter.com/PodProbleme',
									    'facebook'=>'https://www.facebook.com/podprobleme',
										'promo_id'=>'WOA49Tms-WM',
										'itunes'=>'https://itunes.apple.com/ca/podcast/pod-probleme-propulse-par/id335818079',
										'subtitle'=>'Vous avez un problème? Ils ont un podcast!',
										'description'=>'À chaque semaine Frédéric Barbusci reçoit Mathieu Bouillon, Martin Boily et/ou un invité et s\'improvisent expert-en-tout dans le but de brainstormer et de vous aider à trouver plusieurs solutions à l\'un de vos problèmes (ou celui "d\'un ami") recueilli par courriel ou sur les réseaux sociaux. Votre problème sera le nôtre (et ensuite on vous le redonne). À vous!',
										'with'=>'Frédéric Barbusci, Martin Boily et Mathieu Bouillon'),
				   'retronouveau'=>array('id'=>4,
				                         'code'=>'retronouveau',
	                                     'name'=>'R&eacute;tro Nouveau',
									     'url'=>'http://retronouveau.ca',
										 'show_link'=>'retro-nouveau',
										 'promo_id'=>'sMM6im87gAc',
										 'twitter'=>'http://twitter.com/retro_nouveau',
										 'facebook'=>'http://www.facebook.com/RetroNouveau',
										 'subtitle'=>'Web télé sur les jeux vidéos récents et anciens',
										 'description'=>'Rétro Nouveau est une émission décontractée où Bruno Georget (Nouveau) et Dominic Bourret (Rétro) parlent avec passion des jeux vidéos. C\'est aussi des segments captivants comme des guides d\'achat pour les consoles, des suggestions de jeux, des chroniques rétro, des tops, des sessions de jeu sans oublier Amère Gamer, les deux joueurs blasés. Tout ça et bien plus!',
										 'with'=>'Bruno Georget <span style="font-style:italic;">(MNet)</span> <span style="text-transform:none;">et</span> Dominic Bourret <span style="font-style:italic;">(Papa Cassette)</span>'),
					'alcojeux'=>array('id'=>1191,
	                                  'code'=>'alcojeux',
	                                  'name'=>'AlcoJeux',
									  'url'=>'',
									  'show_link'=>'alcojeux',
									  'twitter'=>'http://twitter.com/ALCOJEUX',
									  'facebook'=>'http://www.facebook.com/alcojeux',
									  'itunes'=>'',
									  'promo_id'=>'',
									  'rss_audio'=>'',
									  'subtitle'=>'',
									  'description'=>'Alcojeux est une série dévouée à la découverte de jeux de société et d\'alcool de tout genre.',
									  'with'=>'JF Dicaire et Emilie Boily'),
					'rais'=>array('id'=>1195,
	                                  'code'=>'rais',
	                                  'name'=>'Des Si Et Des Raies',
									  'url'=>'',
									  'show_link'=>'des-si-et-des-rais',
									  'twitter'=>'',
									  'facebook'=>'',
									  'itunes'=>'',
									  'promo_id'=>'',
									  'rss_audio'=>'',
									  'subtitle'=>'',
									  'description'=>'Des si et des rais, c\'est des amis qui improvisent un show inutile et généralement divertissant, parfois avec des vedettes, parfois avec des no names.',
									  'with'=>'Julien Bernatchez, Maxime Gervais, Nicolas Fournier Larocque, Dom Massi, Mathieu Niquette, Mariane Desbiens, Murphy Cooper et Sophie Post-Croteau'),
					'geekorama'=>array('id'=>1192,
	                                  'code'=>'geekorama',
	                                  'name'=>'Geek-o-Rama',
									  'url'=>'',
									  'show_link'=>'geek-o-rama',
									  'twitter'=>'https://twitter.com/Geek0Rama',
									  'facebook'=>'https://www.facebook.com/G33kOrama?ref=hl',
									  'itunes'=>'',
									  'promo_id'=>'',
									  'rss_audio'=>'',
									  'subtitle'=>'',
									  'description'=>'Geek­O­Rama est un podcast qui rend hommage à la musique de jeux vidéos et son univers en général. Nous parlons des événements qui sont liés aux jeux selon la musique, nous critiquons des albums et nous prenons également vos suggestions de musique.',
									  'with'=>'Dominic Jean et Daniel Vaillancourt'),
					'jouelejeu'=>array('id'=>1194,
	                                  'code'=>'jouelejeu',
	                                  'name'=>'Je Joue Le Jeu',
									  'url'=>'',
									  'show_link'=>'je-joue-le-jeu',
									  'twitter'=>'https://twitter.com/jejouelejeu',
									  'facebook'=>'https://www.facebook.com/pages/Je-joue-le-jeu/1376915515878976',
									  'itunes'=>'',
									  'promo_id'=>'aS6IEl6ZksM',
									  'rss_audio'=>'',
									  'subtitle'=>'',
									  'description'=>'Il y a les testeurs rétro fâchés ...et puis il y a Guillaume. Lui, il est plutôt joyeux, poli et il aime bien partager sa passion des jeux rétro importés et autres obscurs amusements. Constamment en quête de la cassette ou de la console dont personne n\'a encore entendu parler, il y a de tout pour tous dans un épisode de Je Joue Le Jeu!',
									  'with'=>'Guillaume Couture'),
					'e4c'=>array('id'=>1196,
	                                  'code'=>'e4c',
	                                  'name'=>'Lerreur 400 Cast',
									  'url'=>'http://lerreur400cast.tumblr.com',
									  'show_link'=>'lerreur-400-cast',
									  'twitter'=>'https://twitter.com/lerreur400cast',
									  'facebook'=>'https://www.facebook.com/pages/Lerreur-400-cast/280618138682920?fref=ts',
									  'itunes'=>'https://itunes.apple.com/ca/podcast/lerreur-400-cast/id517873238',
									  'promo_id'=>'',
									  'rss_audio'=>'',
									  'subtitle'=>'',
									  'description'=>'Le podcast où on jase culture avec un petit «c». Tout y passe: la musique, le cinéma, la télé, la techno, les jeux vidéos, et encore plus. L\'objectif est donner notre opinion et de discuter de ce qui nous allume, le tout avec humour et déconnage. Ce faisant, nous espérons permettre aux auditeurs de découvrir de nouveaux produits culturels.',
									  'with'=>'Antonin Besner, Nicolas Bertrand-Verge, Mario Daoust, Étienne Lavoie-Robert, Cloé Lapan-Vandal, Marie-Ève Taillefer et Benoit Marquette'),
					'pointdevues'=>array('id'=>1197,
	                                  'code'=>'pointdevues',
	                                  'name'=>'Point De Vues',
									  'url'=>'http://pointdevues.net/',
									  'show_link'=>'point-de-vues',
									  'twitter'=>'http://twitter.com/Pointdevues',
									  'facebook'=>'http://www.facebook.com/pointdevues',
									  'itunes'=>'https://itunes.apple.com/us/podcast/point-de-vues/id509414357?l=fr&mt=2&ign-mpt=uo%3D4',
									  'promo_id'=>'',
									  'rss_audio'=>'',
									  'subtitle'=>'',
									  'description'=>'À chaque semaine, l\'animateur Paul Landriau et ses collègues Pascal Plante et Rémi Fréchette discutent de l\'actualité cinéma à Montréal. Sorties en salles, festivals, actualité et rétrospective, Point de vues est un coup d\'oeil et une discussion franche sur le 7e art.',
									  'with'=>'Paul Landriau, Rémi Fréchette et Pascal Plante'),
					'souperdefilles'=>array('id'=>1198,
	                                  'code'=>'souperdefilles',
	                                  'name'=>'Souper De Filles',
									  'url'=>'http://www.webetmascara.ca/category/souper-de-filles/',
									  'show_link'=>'souper-de-filles',
									  'twitter'=>'https://twitter.com/SouperDeFilles_',
									  'facebook'=>'https://www.facebook.com/podcastsouperdefilles',
									  'itunes'=>'',
									  'promo_id'=>'',
									  'rss_audio'=>'',
									  'subtitle'=>'Le premier podcast de filles du Québec',
									  'description'=>'Souper de Filles est un podcast où on répond à vos demandes de conseils, d\'opinions et de trucs pratiques pour votre vie personnelle. Les psychologues des pauvres? Les "Janette Bertrand" du futur? Les "Manuel Hurtubise" d\'aujourd\'hui? Oui, elles sont toutes ça en même temps.',
									  'with'=>'Gabrielle Caron et Lyne Bouthillette'),
					'testeuralpha'=>array('id'=>1190,
	                                  'code'=>'testeuralpha',
	                                  'name'=>'Testeur Alpha',
									  'url'=>'http://www.testeuralpha.com',
									  'show_link'=>'testeur-alpha',
									  'twitter'=>'https://twitter.com/TesteurAlpha',
									  'facebook'=>'https://www.facebook.com/TesteurAlphaofficiel',
									  'itunes'=>'',
									  'promo_id'=>'2uRBTpTwAZI',
									  'rss_audio'=>'',
									  'subtitle'=>'',
									  'description'=>'Testeur Alpha est une émission dans laquelle Alpha, et son associé méprisé Beta, jouent à des jeux rétros allant de l’Atari au Playstation 1. Alpha critique sévèrement le jeu, le tout dans un univers absurde et très humoristique. Alpha est un geek rétro et grand amateur de métal. Le mélange de son caractère arrogant avec son humour particulier suffit à créer des scènes bien divertissantes. De son côté, Beta le souffre-douleur, essaie tant bien que mal de faire rire les gens grâce à ses «Chroniques Calembour», mais sans succès. De plus, la relation tendue entre Alpha et Beta est un grand facteur hilarant.',
									  'with'=>''));
	
	if($code != '')
	{
		return $shows[$code];
	}
	else
	{
		return $shows;
	}
}

function getMonthInFrench($month)
{
	$months= array('January'=>'Janvier',
	               'February'=>'Février',
				   'March'=>'Mars',
				   'April'=>'Avril',
				   'May'=>'Mai',
				   'June','Juin',
				   'July','Juillet',
				   'August'=>'Août',
				   'September'=>'Septembre',
				   'October'=>'Octobre',
				   'November'=>'Novembre',
				   'December'=>'Décembre');
				   
	return $months[$month];
}
?>
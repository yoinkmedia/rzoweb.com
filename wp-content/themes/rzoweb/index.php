<?php require_once('header.php'); ?>

<?php
$episodes= get_homepage_banners();
?>
	
<div class="scrollable" id="scrollable">
     <div class="items">
     	<div>
        	<a href="<?php bloginfo('url'); ?>/a-propos"><img src="<?php bloginfo('template_directory'); ?>/images/homepage/banner_rzo.jpg" alt="" /></a>
        </div>
        <?php foreach($episodes as $episode): ?>
		<?php $post=$episode; setup_postdata($post); ?>
        <?php $infos= getShowInfosByEpisode($episode); ?>
        <?php 
			$url = wp_get_attachment_url( get_post_thumbnail_id($episode->ID) ); 
			if(!$url){$url= get_bloginfo('template_directory') . '/images/banners/' . $infos['code'] . '.jpg';}
		?>
        <div onclick="openPopup('<?php bloginfo('url'); ?>',<?php echo $episode->ID; ?>);_gaq.push(['_trackEvent', 'Homepage Banner', 'Episode', '<?php echo $infos['name'] ?> - <?php the_title(); ?>']);" style="background-image:url('<?php echo $url; ?>')">
        	<div class="slide_infos">
            	<div class="show_name">
				    <span class="name"><?php echo $infos['name']; ?></span>
					<br/>
					<span class="title"><?php the_title(); ?></span>
                </div>
            </div>
        </div>
		<?php endforeach; ?>
    </div>
    <div class="navi"></div>
</div>

<div id="last_episodes">
    <div id="title"><img src="<?php bloginfo('template_directory'); ?>/images/homepage/title_last_episodes.png" alt="" /></div>
    <div id="episodes">
    	<?php require('old_episodes.php'); ?>
    </div>
</div>

<div id="shows">
    <div id="title"><img src="<?php bloginfo('template_directory'); ?>/images/homepage/title_shows.png" alt="" /></div>
    <div id="shows_logos">
    	<?php $shows= getShowInfosByCode(); shuffle($shows); ?>
        
        <?php $i=1; foreach($shows as $show): ?>
        <div <?php if($i<4): ?>style="margin-top:13px;"<?php endif; ?> <?php if(($i%3)==0): ?>class="last"<?php endif; ?>><a href="<?php bloginfo('url') ?>/show/<?php echo $show['show_link']; ?>" onclick="_gaq.push(['_trackEvent', 'Homepage', 'Show', '<?php echo $show['name'] ?>']);"><img src="<?php bloginfo('template_directory'); ?>/images/logos/<?php echo $show['code'] ?>.jpg" alt="<?php echo $show['name'] ?>" /></a></div>
        <?php $i++; endforeach; ?>
        
        <!--<div style="margin-top:13px;"><a href="<?php bloginfo('url') ?>/show/les-mysterieux-etonnants" onclick="_gaq.push(['_trackEvent', 'Homepage', 'Show', 'Les Mysterieux Etonnants']);"><img src="<?php bloginfo('template_directory'); ?>/images/logos/mysterieux.jpg" alt="Les Mysterieux Etonnants" /></a></div>
    	<div style="margin-top:13px;"><a href="<?php bloginfo('url') ?>/show/retro-nouveau"  onclick="_gaq.push(['_trackEvent', 'Homepage', 'Show', 'Retro Nouveau']);"><img src="<?php bloginfo('template_directory'); ?>/images/logos/retronouveau.jpg" alt="Retro Nouveau" /></a></div>
    	<div style="margin-top:13px;" class="last"><a href="<?php bloginfo('url') ?>/show/le-dernier-des-podcasts" onclick="_gaq.push(['_trackEvent', 'Homepage', 'Show', 'Le Dernier Des Podcasts']);"><img src="<?php bloginfo('template_directory'); ?>/images/logos/dernierpodcasts.jpg" alt="Le Dernier Des Podcasts" /></a></div>
    	<div><a href="<?php bloginfo('url') ?>/show/la-soiree-du-podcast" onclick="_gaq.push(['_trackEvent', 'Homepage', 'Show', 'La Soiree Du Podcast']);"><img src="<?php bloginfo('template_directory'); ?>/images/logos/lsdp.jpg" alt="La Soiree Du Podcast" /></a></div>
    	<div><a href="<?php bloginfo('url') ?>/show/3-bieres" onclick="_gaq.push(['_trackEvent', 'Homepage', 'Show', '3 Bieres']);"><img src="<?php bloginfo('template_directory'); ?>/images/logos/3bieres.jpg" alt="3 Bieres" /></a></div>
    	<div class="last"><a href="<?php bloginfo('url') ?>/show/pod-probleme" onclick="_gaq.push(['_trackEvent', 'Homepage', 'Show', 'Pod Probleme']);"><img src="<?php bloginfo('template_directory'); ?>/images/logos/podprobleme.jpg" alt="Pod Probleme" /></a></div>
    	<div><a href="<?php bloginfo('url') ?>/show/boulevard-brutal" onclick="_gaq.push(['_trackEvent', 'Homepage', 'Show', 'Boulevard Brutal']);"><img src="<?php bloginfo('template_directory'); ?>/images/logos/boulbrutal.jpg" alt="Boulevard Brutal" /></a></div>
        <div><a href="<?php bloginfo('url') ?>/show/la-commission-des-geekeurs" onclick="_gaq.push(['_trackEvent', 'Homepage', 'Show', 'La Commission Des Geekeurs']);"><img src="<?php bloginfo('template_directory'); ?>/images/logos/geekeurs.jpg" alt="" /></a></div>
        <div class="last"><a href="<?php bloginfo('url') ?>/show/le-shack-a-boisson" onclick="_gaq.push(['_trackEvent', 'Homepage', 'Show', 'Le Shack A Boisson']);"><img src="<?php bloginfo('template_directory'); ?>/images/logos/shack.jpg" alt="Le Shack A Boisson" /></a></div>
    	<div><a href="<?php bloginfo('url') ?>/show/horreur-gamer" onclick="_gaq.push(['_trackEvent', 'Homepage', 'Show', 'Horreur Gamer']);"><img src="<?php bloginfo('template_directory'); ?>/images/logos/horreur.jpg" alt="Horreur Gamer" /></a></div>
    	<div><a href="<?php bloginfo('url') ?>/show/lepee-legendaire" onclick="_gaq.push(['_trackEvent', 'Homepage', 'Show', 'Lepee Legendaire']);"><img src="<?php bloginfo('template_directory'); ?>/images/logos/epee.jpg" alt="Epee Legendaire" /></a></div>
    	<div class="last"><a href="<?php bloginfo('url') ?>/show/fun-regarder-films" onclick="_gaq.push(['_trackEvent', 'Homepage', 'Show', 'Fun-Regarder-Films']);"><img src="<?php bloginfo('template_directory'); ?>/images/logos/frf.jpg" alt="Fun-Regarder-Film" /></a></div>
    	<div><a href="<?php bloginfo('url') ?>/show/objectif-numerique" onclick="_gaq.push(['_trackEvent', 'Homepage', 'Show', 'Objectif Numerique']);"><img src="<?php bloginfo('template_directory'); ?>/images/logos/objnumerique.jpg" alt="Objectif Numerique" /></a></div>
        <div><a href="<?php bloginfo('url') ?>/show/macquebec" onclick="_gaq.push(['_trackEvent', 'Homepage', 'Show', 'MacQuebec']);"><img src="<?php bloginfo('template_directory'); ?>/images/logos/macquebec.jpg" alt="Mac Quebec" /></a></div>-->
    	<div><a href="<?php bloginfo('url') ?>/contact" onclick="_gaq.push(['_trackEvent', 'Homepage', 'Show', 'Soumettre Votre Show Button']);"><img src="<?php bloginfo('template_directory'); ?>/images/logos/soumettre.jpg" alt="" /></a></div>
        <div class="last"><a href="<?php bloginfo('url') ?>/contact" onclick="_gaq.push(['_trackEvent', 'Homepage', 'Show', 'Soumettre Votre Show Button']);"><img src="<?php bloginfo('template_directory'); ?>/images/logos/soumettre.jpg" alt="" /></a></div>
    </div>
</div>

<!--
<div id="blogue">
    <div id="title"><img src="<?php //bloginfo('template_directory'); ?>/images/homepage/title_blogue.png" alt="" /></div>
    <div style="margin-top:14px;padding-bottom:30px;"><img src="<?php //bloginfo('template_directory'); ?>/images/homepage/blogue_content_temp.jpg" alt="" /></div>
</div>
-->

<?php require_once('footer.php'); ?>
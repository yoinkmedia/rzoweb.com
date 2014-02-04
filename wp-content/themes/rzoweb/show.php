<?php
/**
* Template Name: Show
*/
?>

<?php
$uri = $_SERVER['REQUEST_URI'];
$show_name = end(explode('/',substr($uri,0,-1)));

$show = getShowByName($show_name);
$hasAudioAndVideo= showHasAudioAndVideo($show['id']);
if($hasAudioAndVideo)
{
	$audioEpisodes = getAudioEpisodesByCategory($show['id']);
	$videoEpisodes = getVideoEpisodesByCategory($show['id']);
}
else
{
	$episodes = getEpisodesByCategory($show['id']);
}
?>

<?php require_once('header.php'); ?>

<div id="show">
	<div id="show_banner"><img src="<?php bloginfo('template_directory'); ?>/images/banners/<?php echo $show['code']; ?>.jpg" alt="<?php echo $show['name']; ?>" /></div>
    
    <div id="show_infos">
    	<div id="promo">
        	<h2>BANDE-ANNONCE DE L'&Eacute;MISSION</h2>
            <iframe width="393" height="221" src="//www.youtube.com/embed/<?php echo $show['promo_id'] ?>?rel=0&wmode=transparent" frameborder="0" allowfullscreen></iframe>
        </div>
        <div id="description">
        	<div id="title"><?php echo $show['name']; ?></div>
            <div id="subtitle"><?php echo $show['subtitle']; ?></div>
            <div id="team">Avec: <?php echo $show['with']; ?></div>
            <div id="text"><?php echo $show['description']; ?></div>
            <div id="logo_socials">
            	<?php if($show['url']): ?><button class="site" onclick="window.open('<?php echo $show['url'] ?>','_blank')">SITE OFFICIEL</button><?php endif; ?>
            	<img src="<?php bloginfo('template_directory'); ?>/images/show/icon_fb.png" onclick="window.open('<?php echo $show['facebook'] ?>','_blank')" style="cursor:pointer;" alt="" />
            	<img src="<?php bloginfo('template_directory'); ?>/images/show/icon_twitter.png" onclick="window.open('<?php echo $show['twitter'] ?>','_blank')" style="margin-left:6px;cursor:pointer;" alt="" />
                <?php if(@$show['rss_audio']): ?>
                <img src="<?php bloginfo('template_directory'); ?>/images/header/icon_rss.png" onclick="window.open('<?php echo $show['rss_audio']; ?>','_blank')" style="margin-left:6px;cursor:pointer;" alt="" />
                <?php elseif(@$show['rss_video']): ?>
                <img src="<?php bloginfo('template_directory'); ?>/images/header/icon_rss.png" onclick="window.open('<?php echo $show['rss_video']; ?>','_blank')" style="margin-left:6px;cursor:pointer;" alt="" />
                <?php else: ?>
                <img src="<?php bloginfo('template_directory'); ?>/images/header/icon_rss.png" onclick="window.open('<?php bloginfo('url') ?>/?cat=<?php echo $show['id'] ?>&feed=rss2','_blank')" style="margin-left:6px;cursor:pointer;" alt="" />				
				<?php endif; ?>
                <?php if(@$show['itunes']): ?><img src="<?php bloginfo('template_directory'); ?>/images/show/icon_itunes.png" onclick="window.open('<?php echo $show['itunes'] ?>','_blank')" style="margin-left:6px;cursor:pointer;" alt="" /><?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php if($hasAudioAndVideo): ?>
    	<div style="background-image:url('<?php bloginfo('template_directory') ?>/images/show/title_emissions.png');height:28px;width:873px;padding-left:110px;">
        	<a class="showtype" href="#" onclick="$('#audio_episodes').css('display','block');$('#video_episodes').css('display','none');" style="margin-right:6px;">AUDIO</a>
        	<a class="showtype" href="#" onclick="$('#audio_episodes').css('display','none');$('#video_episodes').css('display','block');">VIDEO</a>
         </div>
        
        <div id="audio_episodes">
        <?php $i=1; foreach($audioEpisodes as $post): ?>
		<?php setup_postdata($post); ?>
        <?php
            $cats= wp_get_post_categories($post->ID);
            if(in_array(11,$cats)){
                $video_id = get_post_meta( $post->ID, 'video_id' );
                $video_id = $video_id[0];
                $type= 'video';
            }
			elseif(in_array(1158,$cats)){
			$video_id = get_post_meta( $post->ID, 'video_id' );
			$video_id = $video_id[0];
			$type= 'vimeo';
		}
            else{
                $audio_link = get_post_meta( $post->ID, 'audio_link' );
                $audio_link = $audio_link[0];
                $type= 'audio';
            }
        ?>
        <div class="episode" <?php if($i%4==0):?>style="margin-right:0;"<?php endif; ?>>
            <div class="title"><?php the_title(); ?></div>
            <div class="date"><?php echo get_the_date('d') . ' ' . getMonthInFrench(get_the_date('F')) . '&nbsp;&nbsp;' . get_the_date('Y'); ?></div>
            <div class="description"><?php echo substr(get_the_excerpt(),0,200); if(strlen(get_the_excerpt())>200): echo '...'; endif; ?></div>
            <button class="ecouter" onclick="_gaq.push(['_trackEvent', 'Episode', '<?php echo $show['name']; ?>', '<?php the_title(); ?>']);openPopup('<?php bloginfo('url'); ?>',<?php echo $post->ID; ?>);">ÉCOUTER</button>
            <?php if($type == 'audio'): ?>
            <a class="telecharger" href="<?php echo $audio_link;  ?>" onclick="_gaq.push(['_trackEvent', 'Download', '<?php echo $show['name']; ?>', '<?php the_title(); ?>']);">TÉLÉCHARGER</a>
            <?php endif; ?>
        </div>
        <?php $i++; endforeach; ?>
        </div>
        <div id="video_episodes" style="display:none;">
        <?php $j=1; foreach($videoEpisodes as $post): ?>
		<?php setup_postdata($post); ?>
        <?php
            $cats= wp_get_post_categories($post->ID);
            if(in_array(11,$cats)){
                $video_id = get_post_meta( $post->ID, 'video_id' );
                $video_id = $video_id[0];
                $type= 'video';
            }
			elseif(in_array(1158,$cats)){
				$video_id = get_post_meta( $post->ID, 'video_id' );
				$video_id = $video_id[0];
				$type= 'vimeo';
			}
            else{
                $audio_link = get_post_meta( $post->ID, 'audio_link' );
                $audio_link = $audio_link[0];
                $type= 'audio';
            }
        ?>
        <div class="episode" <?php if($i%4==0):?>style="margin-right:0;"<?php endif; ?>>
            <div class="title"><?php the_title(); ?></div>
            <div class="description"><?php echo substr(get_the_excerpt(),0,150); if(strlen(get_the_excerpt())>150): echo '...'; endif; ?></div>
            <button class="ecouter" onclick="_gaq.push(['_trackEvent', 'Episode', '<?php echo $show['name']; ?>', '<?php the_title(); ?>']);openPopup('<?php bloginfo('url'); ?>',<?php echo $post->ID; ?>);">ÉCOUTER</button>
            <?php if($type == 'audio'): ?>
            <a class="telecharger" href="<?php echo $audio_link;  ?>" onclick="_gaq.push(['_trackEvent', 'Download', '<?php echo $show['name']; ?>', '<?php the_title(); ?>']);">TÉLÉCHARGER</a>
            <?php endif; ?>
        </div>
        <?php $j++; endforeach; ?>
        </div>
    <?php else: ?>
    <div><img src="<?php bloginfo('template_directory') ?>/images/show/title_emissions.png" alt="" /></div>
    
    <?php $i=1; foreach($episodes as $post): ?>
    <?php setup_postdata($post); ?>
    <?php
    	$cats= wp_get_post_categories($post->ID);
		if(in_array(11,$cats)){
			$video_id = get_post_meta( $post->ID, 'video_id' );
			$video_id = $video_id[0];
			$type= 'video';
		}
		elseif(in_array(1158,$cats)){
			$video_id = get_post_meta( $post->ID, 'video_id' );
			$video_id = $video_id[0];
			$type= 'vimeo';
		}
		else{
			$audio_link = get_post_meta( $post->ID, 'audio_link' );
			$audio_link = $audio_link[0];
			$type= 'audio';
		}
	?>
    <div class="episode" <?php if($i%4==0):?>style="margin-right:0;"<?php endif; ?>>
    	<div class="title"><?php the_title(); ?></div>
        <div class="description"><?php echo substr(get_the_excerpt(),0,150); if(strlen(get_the_excerpt())>150): echo '...'; endif; ?></div>
        <button class="ecouter" onclick="_gaq.push(['_trackEvent', 'Episode', '<?php echo $show['name']; ?>', '<?php the_title(); ?>']);openPopup('<?php bloginfo('url'); ?>',<?php echo $post->ID; ?>);">ÉCOUTER</button>
        <?php if($type == 'audio'): ?>
        <a class="telecharger" href="<?php echo $audio_link;  ?>" onclick="_gaq.push(['_trackEvent', 'Download', '<?php echo $show['name']; ?>', '<?php the_title(); ?>']);">TÉLÉCHARGER</a>
    	<?php endif; ?>
    </div>
	<?php $i++; endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once('footer.php'); ?>
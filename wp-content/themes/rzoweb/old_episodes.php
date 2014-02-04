<?php
$published_posts = wp_count_posts()->publish;
$of = ceil($published_posts/8);
$current_page = 1;

$posts = get_posts(array('category'=>'27,6,8,25,24,28,7,5,26,3,22,23,21,4,1191,1195,1192,1194,1196,1197,1198,1190','numberposts'=>10));
?>
<div style="display:none;" id="current_page"><?php echo $current_page; ?></div>
<ul id="old_episodes">
<script type="text/javascript">
var nbPages = <?php echo $of; ?>;
</script>

<?php foreach($posts as $post): ?>
	<?php 
		setup_postdata($post); 
	
		$infos= getShowInfosByEpisode($post);
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
    
    <li class="<?php echo $type; ?>" onclick="_gaq.push(['_trackEvent', 'Left Widget', 'Episode', '<?php echo $infos['name'] ?> - <?php the_title(); ?>']);openPopup('<?php bloginfo('url'); ?>',<?php echo $post->ID; ?>);">
		<div class="name"><?php echo $infos['name'] ?></div>
        <div class="title"><?php echo substr(get_the_title(),0,31); if(strlen(get_the_title())>31): echo '...'; endif; ?></div>
    </li>
<?php endforeach; ?>
	<div style="position:absolute;margin-top:-265px;margin-left:85px;display:none;" id="ajax_loader"><img src="<?php bloginfo('template_directory'); ?>/images/ajax_loader.gif" height="80" alt="" /></div>
</ul>
<div id="old_ep_nav">
	<div id="prev" onclick="reloadEpisodesList('prev',<?php echo $current_page; ?>,'<?php bloginfo('url'); ?>');"><img src="<?php bloginfo('template_directory'); ?>/images/widget/shows/btn_prev.png" alt="" /></div>
    <div id="count"><div id="box"><span id="current_page_container"><?php echo $page; ?></span> DE <span id="nb_pages" ng-pages="<?php echo $of; ?>"><?php echo $of; ?></span></div></div>
	<div id="next" onclick="reloadEpisodesList('next',<?php echo $current_page; ?>,'<?php bloginfo('url'); ?>');"><img src="<?php bloginfo('template_directory'); ?>/images/widget/shows/btn_next.png" alt="" /></div>
</div>
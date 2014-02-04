<?php
/**
* Template Name: Reload This Show
*/
?>

<?php
$cat_id= $_POST['cat_id'];
$page= 0;

$posts = get_posts(array('category'=>$cat_id,'numberposts'=>9,'offset'=>($page*8),'orderby'=>'post_date','order'=>'DESC'));
$all_posts = get_posts(array('category'=>$cat_id,'numberposts'=>1000,'orderby'=>'post_date','order'=>'DESC'));
$posts_count = count($all_posts);

$of = ceil($posts_count/8);
$current_page_popup = 1;
?>
<div style="display:none;" id="current_page_popup"><?php echo $current_page; ?></div>
<div>
	<img src="<?php bloginfo('template_directory'); ?>/images/popup/this_show_on.png" style="margin-right:5px;cursor:pointer;" onclick="loadThisShowEpisodes('<?php bloginfo('url'); ?>','<?php echo $cat_id; ?>')" alt="" />
    <img src="<?php bloginfo('template_directory'); ?>/images/popup/all_shows_off.png" onclick="reloadAllShow('<?php bloginfo('url'); ?>');" style="cursor:pointer;" alt="" />
</div>
<ul id="old_episodes_popup">
<script type="text/javascript">
var nbPagesPopup = <?php echo $of; ?>;
</script>

<?php
foreach($posts as $post):
?>
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
    
    <li class="<?php echo $type; ?>" onclick="_gaq.push(['_trackEvent', 'Popup Widget This Show', 'Episode', '<?php echo $infos['name']; ?> - <?php the_title(); ?>']);openPopup('<?php bloginfo('url'); ?>',<?php echo $post->ID; ?>);">
		<div class="name"><?php echo $infos['name'] ?></div>
        <div class="title"><?php echo substr(get_the_title(),0,35); if(strlen(get_the_title())>35): echo '...'; endif; ?></div>
    </li>
<?php endforeach; ?>
	<div style="position:absolute;margin-top:-265px;margin-left:85px;display:none;" id="ajax_loader_popup"><img src="<?php bloginfo('template_directory'); ?>/images/ajax_loader.gif" height="80" alt="" /></div>
</ul>
<div id="old_ep_nav_popup">
	<div id="prev" <?php if($of!=1): ?>onclick="reloadThisEpisodesListPopup('prev',<?php echo $current_page_popup; ?>,'<?php bloginfo('url'); ?>',<?php echo $cat_id; ?>);"<?php endif; ?>><img src="<?php bloginfo('template_directory'); ?>/images/widget/shows/btn_prev.png" alt="" /></div>
    <div id="count"><div id="box"><span id="current_page_container_popup"><?php echo $page; ?></span> DE <span id="nb_pages" ng-pages="<?php echo $of; ?>"><?php echo $of; ?></span></div></div>
	<div id="next" <?php if($of!=1): ?>onclick="reloadThisEpisodesListPopup('next',<?php echo $current_page_popup; ?>,'<?php bloginfo('url'); ?>',<?php echo $cat_id; ?>);"<?php endif; ?>><img src="<?php bloginfo('template_directory'); ?>/images/widget/shows/btn_next.png" alt="" /></div>
</div>
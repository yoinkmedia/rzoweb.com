<?php require_once('header.php'); ?>
<?php while ( have_posts() ) : the_post(); ?>
<?php 
	$cat= get_the_category();
	$cats= array();
	$postID= $post->ID;

	foreach($cat as $c){
		array_push($cats,$c->cat_ID);
	}
	$show= getShowByCategories($cats); 
	if(in_array(11,$cats)){
		require_once('single_video.php');
		exit;	
	}
	elseif(in_array(1158,$cats)){
		require_once('single_vimeo.php');
		exit;	
	}
	else{
		require_once('single_audio.php');
		exit;
	}
?>
<?php endwhile; ?>
<div style="float:left">
<?php require_once('footer.php'); ?>
</div>
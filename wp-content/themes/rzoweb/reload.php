<?php
/**
* Template Name: Reload
*/
?>

<?php
$page= $_POST['currentPage'];
$direction= $_POST['direction'];
$nbPages = $_POST['nbPages'];

if($direction == 'prev')
{
	$page = $page - 2;
	if($page<0){$page= $nbPages-1;}
}
elseif($direction == 'next')
{
	if($page == $nbPages)
	{$page= 0;}
}

$posts = get_posts(array('category'=>'27,6,8,25,24,28,7,5,26,3,22,23,21,4','numberposts'=>10,'offset'=>($page*8),'orderby'=>'post_date','order'=>'DESC'));

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
    
    <li class="<?php echo $type; ?>" onclick="openPopup('<?php bloginfo('url'); ?>',<?php echo $post->ID; ?>);">
		<div class="name"><?php echo $infos['name'] ?></div>
        <div class="title"><?php echo substr(get_the_title(),0,35); if(strlen(get_the_title())>35): echo '...'; endif; ?></div>
    </li>
<?php endforeach; ?>
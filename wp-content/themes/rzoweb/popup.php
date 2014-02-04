<?php
/**
* Template Name: Popup
*/
?>

<?php 
	$postID= $_GET['id'];
	if(!$postID){die('Error. No ID');}
	
	$post = get_post($postID);
	setup_postdata($post);
	$infos= getShowInfosByEpisode($post);
	
	$cats= wp_get_post_categories($postID);
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

<style>
#popup{background-color:#565656;border:none;padding:0;margin:0;width:922px;height:585px;}
#top{height:96px;}
</style>

<div id="popup">
	<div id="top" style="margin-bottom:10px;">
    	<img src="<?php bloginfo('template_directory'); ?>/images/popup/logo.png" alt="">
        <a href="http://www.toyotaquebec.ca/fr/corolla-ce-2014" target="_blank"><img src="<?php bloginfo('template_directory'); ?>/images/pub/LB_Corolla.jpg" alt="" /></a>
    </div>
    <div style="float:left;width:649px;height:489px;background-color:#565656;padding-left:4px;">
    	<?php if($type == 'video'): ?>
    	<iframe width="640" height="390" src="//www.youtube.com/embed/<?php echo $video_id; ?>?rel=0&showinfo=0" frameborder="0" border="0" allowfullscreen></iframe>
        <?php elseif($type == 'vimeo'): ?>
        <iframe src="//player.vimeo.com/video/<?php echo $video_id; ?>?badge=0" width="640" height="390" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
    	<?php else: ?>
        <div style="float:left;width:640px;height:390px;background-image:url('<?php bloginfo('template_directory'); ?>/images/audioplayer/background/<?php echo $infos['code'] ?>.jpg');">
            <div id="audio_player">
                <div id="audio">
                    <!--
                    <audio style="width:550px; margin: 0 auto; display:block;" controls>
                      <source src="<?php echo $audio_link; ?>" type="audio/mpeg">
                    </audio>-->
                    <audio id="audio_with_controls"  style="width:550px; margin: 0 auto; display:block;" controls>
                        <source src="<?php echo $audio_link; ?>" type="audio/mpeg" />
                        <object class="playerpreview" type="application/x-shockwave-flash" data="http://www.html5rocks.com/en/tutorials/audio/quick/player_mp3_mini.swf" width="200" height="20" style="padding:0;">
                          <param name="movie" value="http://www.html5rocks.com/en/tutorials/audio/quick/player_mp3_mini.swf" />
                          <param name="bgcolor" value="#085c68" />
                          <param name="FlashVars" value="mp3=<?php echo $audio_link; ?>" />
                          <embed href="http://www.html5rocks.com/en/tutorials/audio/quick/player_mp3_mini.swf" bgcolor="#085c68" width="200" height="20" name="movie" align="" type="application/x-shockwave-flash" flashvars="mp3=<?php echo $audio_link; ?>" />
                        </object>
                      </audio>
                    <div id="default_player_fallback"></div>
                
                    <script>
                      if (document.createElement('audio').canPlayType) {
                        if (!document.createElement('audio').canPlayType('audio/mpeg')) {
                          swfobject.embedSWF("http://www.html5rocks.com/en/tutorials/audio/quick/player_mp3_mini.swf",
                                             "default_player_fallback", "200", "20", "9.0.0", "",
                                             {"mp3":"<?php echo $audio_link; ?>"},
                                             {"bgcolor":"#085c68"}
                                            );
                          swfobject.embedSWF("http://www.html5rocks.com/en/tutorials/audio/quick/player_mp3_mini.swf",
                                             "custom_player_fallback", "200", "20", "9.0.0", "",
                                             {"mp3":"<?php echo $audio_link; ?>"},
                                             {"bgcolor":"#085c68"}
                                            );
                          //document.getElementById('audio_with_controls').style.display = 'none';
                        } else {
                          // HTML5 audio + mp3 support
                          //document.getElementById('player').style.display = 'block';
                        }
                      }
                    </script>                    
                </div>
                <a href="<?php echo $audio_link; ?>" id="download">T&eacute;l&eacute;charger l'&eacute;pisode</a>
            </div>
        </div>
        <?php endif; ?>
    	<div id="infos">
        	<div id="title"><?php the_title(); ?></div>
            <div id="text"><?php the_content(); ?></div>
        </div>
        <div id="socials">
        	<div class="socials_btn" onclick="location.href='<?php bloginfo('url'); ?>/<?php echo $infos['show_link'] ?>'">Page de l'émission</div>
            <?php if($infos['url']): ?><div class="socials_btn" onclick="window.open('<?php echo $infos['url'] ?>');">Site Officiel</div><?php endif; ?>
            
            <div id="logo_socials">
            	<div class="socials_btn" onclick="window.open('<?php echo $infos['facebook'] ?>');">Facebook officiel</div>
            	<div class="socials_btn" onclick="window.open('<?php echo $infos['twitter'] ?>');">Twitter officiel</div>
				<div>
                	<span class='st_facebook_large' displayText='Facebook'></span>
                	<span class='st_twitter_large' displayText='Tweet'></span>
                	<span class='st_email_large' displayText='Email'></span>
            	</div>
            </div>
            </div>
        </div>
    <div style="width:265px;float:left;margin-left:4px;" id="old_episodes_popup_container">
    	<?php $cat_id = $infos['id']; require('old_episodes_popup.php'); ?>
    </div>
    </div>
</div>
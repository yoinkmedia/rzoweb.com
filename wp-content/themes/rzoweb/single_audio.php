<div id="show" style="margin-left: 24px;height:650px;">
	<!--<div id="show_banner"><img src="<?php bloginfo('template_directory'); ?>/images/banners/<?php echo $show['code']; ?>.jpg" alt="<?php echo $show['name']; ?>" /></div>-->
	<div id="episodes" style="float:left;margin-top:30px;">
    	<?php require('old_episodes.php'); ?>
    </div>
    <div style="float:left;margin-top:30px;margin-left:30px;">
        <div style="float:left;width:649px;height:489px;background-color:#565656;padding-left:4px;">
    	
        <div style="float:left;width:640px;height:390px;background-image:url('<?php bloginfo('template_directory'); ?>/images/audioplayer/background/<?php echo $show['code'] ?>.jpg');">
            <div id="audio_player">
                <div id="audio">
                    <audio id="audio_with_controls"  style="width:550px; margin: 0 auto; display:block;" controls>
                        <source src="<?php echo get_post_meta($postID, "audio_link", true) ?>" type="audio/mpeg" />
                        <object class="playerpreview" type="application/x-shockwave-flash" data="http://www.html5rocks.com/en/tutorials/audio/quick/player_mp3_mini.swf" width="200" height="20" style="padding:0;">
                          <param name="movie" value="http://www.html5rocks.com/en/tutorials/audio/quick/player_mp3_mini.swf" />
                          <param name="bgcolor" value="#085c68" />
                          <param name="FlashVars" value="mp3=<?php echo get_post_meta($postID, "audio_link", true) ?>" />
                          <embed href="http://www.html5rocks.com/en/tutorials/audio/quick/player_mp3_mini.swf" bgcolor="#085c68" width="200" height="20" name="movie" align="" type="application/x-shockwave-flash" flashvars="mp3=<?php echo $audio_link; ?>" />
                        </object>
                      </audio>
                    <div id="default_player_fallback"></div>
                
                    <script>
                      if (document.createElement('audio').canPlayType) {
                        if (!document.createElement('audio').canPlayType('audio/mpeg')) {
                          swfobject.embedSWF("http://www.html5rocks.com/en/tutorials/audio/quick/player_mp3_mini.swf",
                                             "default_player_fallback", "200", "20", "9.0.0", "",
                                             {"mp3":"<?php echo get_post_meta($postID, "audio_link", true) ?>"},
                                             {"bgcolor":"#085c68"}
                                            );
                          swfobject.embedSWF("http://www.html5rocks.com/en/tutorials/audio/quick/player_mp3_mini.swf",
                                             "custom_player_fallback", "200", "20", "9.0.0", "",
                                             {"mp3":"<?php echo get_post_meta($postID, "audio_link", true) ?>"},
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
                <a href="<?php echo get_post_meta($postID, "audio_link", true) ?>" id="download">T&eacute;l&eacute;charger l'&eacute;pisode</a>
            </div>
        </div>
        
    	<div id="infos">
        	<div id="title"><?php echo get_the_title($postID); ?></div>
            <div id="text" style="height:auto;"><?php $content_post = get_post($postID);
$content = $content_post->post_content; echo $content; ?></div>
        </div>
        <div id="socials">
        	<div class="socials_btn" onclick="location.href='<?php bloginfo('url'); ?>/<?php echo $show['show_link'] ?>'">Page de l'émission</div>
            <?php if($infos['url']): ?><div class="socials_btn" onclick="window.open('<?php echo $show['url'] ?>');">Site Officiel</div><?php endif; ?>
            
            <div id="logo_socials">
            	<div class="socials_btn" onclick="window.open('<?php echo $show['facebook'] ?>');">Facebook officiel</div>
            	<div class="socials_btn" onclick="window.open('<?php echo $show['twitter'] ?>');">Twitter officiel</div>
                <iframe
                class="fb-like"
                src="http://www.facebook.com/plugins/like.php?href=<?php echo get_permalink($post->ID) ?>&layout=button_count&amp;show-faces=false&amp;width=450&amp;action=like&amp;font=arial&amp;colorscheme=light"
                scrolling="no"
                frameborder="0"
                allowTransparency="true"
                style="border:none;
                overflow:hidden;
                width:47px;
                height:px">
            </iframe>
            <a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo get_permalink($post->ID) ?>" data-hashtags="RZO">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
            </div>
            </div>
        </div>
    </div>
    </div>
</div>
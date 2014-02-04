<div id="show" style="margin-left: 24px;height:650px;">
	<!--<div id="show_banner"><img src="<?php bloginfo('template_directory'); ?>/images/banners/<?php echo $show['code']; ?>.jpg" alt="<?php echo $show['name']; ?>" /></div>-->
	<div id="episodes" style="float:left;margin-top:30px;">
    	<?php require('old_episodes.php'); ?>
    </div>
    <div style="float:left;margin-top:30px;margin-left:30px;">
        <div style="float:left;width:649px;height:489px;background-color:#565656;padding-left:4px;">
    	<iframe width="640" height="390" src="//www.youtube.com/embed/<?php echo get_post_meta($postID, "video_id", true) ?>?rel=0&showinfo=0" frameborder="0" border="0" allowfullscreen></iframe>
    	
    	<div id="infos">
        	<div id="title"><?php echo get_the_title($postID); ?></div>
            <div id="text" style="height:auto"><?php $content_post = get_post($postID);
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
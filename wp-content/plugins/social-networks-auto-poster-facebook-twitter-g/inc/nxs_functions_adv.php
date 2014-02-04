<?php
//## Format Message
if (!function_exists("nsFormatMessage")) { function nsFormatMessage($msg, $postID, $addURLParams='', $lng=''){ global $ShownAds, $plgn_NS_SNAutoPoster, $nxs_urlLen; $post = get_post($postID); $options = $plgn_NS_SNAutoPoster->nxs_options; 
  if (!empty($options['nxsHTSpace'])) $htS = $options['nxsHTSpace']; else $htS = '';
  // if ($addURLParams=='' && $options['addURLParams']!='') $addURLParams = $options['addURLParams'];
  $msg = stripcslashes($msg); if (isset($ShownAds)) $ShownAdsL = $ShownAds; // $msg = htmlspecialchars(stripcslashes($msg)); 
  $msg = nxs_doSpin($msg);
  if (preg_match('%URL%', $msg)) { $url = get_permalink($postID); if($addURLParams!='') $url .= (strpos($url,'?')!==false?'&':'?').$addURLParams;  $nxs_urlLen = nxs_strLen($url); $msg = str_ireplace("%URL%", $url, $msg);}
  if (preg_match('%MYURL%', $msg)) { $url =  get_post_meta($postID, 'snap_MYURL', true); if($addURLParams!='') $url .= (strpos($url,'?')!==false?'&':'?').$addURLParams;  $nxs_urlLen = nxs_strLen($url); $msg = str_ireplace("%MYURL%", $url, $msg);}
  if (preg_match('%SURL%', $msg)) { $url = get_permalink($postID); if($addURLParams!='') $url .= (strpos($url,'?')!==false?'&':'?').$addURLParams; 
    $url = nxs_mkShortURL($url, $postID); $nxs_urlLen = nxs_strLen($url); $msg = str_ireplace("%SURL%", $url, $msg);
  }
  if (preg_match('%IMG%', $msg)) { $imgURL = nxs_getPostImage($postID); $msg = str_ireplace("%IMG%", $imgURL, $msg); } 
  if (preg_match('%TITLE%', $msg)) { $title = nxs_doQTrans($post->post_title, $lng);  $msg = str_ireplace("%TITLE%", $title, $msg); }                    
  if (preg_match('%FULLTITLE%', $msg)) { $title = apply_filters('the_title', nxs_doQTrans($post->post_title, $lng));  $msg = str_ireplace("%FULLTITLE%", $title, $msg); }                    
  if (preg_match('%STITLE%', $msg)) { $title = nxs_doQTrans($post->post_title, $lng);   $title = substr($title, 0, 115); $msg = str_ireplace("%STITLE%", $title, $msg); }                    
  if (preg_match('%AUTHORNAME%', $msg)) { $aun = $post->post_author;  $aun = get_the_author_meta('display_name', $aun );  $msg = str_ireplace("%AUTHORNAME%", $aun, $msg);}                    
  if (preg_match('%ANNOUNCE%', $msg)) { $postContent = nxs_doQTrans($post->post_content, $lng);     
    $postContent = strip_tags(strip_shortcodes(str_ireplace('<!--more-->', '#####!--more--!#####', str_ireplace("&lt;!--more--&gt;", '<!--more-->', $postContent))));
    if (stripos($postContent, '#####!--more--!#####')!==false) { $postContentEx = explode('#####!--more--!#####',$postContent); $postContent = $postContentEx[0]; }    
      else $postContent = nsTrnc($postContent, $options['anounTagLimit']);  $msg = str_ireplace("%ANNOUNCE%", $postContent, $msg);
  }
  if (preg_match('%TEXT%', $msg)) {      
    if ($post->post_excerpt!="") $excerpt = apply_filters('the_content', nxs_doQTrans($post->post_excerpt, $lng)); else $excerpt= apply_filters('the_content', nxs_doQTrans($post->post_content, $lng));
      $excerpt = nsTrnc(strip_tags(strip_shortcodes($excerpt)), 300, " ", "..."); $msg = str_ireplace("%TEXT%", $excerpt, $msg); 
  }
  if (preg_match('%EXCERPT%', $msg)) {      
    if ($post->post_excerpt!="") $excerpt = apply_filters('the_content', nxs_doQTrans($post->post_excerpt, $lng)); else $excerpt= apply_filters('the_content', nxs_doQTrans($post->post_content, $lng)); 
      $excerpt = nsTrnc(strip_tags(strip_shortcodes($excerpt)), 300, " ", "..."); $msg = str_ireplace("%EXCERPT%", $excerpt, $msg);
  }
  if (preg_match('%RAWEXTEXT%', $msg)) {      
    if ($post->post_excerpt!="") $excerpt = nxs_doQTrans($post->post_excerpt, $lng); else $excerpt= nxs_doQTrans($post->post_content, $lng); 
      $excerpt = nsTrnc(strip_tags(strip_shortcodes($excerpt)), 300, " ", "..."); $msg = str_ireplace("%RAWEXTEXT%", $excerpt, $msg);
  }
  if (preg_match('%RAWEXCERPT%', $msg)) {      
    if ($post->post_excerpt!="") $excerpt = nxs_doQTrans($post->post_excerpt, $lng); else $excerpt= nxs_doQTrans($post->post_content, $lng); 
      $excerpt = nsTrnc(strip_tags(strip_shortcodes($excerpt)), 300, " ", "..."); $msg = str_ireplace("%RAWEXCERPT%", $excerpt, $msg);
  }
  if (preg_match('%RAWEXCERPTHTML%', $msg)) { 
      if ($post->post_excerpt!="") $excerpt = strip_shortcodes(nxs_doQTrans($post->post_excerpt, $lng)); else $excerpt= nsTrnc(strip_tags(strip_shortcodes(nxs_doQTrans($post->post_content, $lng))), 300, " ", "..."); 
       $msg = str_ireplace("%RAWEXCERPTHTML%", $excerpt, $msg);
   }
  if (preg_match('%TAGS%', $msg)) { $t = wp_get_object_terms($postID, 'product_tag'); if ( empty($t) || is_wp_error($pt) || !is_array($t) ) $t = wp_get_post_tags($postID);
    $tggs = array(); foreach ($t as $tagA) {$tggs[] = $tagA->name;} $tags = implode(', ',$tggs); $msg = str_ireplace("%TAGS%", $tags, $msg);
  }
  if (preg_match('%CATS%', $msg)) { $t = wp_get_post_categories($postID); $cats = array();  foreach($t as $c){ $cat = get_category($c); $cats[] = str_ireplace('&','&amp;',$cat->name); } 
          $ctts = implode(', ',$cats); $msg = str_ireplace("%CATS%", $ctts, $msg);
  }
  if (preg_match('%HCATS%', $msg)) { $t = wp_get_post_categories($postID); $cats = array();  
    foreach($t as $c){ $cat = get_category($c);  $cats[] = "#".trim(str_replace(' ',$htS, str_replace('  ', ' ', trim(str_ireplace('&','',str_ireplace('&amp;','',$cat->name)))))); } 
    $ctts = implode(', ',$cats); $msg = str_ireplace("%HCATS%", $ctts, $msg);
  }  
  if (preg_match('%HTAGS%', $msg)) { $t = wp_get_object_terms($postID, 'product_tag'); if ( empty($t) || is_wp_error($pt) || !is_array($t) ) $t = wp_get_post_tags($postID);
    $tggs = array(); foreach ($t as $tagA) {$tggs[] = "#".trim(str_replace(' ', $htS, preg_replace('/[^a-zA-Z0-9\p{L}\p{N}\s]/u', '', trim(ucwords(str_ireplace('&','',str_ireplace('&amp;','',$tagA->name)))))));  } 
    $tags = implode(', ',$tggs); $msg = str_ireplace("%HTAGS%", $tags, $msg);
  } 
  if (preg_match('%CF-[a-zA-Z0-9_]%', $msg)) { $msgA = explode('%CF', $msg); $mout = '';
    foreach ($msgA as $mms) { 
        if (substr($mms, 0, 1)=='-' && stripos($mms, '%')!==false) { $mGr = CutFromTo($mms, '-', '%'); $cfItem =  get_post_meta($postID, $mGr, true);  $mms = str_ireplace("-".$mGr."%", $cfItem, $mms); } $mout .= $mms; 
    } $msg = $mout; 
  }  
  if (preg_match('%FULLTEXT%', $msg)) { $postContent = apply_filters('the_content', nxs_doQTrans($post->post_content, $lng)); $msg = str_ireplace("%FULLTEXT%", $postContent, $msg);}                    
  if (preg_match('%RAWTEXT%', $msg)) { $postContent = nxs_doQTrans($post->post_content, $lng); $msg = str_ireplace("%RAWTEXT%", $postContent, $msg);}
  if (preg_match('%SITENAME%', $msg)) { $siteTitle = htmlspecialchars_decode(get_bloginfo('name'), ENT_QUOTES); $msg = str_ireplace("%SITENAME%", $siteTitle, $msg);}      
  if (isset($ShownAds)) $ShownAds = $ShownAdsL; // FIX for the quick-adsense plugin
  return trim($msg);
}}

if (!function_exists("nxs_get_admin_url")) { function nxs_get_admin_url($path=''){ //## Workaround for some buggy 'admin hiding' plugins.
  $admURL = admin_url($path); if (substr($admURL, 0, 4)!='http') { $admURL = admin_url($path, 'https'); $admURL = str_ireplace('https://', 'http://', $admURL);} return $admURL;
}}
//## Process Spin 
if (!function_exists("nxs_spinRecursion")) { function nxs_spinRecursion(&$txt, $startCh) { global $nxs_spin_lCh, $nxs_spin_rCh, $nxs_spin_splCh; $startPos = $startCh;
  while ($startCh++ < strlen($txt)) {
    if (substr($txt, $startCh, strlen($nxs_spin_lCh)) == $nxs_spin_lCh)  $txt = nxs_spinRecursion($txt, $startCh);
    elseif (substr($txt, $startCh, strlen($nxs_spin_rCh)) == $nxs_spin_rCh) {
      $tmpTxt = substr($txt, $startPos+strlen($nxs_spin_lCh), ($startCh - $startPos)-strlen($nxs_spin_rCh));
      $toRepl = nxs_spinReplace($tmpTxt); $txt = str_replace($nxs_spin_lCh.$tmpTxt.$nxs_spin_rCh, $toRepl, $txt);
    }
  } return $txt;
}}
if (!function_exists("nxs_spinReplace")) { function nxs_spinReplace($txt) { global $nxs_spin_splCh; $txt = explode($nxs_spin_splCh, $txt);  $out = $txt[mt_rand(0,count($txt)-1)]; return $out; }}
if (!function_exists("nxs_doSpin")) { function nxs_doSpin($msg){  global $nxs_spin_lCh, $nxs_spin_rCh, $nxs_spin_splCh;
    $nxs_spin_lCh = '{'; $nxs_spin_rCh='}'; $nxs_spin_splCh='|'; $msg = nxs_spinRecursion($msg, -1); return $msg;
}}

if (!function_exists("nxs_getImgfrOpt")) { function nxs_getImgfrOpt($imgOpts, $defSize=''){ if (!is_array($imgOpts)) return $imgOpts;// prr($imgOpts);
   if ($defSize!='' && isset($imgOpts[$defSize]) && trim($imgOpts[$defSize])!='') return $imgOpts[$defSize];
   if (isset($imgOpts['large']) && trim($imgOpts['large'])!='') return $imgOpts['large'];
   if (isset($imgOpts['original']) && trim($imgOpts['original'])!='') return $imgOpts['original'];
   if (isset($imgOpts['thumb']) && trim($imgOpts['thumb'])!='') return $imgOpts['thumb'];
   if (isset($imgOpts['medium']) && trim($imgOpts['medium'])!='') return $imgOpts['medium'];
}}

if (!function_exists("nxs_memCheck")) { function nxs_memCheck() { global $nxs_snapThisPageUrl;  $mLimit = (int) ini_get('memory_limit'); $mLimit = empty($mLimit) ? __('N/A') :$mLimit . __(' MByte');
  $mUsage = function_exists('memory_get_usage') ? round(memory_get_usage() / 1024 / 1024, 2) : 0; $mUsage = empty($mUsage) ? __('N/A') : $mUsage . __(' MByte'); ?>
    <div><strong><?php _e('PHP Version'); ?></strong>: <span><?php echo PHP_VERSION; ?>;&nbsp;</span>
      <strong><?php _e('Memory limit'); ?></strong>: <span><?php echo $mLimit; ?>; &nbsp;</span>
      <strong><?php _e('Memory usage'); ?></strong>: <span><?php echo $mUsage; ?>; &nbsp;</span>
      &nbsp;&nbsp;<a target="_blank" href="<?php echo $nxs_snapThisPageUrl; ?>&do=test">Check HTTPS/SSL</a>
    </div> <?php
}}
//## Check SSL Sec
if (!function_exists("nxsCheckSSLCurl")){function nxsCheckSSLCurl($url){
  $ch = curl_init($url); $headers = array(); $headers[] = 'Accept: text/html, application/xhtml+xml, */*'; $headers[] = 'Cache-Control: no-cache';
  $headers[] = 'Connection: Keep-Alive'; $headers[] = 'Accept-Language: en-us';  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0)"); 
  $content = curl_exec($ch); $err = curl_errno($ch); $errmsg = curl_error($ch); if ($err!=0) return array('errNo'=>$err, 'errMsg'=>$errmsg); else return false;
}}

?>
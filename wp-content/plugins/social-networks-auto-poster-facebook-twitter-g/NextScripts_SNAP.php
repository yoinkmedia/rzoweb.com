<?php
/*
Plugin Name: NextScripts: Social Networks Auto-Poster
Plugin URI: http://www.nextscripts.com/social-networks-auto-poster-for-wordpress
Description: This plugin automatically publishes posts from your blog to multiple accounts on Facebook, Twitter, and Google+ profiles and/or pages.
Author: Next Scripts
Version: 3.1.1
Author URI: http://www.nextscripts.com
Text Domain: nxs_snap
Copyright 2012  Next Scripts, Inc
*/
define( 'NextScripts_SNAP_Version' , '3.1.1' ); 

$nxs_mLimit = ini_get('memory_limit'); if (strpos($nxs_mLimit, 'G')) {$nxs_mLimit = (int)$nxs_mLimit * 1024;} else {$nxs_mLimit = (int)$nxs_mLimit;}
  if ($nxs_mLimit>0 && $nxs_mLimit<64) { add_filter('plugin_action_links','ns_add_nomem_link', 10, 2 );
if (!function_exists("ns_add_nomem_link")) { function ns_add_nomem_link($links, $file) { global $nxs_mLimit; static $this_plugin; if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);
  if ($file == $this_plugin){ $settings_link = '<b style="color:red;">Not Enough Memory allowed for PHP.</b> <br/> You have '.$nxs_mLimit.' MB. You need at least 64MB'; array_unshift($links, $settings_link);} return $links;}}
} else {
    
require_once "nxs_functions.php"; require_once "inc/nxs_functions_adv.php"; require_once "inc/nxs_snap_class.php"; 
//## Include All Available Networks            
//error_reporting(E_ALL); ini_set('display_errors', '1');
global $nxs_snapAvNts, $nxs_snapThisPageUrl, $nxs_snapSetPgURL, $nxs_plurl, $nxs_plpath, $nxs_isWPMU, $nxs_tpWMPU, $nxs_skipSSLCheck;

$nxs_snapSetPgURL = nxs_get_admin_url().'options-general.php?page=NextScripts_SNAP.php'; $nxs_snapThisPageUrl = $nxs_snapSetPgURL; $nxs_plurl = plugin_dir_url(__FILE__); $nxs_plpath = plugin_dir_path(__FILE__); 
$nxs_isWPMU = defined('MULTISITE') && MULTISITE==true; 

if (class_exists("NS_SNAutoPoster")) { nxs_checkAddLogTable(); $plgn_NS_SNAutoPoster = new NS_SNAutoPoster(); }
do_action('nxs_doSomeMore');
if (!isset($nxs_snapAvNts) || !is_array($nxs_snapAvNts)) $nxs_snapAvNts = array(); $nxs_snapAPINts = array(); foreach (glob($nxs_plpath.'inc-cl/*.php') as $filename){  require_once $filename; } 
do_action('nxs_doSomeMoreSecond');
//## Tests
if (isset($_GET['page']) && $_GET['page']=='NextScripts_SNAP.php' && isset($_GET['do']) && $_GET['do']=='test'){ 
  error_reporting(E_ALL); ini_set('error_reporting', E_ALL); ini_set('display_errors', 1); if (function_exists('gzdeflate')) echo "Y"; else echo "N";  echo "Testting... cURL<br/>";
  nxs_cURLTest("http://www.google.com/intl/en/contact/", "HTTP to Google", "Mountain View, CA");
  nxs_cURLTest("https://www.google.com/intl/en/contact/", "HTTPS to Google", "Mountain View, CA");
  nxs_cURLTest("https://www.facebook.com/", "HTTPS to Facebook", 'id="facebook"');
  nxs_cURLTest("https://graph.facebook.com/nextscripts", "HTTPS to API (Graph) Facebook", '270851199672443');  
  nxs_cURLTest("https://www.linkedin.com/", "HTTPS to LinkedIn", 'rel="canonical" href="https://www.linkedin.com/"');
  nxs_cURLTest("https://twitter.com/", "HTTPS to Twitter", '<link rel="canonical" href="https://twitter.com/">');
  nxs_cURLTest("https://www.pinterest.com/", "HTTPS to Pinterest", 'content="Pinterest"');
  nxs_cURLTest("http://www.livejournal.com/", "HTTP to LiveJournal", '1999 LiveJournal');  
  die('Done');
}
//## Delete Account
if (!function_exists("ns_delNT_ajax")) { function ns_delNT_ajax(){ check_ajax_referer('nxsSsPageWPN'); $indx = (int)$_POST['id']; 
  global $plgn_NS_SNAutoPoster;  if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options; 
  unset($options[$_POST['nt']][$indx]); if (is_array($options)) { update_option('NS_SNAutoPoster', $options); $plgn_NS_SNAutoPoster->nxs_options = $options; }
}}
if (!function_exists("nsAuthFBSv_ajax")) { function nsAuthFBSv_ajax() { check_ajax_referer('nsFB');  $pgID = $_POST['pgID']; $fbs = array();
  global $plgn_NS_SNAutoPoster;  if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options;   
  foreach ($options['fb'] as $two) { if ($two['fbPgID']==$pgID) $two['wfa']=time(); $fbs[] = $two; } $options['fb'] = $fbs; if (is_array($options)) { update_option('NS_SNAutoPoster', $options); $plgn_NS_SNAutoPoster->nxs_options = $options; }
}}  
if (!function_exists("nsGetBoards_ajax")) { 
  function nsGetBoards_ajax() { global $nxs_gCookiesArr; check_ajax_referer('nxsSsPageWPN'); global $plgn_NS_SNAutoPoster; if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options; 
  if (get_magic_quotes_gpc() || $_POST['nxs_mqTest']=="\'") { $_POST['u'] = stripslashes($_POST['u']);  $_POST['p'] = stripslashes($_POST['p']);} $_POST['p'] = trim($_POST['p']); $u = trim($_POST['u']);  
   $loginError = doConnectToPinterest($_POST['u'],  substr($_POST['p'], 0, 5)=='g9c1a'?nsx_doDecode(substr($_POST['p'], 5)):$_POST['p'] );  if ($loginError!==false) {echo $loginError; return "BAD USER/PASS";} 
   $gPNBoards = doGetBoardsFromPinterest();  $options['pn'][$_POST['ii']]['pnBoardsList'] = base64_encode($gPNBoards);
   $options['pn'][$_POST['ii']]['pnSvC'] = serialize($nxs_gCookiesArr); if (is_array($options)) update_option('NS_SNAutoPoster', $options); echo $gPNBoards; die();
  }
}     

if (!function_exists("nxs_getBrdsOrCats_ajax")) { 
  function nxs_getBrdsOrCats_ajax() { global $nxs_gCookiesArr; check_ajax_referer('nxsSsPageWPN'); global $plgn_NS_SNAutoPoster; if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options; 
    if (get_magic_quotes_gpc() || $_POST['nxs_mqTest']=="\'") { $_POST['u'] = stripslashes($_POST['u']);  $_POST['p'] = stripslashes($_POST['p']);} $_POST['p'] = trim($_POST['p']); $u = trim($_POST['u']);  
  
    if ( $_POST['ty']=='pn') { $loginError = doConnectToPinterest($_POST['u'],  substr($_POST['p'], 0, 5)=='g9c1a'?nsx_doDecode(substr($_POST['p'], 5)):$_POST['p'] );  if ($loginError!==false) {echo $loginError; return "BAD USER/PASS";} 
      $gPNBoards = doGetBoardsFromPinterest();  $options['pn'][$_POST['ii']]['pnBoardsList'] = base64_encode($gPNBoards);
      $options['pn'][$_POST['ii']]['pnSvC'] = serialize($nxs_gCookiesArr); if (is_array($options)) update_option('NS_SNAutoPoster', $options); echo $gPNBoards; die();
    }
    if ( $_POST['ty']=='rd') { $loginError = doConnectToRD($_POST['u'],  substr($_POST['p'], 0, 5)=='g9c1a'?nsx_doDecode(substr($_POST['p'], 5)):$_POST['p'] ); if (!is_array($loginError)) { echo $loginError; return "BAD USER/PASS";} 
      $gBoards = doGetSubredditsFromRD(); $options['rd'][$_POST['ii']]['rdSubRedditsList'] = base64_encode($gBoards);
      if (is_array($options)) update_option('NS_SNAutoPoster', $options); echo $gBoards; die();
    }
     
  }
} 


if (!function_exists("nxs_delPostSettings_ajax")) { function nxs_delPostSettings_ajax(){ check_ajax_referer('nxsSsPageWPN'); global $nxs_snapAvNts; $pid = (int)$_POST['pid'];
  foreach ($nxs_snapAvNts as $avNt) delete_post_meta($pid, 'snap'.strtoupper($avNt['code'])); 
  echo "OK"; die();
}}

if (!function_exists("nsGetGPCats_ajax")) { 
  function nsGetGPCats_ajax() { global $nxs_gCookiesArr; check_ajax_referer('nxsSsPageWPN'); global $plgn_NS_SNAutoPoster; if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options; 
  if (get_magic_quotes_gpc() || $_POST['nxs_mqTest']=="\'") { $_POST['u'] = stripslashes($_POST['u']);  $_POST['p'] = stripslashes($_POST['p']);} $_POST['p'] = trim($_POST['p']); $u = trim($_POST['u']);  
   $loginError = doConnectToGooglePlus2($_POST['u'],  substr($_POST['p'], 0, 5)=='g9c1a'?nsx_doDecode(substr($_POST['p'], 5)):$_POST['p'] );  if ($loginError!==false) {echo $loginError; return "BAD USER/PASS";} 
   $gGPCCats = doGetCCatsFromGooglePlus($_POST['c']);  $options['gp'][$_POST['ii']]['gpCCatsList'] = base64_encode($gGPCCats);
   if (is_array($options)) update_option('NS_SNAutoPoster', $options); echo $gGPCCats; die();
  }
}     
if (!function_exists("nsGetWLBoards_ajax")) { 
  function nsGetWLBoards_ajax() { global $nxs_gCookiesArr; check_ajax_referer('nxsSsPageWPN'); global $plgn_NS_SNAutoPoster; if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options; 
  if (get_magic_quotes_gpc() || $_POST['nxs_mqTest']=="\'") { $_POST['u'] = stripslashes($_POST['u']);  $_POST['p'] = stripslashes($_POST['p']);} $_POST['p'] = trim($_POST['p']); $u = trim($_POST['u']);  
   $loginError = doConnectToWaNeLo($_POST['u'],  substr($_POST['p'], 0, 5)=='g9c1a'?nsx_doDecode(substr($_POST['p'], 5)):$_POST['p'] );  if ($loginError!==false) {echo $loginError; return "BAD USER/PASS";} 
   $gWLBoards = doGetBoardsFromWaNeLo();  $options['wl'][$_POST['ii']]['wlBoardsList'] = base64_encode($gWLBoards);
   $options['wl'][$_POST['ii']]['wlSvC'] = serialize($nxs_gCookiesArr); if (is_array($options)) update_option('NS_SNAutoPoster', $options); echo $gWLBoards; die();
  }
}     
//## Set all posts to Include/exclude from/to Auto-Reposting
if (!function_exists("nxs_SetRpstAll_ajax")) { 
 function nxs_SetRpstAll_ajax() { check_ajax_referer('nxsSsPageWPN'); global $plgn_NS_SNAutoPoster; if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options;//  prr($options[$_POST['t']][$_POST['ii']]);
   if ($_POST['ed']=='2')  {echo "OK"; die();} //## !!FIX THIS!!
   if ($_POST['ed']=='X' || $_POST['ed']=='L') { // prr($options[$_POST['t']][$_POST['ii']]); prr($options); die();
     if ($_POST['ed']=='X') { $options[$_POST['t']][$_POST['ii']]['rpstLastPostID'] = ''; 
       $options[$_POST['t']][$_POST['ii']]['rpstLastShTime'] = ''; $options[$_POST['t']][$_POST['ii']]['rpstLastPostTime'] = '';  $options[$_POST['t']][$_POST['ii']]['rpstNxTime'] = ''; 
     } elseif ($_POST['ed']=='L' && trim($_POST['lpid'])!='' && (int)$_POST['lpid'] > 0) { 
         $post = get_post($_POST['lpid']);
         $options[$_POST['t']][$_POST['ii']]['rpstLastPostTime'] = $post->post_date;
         $options[$_POST['t']][$_POST['ii']]['rpstLastPostID'] = trim($_POST['lpid']);     
     }
     if (is_array($options)) { update_option('NS_SNAutoPoster', $options); $plgn_NS_SNAutoPoster->nxs_options = $options; } //  echo "|".$_POST['t'].$_POST['ii']."|"; prr($options[$_POST['t']][$_POST['ii']]);
   } else { 
    if (!empty($options['nxsCPTSeld'])) $tpArray = maybe_unserialize($options['nxsCPTSeld']); if (!is_array($tpArray)) $tpArray = array('post'); else $tpArray[] = 'post'; 
    foreach ($tpArray  as $tp) if (!empty($tp)) { 
    $args = array( 'post_type' => $tp, 'post_status' => 'publish', 'numberposts' => 30, 'offset'=> 0, 'fields'=>'ids' ); $posts = get_posts( $args ); 
    while (count($posts)>0){
      foreach ($posts as $postID){ $pMeta = maybe_unserialize(get_post_meta($postID, 'snap'.strtoupper($_POST['t']), true)); 
        if (!isset($pMeta) || !is_array($pMeta)) $pMeta = array();  if (!isset($pMeta[$_POST['ii']]) || !is_array($pMeta[$_POST['ii']])) $pMeta[$_POST['ii']] = array(); 
        $pMeta[$_POST['ii']]['rpstPostIncl'] = $_POST['ed']=='0'?'0':'nxsi'.$_POST['ii'].$_POST['t'];  delete_post_meta($postID, 'snap'.strtoupper($_POST['t'])); add_post_meta($postID, 'snap'.strtoupper($_POST['t']), serialize($pMeta));        
      } $args['offset'] = $args['offset']+30;  $posts = get_posts( $args );
    } 
    }
  } echo "OK"; die(); 
}}  
if (!function_exists("nxs_clLgo_ajax")) { function nxs_clLgo_ajax() { check_ajax_referer('nxsSsPageWPN'); global $wpdb;
  //update_option('NS_SNAutoPosterLog', ''); 
  $wpdb->query( 'DELETE FROM '.$wpdb->prefix . 'nxs_log' ); echo "OK";
}} 
if (!function_exists("nxs_rfLgo_ajax")) { function nxs_rfLgo_ajax() { check_ajax_referer('nxsSsPageWPN');  echo "Y:";
  //$log = get_option('NS_SNAutoPosterLog'); $logInfo = maybe_unserialize(get_option('NS_SNAutoPosterLog')); 
  $logInfo = nxs_getnxsLog();
  if (is_array($logInfo))foreach (array_reverse($logInfo) as $logline) { 
            if ($logline['type']=='E') $actSt = "color:#FF0000;"; elseif ($logline['type']=='M') $actSt = "color:#585858;"; elseif ($logline['type']=='BG') $actSt = "color:#008000; font-weight:bold;";
              elseif ($logline['type']=='I') $actSt = "color:#0000FF;"; elseif ($logline['type']=='W') $actSt = "color:#DB7224;"; elseif ($logline['type']=='BI') $actSt = "color:#0000FF; font-weight:bold;"; 
              elseif ($logline['type']=='GR') $actSt = "color:#008080;"; elseif ($logline['type']=='S') $actSt = "color:#005800; font-weight:bold;"; else $actSt = "color:#585858;";              
            if ($logline['type']=='E') $msgSt = "color:#FF0000;"; elseif ($logline['type']=='BG') $msgSt = "color:#008000; font-weight:bold;"; else $msgSt = "color:#585858;";                            
            if ($logline['nt']!='') $ntInfo = ' ['.$logline['nt'].'] '; else $ntInfo = '';           
            echo '<snap style="color:#008000">['.$logline['date'].']</snap> - <snap style="'.$actSt.'">['.$logline['act'].']</snap>'.$ntInfo.'-  <snap style="'.$msgSt.'">'.$logline['msg'].'</snap> '.$logline['extInfo'].'<br/>'; 
  }
}} 
//## Initialize the admin panel if the plugin has been activated
if (!function_exists("nxs_AddSUASettings")) { function nxs_AddSUASettings() {  global $plgn_NS_SNAutoPoster, $nxs_plurl; // if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options;       
  add_menu_page('Social Networks Auto Poster', 'Social Networks Auto Poster', 'manage_options', basename(__FILE__), array(&$plgn_NS_SNAutoPoster, 'showSNAP_WPMU_OptionsPage'), $nxs_plurl.'img/snap-icon12.png');  }}
//## Initialize the admin panel if the plugin has been activated
if (!function_exists("NS_SNAutoPoster_ap")) { function NS_SNAutoPoster_ap() { global $plgn_NS_SNAutoPoster, $nxs_plurl; // if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options;       
   if (function_exists('add_options_page')) { add_options_page('Social Networks Auto Poster', 
     '<span style="font-weight:bold; color:#2ecc2e;">{SNAP} </span>Social Networks Auto Poster', 'manage_options', basename(__FILE__), array(&$plgn_NS_SNAutoPoster, 'showSNAutoPosterOptionsPage'));     
}}}
if (!function_exists("NS_SNAutoPoster_apx")) { function NS_SNAutoPoster_apx() { global $plgn_NS_SNAutoPoster, $nxs_plurl;  if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options;       
   if (function_exists('add_options_page')) { add_options_page('Social Networks Auto Poster', 
     '<span style="font-weight:bold; color:#2ecc2e">{SNAP} </span>Social Networks Auto Poster ', 'manage_options', basename(__FILE__), array(&$plgn_NS_SNAutoPoster, 'showSNAutoPosterOptionsPagex'));     
}}}
//## Main Function to Post 
if (!function_exists("nxs_snapLogPublishTo")) { function nxs_snapLogPublishTo( $new_status, $old_status, $post ) {
  if ( $old_status!='publish' && $old_status!='trash' && $new_status == 'publish' ) nxs_addToLogN('BG', "*** ID: {$post->ID}, Type: {$post->post_type}", '', ' Status Changed: '."{$old_status}_to_{$new_status}".'. Autopost requested.'); 
}}
if (!function_exists("nxs_snapPublishTo")) { function nxs_snapPublishTo($postArr, $type='', $aj=false) {  global $plgn_NS_SNAutoPoster, $nxs_snapAvNts, $blog_id, $nxs_tpWMPU;  //  echo " | nxs_doSMAS2 | "; prr($postArr);
  if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options; 
  if (!empty($_POST['nxs_snapPostOptions'])) { $NXS_POSTX = $_POST['nxs_snapPostOptions'];  $NXS_POST = array(); $NXS_POST = NXS_parseQueryStr($NXS_POSTX); } else $NXS_POST = $_POST;
  if(is_object($postArr)) $postID = $postArr->ID; else { $postID = $postArr; $postArr = get_post($postID);  } $isPost = isset($NXS_POST["snapEdIT"]);  $post = get_post($postID);   
  if ($post->post_status != 'publish') { sleep(5);  $post = get_post($postID); $postArr = $post;
    if ($post->post_status != 'publish') {  nxs_addToLogN('I', 'Cancelled', '', 'Autopost Cancelled - Post is not "Published" Right now - Post ID:('.$postID.') - Current Post status -'.$post->post_status ); return; }
  }  
  //nxs_addToLogN('BG', 'Post Status Changed', '', '-=## Autopost requested.'.($blog_id>1?'BlogID:'.$blog_id:'').' PostID:('.$postID.') Post Type: '.$post->post_type.' ##=-'); 
  $args=array('public'=>true, '_builtin'=>false);  $output = 'names';  $operator = 'and';  $post_types = array(); if (function_exists('get_post_types')) $post_types=get_post_types($args, $output, $operator); 
  if ( isset($options['nxsCPTSeld']) && $options['nxsCPTSeld']!='') $nxsCPTSeld = unserialize($options['nxsCPTSeld']);  else $nxsCPTSeld = array(); // $nxsCPTSeld = array_keys($post_types); - why we needed it?
  
  if ($post->post_type == 'post' || ($options['useForPages']=='1' && $post->post_type == 'page') || (in_array($post->post_type, $post_types) && in_array($post->post_type, $nxsCPTSeld))) { 
    if ($isPost && $options['skipSecurity']!='1' && !current_user_can("make_snap_posts") && !current_user_can("manage_options")) { nxs_addToLogN('I', 'Skipped', '', 'Current user can\'t autopost - Post ID:('.$postID.')' ); return; }
    $postUser = $postArr->post_author; 
    if ($options['skipSecurity']!='1' && !user_can( $postUser, "make_snap_posts" ) && !user_can( $postUser, "manage_options")){ nxs_addToLogN('I', 'Skipped', '', 'User ID '.$postUser.' can\'t autopost  - Post ID:('.$postID.')' ); return; } 
    if ($isPost) $plgn_NS_SNAutoPoster->NS_SNAP_SavePostMetaTags($postID); 
    if (function_exists('nxs_doSMAS2')) { nxs_doSMAS2($postArr, $type, $aj); return; } else {
    $options = $plgn_NS_SNAutoPoster->nxs_options;  $ltype=strtolower($type);
    if ($nxs_tpWMPU=='S') { switch_to_blog(1); $plgn_NS_SNAutoPoster = new NS_SNAutoPoster(); $options = $plgn_NS_SNAutoPoster->nxs_options; restore_current_blog(); }
    if (!isset($options['nxsHTDP']) || $options['nxsHTDP']=='S') { if(isset($NXS_POST["snapEdIT"]) && $NXS_POST["snapEdIT"]=='1') { $publtype='S'; $delay = rand(2,10); } else $publtype='A'; } else $publtype = 'I';
    nxs_addToLogN('BG', 'Start =- ', '', '------=========#### NEW AUTO-POST REQUEST '.($blog_id>1?'BlogID:'.$blog_id:'').' PostID:('.$postID.') '.($publtype=='S'?'Scheduled +'.$delay:($publtype=='A'?'Automated':'Immediate')).' ####=========------');
  
    $snap_isAutoPosted = get_post_meta($postID, 'snap_isAutoPosted', true); if ($snap_isAutoPosted=='1') { nxs_addToLogN('W', 'Skipped', '', 'Already Autoposted - Post ID:('.$postID.')' ); return; }  
    $snap_isEdIT = get_post_meta($postID, 'snapEdIT', true); if ($snap_isEdIT!='1') { $doPost = true; $exclCats = maybe_unserialize($options['exclCats']); $postCats = wp_get_post_categories($postID);
       foreach ($postCats as $pCat) { if ( (is_array($exclCats)) && in_array($pCat, $exclCats)) $doPost = false; else {$doPost = true; break;}}
       if (!$doPost) { nxs_addToLogN('I', 'Skipped', '', 'Non-Human Post - Category Excluded - Post ID:('.$postID.')' ); return; }
    }    
      
    foreach ($nxs_snapAvNts as $avNt) { 
      if (count($options[$avNt['lcode']])>0) { $clName = 'nxs_snapClass'.$avNt['code'];
        if ($isPost && isset($NXS_POST[$avNt['lcode']])) $po = $NXS_POST[$avNt['lcode']]; else { $po =  get_post_meta($postID, 'snap'.$avNt['code'], true); $po =  maybe_unserialize($po);} 
      
        if (isset($po) && is_array($po)) $isPostMeta = true; else { $isPostMeta = false; $po = $options[$avNt['lcode']]; }
        delete_post_meta($postID, 'snap_isAutoPosted'); add_post_meta($postID, 'snap_isAutoPosted', '1');
      
        $optMt = $options[$avNt['lcode']][0]; if ($isPostMeta) { $ntClInst = new $clName(); $optMt = $ntClInst->adjMetaOpt($optMt, $po[0]); }       
          if ($snap_isEdIT!='1') { $doPost = true; 
            if ( $optMt['catSel']=='1' && trim($optMt['catSelEd'])!='' ) { $inclCats = explode(',',$optMt['catSelEd']); foreach ($postCats as $pCat) { if (!in_array($pCat, $inclCats)) $doPost = false; else {$doPost = true; break;}} 
              if (!$doPost) { nxs_addToLogN('I', 'Skipped', $avNt['name'].' ('.$optMt['nName'].')', '[Non-Human Post]  - Individual Category Excluded - Post ID:('.$postID.')' ); continue; }
            }
          }        
          if ($optMt['do'.$avNt['code']]=='1') { $optMt['ii'] = 0; 
            if ($publtype=='A' && ($optMt['nMin']>0 || $optMt['nHrs']>0 || $optMt['nTime']!='')) $publtype='S';        
            if ($publtype=='S') { if (isset($optMt['nHrs']) && isset($optMt['nMin']) && ($optMt['nHrs']>0 || $optMt['nMin']>0) ) { $delay = $optMt['nMin']*60+$optMt['nHrs']*3600;
                nxs_addToLogN('I', 'Delayed', $avNt['name'].' ('.$optMt['nName'].')', 'Post has been delayed for '.$delay.' Seconds ('.($optMt['nHrs']>0?$optMt['nHrs'].' Hours':'')." ".($optMt['nMin']>0?$optMt['nMin'].' Minutes':'').')' );
              } else $delay = rand(2,10); $optMt['timeToRun'] = time()+$delay; 
              if ($options['ver']>300.330) { $shName = 'nxs_snap_sh_'.$avNt['code'].'0_'.$optMt['timeToRun']; delete_post_meta($postID, $shName); add_post_meta($postID, $shName, $optMt); $args = array($postID, $shName); }
                else $args = array($postID, $optMt);  
              wp_schedule_single_event($optMt['timeToRun'],'ns_doPublishTo'.$avNt['code'], $args); 
                nxs_addToLogN('BI', 'Scheduled', $avNt['name'].' ('.$optMt['nName'].')', ' PostID:('.$postID.')' );
            } else { $fname = 'nxs_doPublishTo'.$avNt['code']; $fname($postID, $optMt); }
          } else { nxs_addToLogN('GR', 'Skipped', $avNt['name'].' ('.$optMt['nName'].')', '-=[Unchecked Account]=- - PostID:'.$postID.'' ); }
        }                   
      } } } else { nxs_addToLogN('I', 'Skipped', '', 'Excluded Post Type: '.$post->post_type.' (Post ID: '.$postID.')' ); return; }
   if ($isS) restore_current_blog(); 
}} 

//## Add settings link to plugins list
if (!function_exists("ns_add_settings_link")) { function ns_add_settings_link($links, $file) {
    static $this_plugin;
    if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);
    if ($file == $this_plugin){
        $settings_link = '<a href="options-general.php?page=NextScripts_SNAP.php">'.__("Settings", "default").'</a>';
        array_unshift($links, $settings_link);
    }
    return $links;
}}
//## Actions and filters    

if (!function_exists("nxs_adminInitFunc")) { function nxs_adminInitFunc(){ global $plgn_NS_SNAutoPoster, $nxs_snapThisPageUrl, $pagenow, $nxs_isWPMU; 
  $nxs_snapThisPageUrl = nxs_get_admin_url().($pagenow=='admin.php'?'network/':'').$pagenow.'?page=NextScripts_SNAP.php'; 
  if (function_exists('nxs_getInitUCheck') && (isset($plgn_NS_SNAutoPoster))) { $options = $plgn_NS_SNAutoPoster->nxs_options; if (is_array($options) && count($options)>1) nxs_getInitUCheck($options);  } 
  //## Javascript to Admin Panel        
  if (( ($pagenow=='options-general.php'||$pagenow=='admin.php') && isset($_GET['page']) && $_GET['page']=='NextScripts_SNAP.php') ||$pagenow=='post.php'||$pagenow=='post-new.php'){
    if ( isset($_GET['post_type']) && $_GET['post_type']=='page' && isset($options['useForPages']) && $options['useForPages']!=1 ) {} 
      else { add_filter( 'tiny_mce_before_init', 'nxs_tiny_mce_before_init' ); add_action('admin_head', 'jsPostToSNAP'); add_action('admin_head', 'nxs_jsPostToSNAP2'); }
  }
  if (function_exists('nxsDoLic_ajax')) { add_action('wp_ajax_nxsDoLic', 'nxsDoLic_ajax');  } 
}}
if (!function_exists("nxs_adminInitFunc2")) { function nxs_adminInitFunc2(){ global $plgn_NS_SNAutoPoster, $nxs_snapThisPageUrl, $pagenow;   $nxs_snapThisPageUrl = nxs_get_admin_url().($pagenow=='admin.php'?'network/':'').$pagenow.'?page=NextScripts_SNAP.php';  //## Add MEtaBox to Post Edit Page
  if (current_user_can("see_snap_box") || current_user_can("manage_options")) add_action('add_meta_boxes', array($plgn_NS_SNAutoPoster, 'NS_SNAP_addCustomBoxes'));        
}}

function nxs_saveSiteSets_ajax(){ check_ajax_referer('nxssnap'); 
   if ($_POST['sid']=='A'){  global $wpdb; $allBlogs = $wpdb->get_results("SELECT blog_id FROM wp_blogs where blog_id > 1");
     foreach( $allBlogs as $aBlog ) { switch_to_blog($aBlog->blog_id); 
       $options =  get_option('NS_SNAutoPoster'); $options['suaMode'] = $_POST['sset']; update_option('NS_SNAutoPoster', $options);
     }       
   } else { switch_to_blog($_POST['sid']); 
     $options = get_option('NS_SNAutoPoster'); $options['suaMode'] = $_POST['sset']; update_option('NS_SNAutoPoster', $options); //    prr($plgn_NS_SNAutoPoster->dbOptionsName);  prr($options);
   }
   echo "OK"; die();
}

//## OG:Tags
function nxs_start_ob(){ if (!is_admin()) ob_start( 'nxs_ogtgCallback' );}
function nxs_end_flush_ob(){ if (!is_admin()) @ob_end_flush();}
function nxs_ogtgCallback($content){ global $post, $plgn_NS_SNAutoPoster;  
  if (stripos($content, 'og:title')!==false) $ogOut = "\r\n"; else {
    if (!isset($plgn_NS_SNAutoPoster)) $options = get_option('NS_SNAutoPoster'); else $options = $plgn_NS_SNAutoPoster->nxs_options;    $ogimgs = array();  
    if (!empty($post) && !is_object($post) && int($post)>0) $post = get_post($post); if (empty($options['advFindOGImg'])) $options['advFindOGImg'] = 0;       
    $title = preg_match( '/<title>(.*)<\/title>/', $content, $title_matches );  
    if ($title !== false && count( $title_matches) == 2 ) $ogT ='<meta property="og:title" content="' . $title_matches[1] . '" />'."\r\n"; else {
      if (is_home() || is_front_page() )  $ogT = get_bloginfo( 'name' ); else $ogT = get_the_title();
      $ogT =  '<meta property="og:title" content="' . esc_attr( apply_filters( 'nxsog_title', $ogT ) ) . '" />'."\r\n";          
    }    
    $prcRes = preg_match( '/<meta name="description" content="(.*)"/', $content, $description_matches );    
    if ( $prcRes !== false && count( $description_matches ) == 2 ) $ogD = '<meta property="og:description" content="' . $description_matches[1] . '" />'."\r\n"; {
      if (!empty($post) && is_object($post) && is_singular()) {
        if(has_excerpt($post->ID))$ogD=strip_tags(nxs_snapCleanHTML(get_the_excerpt($post->ID)));else $ogD= str_replace("  ", ' ', str_replace("\r\n", ' ', trim(substr(strip_tags(nxs_snapCleanHTML(strip_shortcodes($post->post_content))), 0, 200))));
      } else $ogD = get_bloginfo('description');  $ogD = preg_replace('/\r\n|\r|\n/m','',$ogD); 
      $ogD = '<meta property="og:description" content="'.esc_attr( apply_filters( 'nxsog_desc', $ogD ) ).'" />'."\r\n";          
    }    
    $ogSN = '<meta property="og:site_name" content="'.get_bloginfo('name').'" />'."\r\n";
    $ogLoc = strtolower(esc_attr(get_locale())); if (strlen($ogLoc)==2) $ogLoc .= "_".strtoupper($ogLoc);
    $ogLoc = '<meta property="og:locale" content="'.$ogLoc.'" />'."\r\n"; $iss = is_home();  
    $ogType = is_singular()?'article':'website'; if($vidsFromPost == false) $ogType = '<meta property="og:type" content="'.esc_attr(apply_filters('nxsog_type', $ogType)).'" />'."\r\n";                  
        
    if (is_home() || is_front_page()) $ogUrl = get_bloginfo( 'url' ); else $ogUrl = 'http' . (is_ssl() ? 's' : '') . "://".$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $ogUrl = '<meta property="og:url" content="'.esc_url( apply_filters( 'nxsog_url', $ogUrl ) ) . '" />' . "\r\n";
  
    if (!is_home()) { /*
      $vidsFromPost = nsFindVidsInPost($post); if ($vidsFromPost !== false && is_singular()) {  echo '<meta property="og:video" content="http://www.youtube.com/v/'.$vidsFromPost[0].'" />'."\n";  
      echo '<meta property="og:video:type" content="application/x-shockwave-flash" />'."\n";
      echo '<meta property="og:video:width" content="480" />'."\n";
      echo '<meta property="og:video:height" content="360" />'."\n";
      echo '<meta property="og:image" content="http://i2.ytimg.com/vi/'.$vidsFromPost[0].'/mqdefault.jpg" />'."\n";
      echo '<meta property="og:type" content="video" />'."\n"; 
    } */
    
      if (function_exists('has_post_thumbnail') && is_object($post) && has_post_thumbnail($post->ID)) {
        $thumbnail_src = nxs_getPostImage($post->ID, 'full', $options['ogImgDef']); $ogimgs[] = $thumbnail_src[0];
      } $imgsFromPost = nsFindImgsInPost($post, (int)$options['advFindOGImg']==1);           
      if ($imgsFromPost !== false && is_singular() && is_array($ogimgs) && is_array($imgsFromPost))  $ogimgs = array_merge($ogimgs, $imgsFromPost);       
    }       
    //## Add default image to the endof the array
    if ( count($ogimgs)<1 && isset($options['ogImgDef']) && $options['ogImgDef']!='') $ogimgs[] = $options['ogImgDef']; 
    //## Output og:image tags
    if (!empty($ogimgs) && is_array($ogimgs)) foreach ($ogimgs as $ogimage)  $ogImgsOut = '<meta property="og:image" content="'.esc_url(apply_filters('ns_ogimage', $ogimage)).'" />'."\r\n"; 
    $ogOut  = "\r\n".$ogSN.$ogT.$ogD.$ogType.$ogUrl.$ogLoc.$ogImgsOut;
  } $content = str_ireplace('<!-- ## NXSOGTAGS ## -->', $ogOut, $content); 
  return $content;
}
function nxs_addOGTagsPreHolder() { echo "<!-- ## NXS/OG ## --><!-- ## NXSOGTAGS ## --><!-- ## NXS/OG ## -->\n\r";}

if (!function_exists("nxssnap_enqueue_scripts")) { function nxssnap_enqueue_scripts(){ 
  wp_enqueue_script( 'nxssnap-scripts', plugin_dir_url( __FILE__ ) . 'js/js.js', array( 'jquery' ) );
  wp_localize_script( 'nxssnap-scripts', 'MyAjax', array( 'ajaxurl' => nxs_get_admin_url( 'admin-ajax.php' ), 'nxsnapWPnonce' => wp_create_nonce( 'nxsnapWPnonce' ),));
}} 

if (!function_exists("nxs_getExpSettings_ajax")) { function nxs_getExpSettings_ajax() { /* check_ajax_referer('nsDN'); */  $filename = preg_replace('/[^a-z0-9\-\_\.]/i','',$_POST['filename']);
 header("Cache-Control: "); header("Content-type: text/plain"); header('Content-Disposition: attachment; filename="'.$filename.'"');
 global $plgn_NS_SNAutoPoster;  if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options; 
 echo serialize($options); die();
}}

function cron_add_nxsreposter( $schedules ) { $schedules['nxsreposter'] = array( 'interval' => 90, 'display' => __( 'NXS Reposter' )); return $schedules;} // Do this every 90 seconds

if (!function_exists("nxs_snapAjax")) { function nxs_snapAjax() { check_ajax_referer('nxsSsPageWPN'); global $plgn_NS_SNAutoPoster; if (!isset($plgn_NS_SNAutoPoster)) return; $options = $plgn_NS_SNAutoPoster->nxs_options; 
  if ($_POST['nxsact']=='getNTset') { $ii = $_POST['ii']; $nt = $_POST['nt']; $ntl = strtolower($nt); $pbo = $options[$ntl][$ii];  $pbo['ntInfo']['lcode'] = $ntl; $clName = 'nxs_snapClass'.$nt; $ntObj = new $clName();  
     $ntObj->showNTSettings($ii, $pbo);  
  }
  die();
}}

 
//## Actions and filters    
add_action('shutdown', 'nxs_psCron', 25); //add_action( 'transition_post_status', 'nxs_snapLogPublishTo', 10, 3 );

add_filter('cron_schedules', 'cron_add_nxsreposter');  
add_action('nxs_hourly_event', 'nxs_do_this_hourly');
add_action('nxs_querypost_event', 'nxs_do_post_from_query');  
add_action('wp', 'nxs_activation'); //## Adds Hourly Event  
add_filter('get_avatar','ns_get_avatar', 10, 5 );
  
if (isset($plgn_NS_SNAutoPoster)) { //## Actions
  //## Add the admin menu    
  if ($nxs_skipSSLCheck===true){ add_filter('https_ssl_verify', '__return_false'); add_filter('https_local_ssl_verify', '__return_false'); }  
  if ($nxs_isWPMU) { add_action('network_admin_menu', 'nxs_AddSUASettings'); global $blog_id; } $suOptions = array(); 
  $suOptions = $plgn_NS_SNAutoPoster->nxs_options; if ($nxs_isWPMU) { $ntOptions = $plgn_NS_SNAutoPoster->nxs_ntoptions; if (!isset($suOptions['suaMode'])) $suOptions['suaMode'] = ''; }  
  $isPMB = $nxs_isWPMU && function_exists('nxs_doSMAS1') && $blog_id==1;
  $isO = !$nxs_isWPMU || ($nxs_isWPMU && ($suOptions['isMU']||$suOptions['isMUx']) && ($suOptions['suaMode']=='O' || ($suOptions['suaMode']=='' && $ntOptions['nxsSUType']=='O')));
  $isS = !$nxs_isWPMU || ($nxs_isWPMU && ($suOptions['isMU']||$suOptions['isMUx']) && ($suOptions['suaMode']=='S' || ($suOptions['suaMode']=='' && $ntOptions['nxsSUType']=='S')));
  if ($nxs_isWPMU) { if ($isO) $nxs_tpWMPU = 'O'; elseif ($isS) $nxs_tpWMPU = 'S';} // prr($nxs_tpWMPU); prr($suOptions);
  
  if (function_exists('nxs_doSMAS3')) nxs_doSMAS3($isS, $isO);
  if (!$isO && !$isS && !$isPMB && !function_exists('showSNAP_WPMU_OptionsPageExt')) add_action('admin_menu', 'NS_SNAutoPoster_apx');    

  add_action('admin_init', 'nxs_adminInitFunc');  
  add_action( 'admin_enqueue_scripts', 'nxssnap_enqueue_scripts' ); 
  
  add_action('wp_ajax_nxs_snap_aj', 'nxs_snapAjax');
  
  add_action('wp_ajax_nxs_clLgo', 'nxs_clLgo_ajax');
  add_action('wp_ajax_nxs_rfLgo', 'nxs_rfLgo_ajax');
  add_action('wp_ajax_nxs_prxTest', 'nxs_prxTest_ajax');
  add_action('wp_ajax_nxs_prxGet', 'nxs_prxGet_ajax');
  add_action('wp_ajax_nxs_getExpSettings', 'nxs_getExpSettings_ajax');
  add_action('wp_ajax_nxs_hideTip', 'nxs_hideTip_ajax');
  
                       
  if ($isO || $isS) {    
    add_action( 'transition_post_status', 'nxs_snapLogPublishTo', 10, 3 );
  //## Whenever you publish a post, post to Social Networks
    add_action('future_to_publish', 'nxs_snapPublishTo');
    add_action('new_to_publish', 'nxs_snapPublishTo');
    add_action('draft_to_publish', 'nxs_snapPublishTo');
    add_action('pending_to_publish', 'nxs_snapPublishTo');   
    add_action('private_to_publish', 'nxs_snapPublishTo');
    add_action('auto-draft_to_publish', 'nxs_snapPublishTo');
    //## Add nxs_snapPublishTo to custom post types

    foreach ($nxs_snapAvNts as $avNt) { add_action('ns_doPublishTo'.$avNt['code'], 'nxs_doPublishTo'.$avNt['code'], 1, 2); }
    foreach ($nxs_snapAvNts as $avNt) { add_action('wp_ajax_rePostTo'.$avNt['code'], 'nxs_rePostTo'.$avNt['code'].'_ajax'); }
    
    //## Add AJAX Calls for Test and Repost    
    
    add_action('wp_ajax_nxs_getBrdsOrCats' , 'nxs_getBrdsOrCats_ajax');
    add_action('wp_ajax_getBoards' , 'nsGetBoards_ajax');
    add_action('wp_ajax_getGPCats' , 'nsGetGPCats_ajax');
    add_action('wp_ajax_getWLBoards' , 'nsGetWLBoards_ajax');
    add_action('wp_ajax_SetRpstAll' , 'nxs_SetRpstAll_ajax');
    add_action('wp_ajax_nxs_delPostSettings' , 'nxs_delPostSettings_ajax');    
    add_action('wp_ajax_nsDN', 'ns_delNT_ajax');    
  }
  
  if ($isO) {    
    add_action('admin_menu', 'NS_SNAutoPoster_ap');    
    add_action('admin_init', 'nxs_adminInitFunc2');    
    //## Initialize options on plugin activation
    $myrelpath = preg_replace( '/.*wp-content.plugins./', '', __FILE__ ); 
    add_action("activate_".$myrelpath,  array(&$plgn_NS_SNAutoPoster, 'init'));    
    
    //## Add/Change meta on Save
    add_action('edit_post', array($plgn_NS_SNAutoPoster, 'NS_SNAP_SavePostMetaTags'));
    add_action('publish_post', array($plgn_NS_SNAutoPoster, 'NS_SNAP_SavePostMetaTags'));
    add_action('save_post', array($plgn_NS_SNAutoPoster, 'NS_SNAP_SavePostMetaTags'));
  //  add_action('edit_page_form', array($plgn_NS_SNAutoPoster, 'NS_SNAP_SavePostMetaTags'));         
    
    add_action('wp_ajax_nsAuthFBSv', 'nsAuthFBSv_ajax');
    //## Custom Post Types and OG tags
    add_filter('plugin_action_links','ns_add_settings_link', 10, 2 );

    //## Scedulled Publish Calls    
    if (!empty($suOptions['nsOpenGraph']) && (int)$suOptions['nsOpenGraph'] == 1) {    
      add_action( 'init', 'nxs_start_ob', 0 );
      add_action('wp_head', 'nxs_addOGTagsPreHolder', 150);  
      add_action('shutdown', 'nxs_end_flush_ob', 1000);   
    }    
  }    
  if ($nxs_isWPMU){      
      if (function_exists('nxssnapmu_columns_head')) add_filter('wpmu_blogs_columns', 'nxssnapmu_columns_head');
      if (function_exists('nxssnapmu_columns_content')) add_action('manage_blogs_custom_column', 'nxssnapmu_columns_content', 10, 2);
      if (function_exists('nxssnapmu_columns_content')) add_action('manage_sites_custom_column', 'nxssnapmu_columns_content', 10, 2);    
      if (function_exists('nxs_add_style')) add_action( 'admin_footer', 'nxs_add_style' );  
      if (function_exists('nxs_saveSiteSets_ajax')) add_action('wp_ajax_nxs_saveSiteSets', 'nxs_saveSiteSets_ajax');
  }
}
}
?>
<?php    

//## NextScripts Facebook Connection Class

$nxs_snapAvNts[] = array('code'=>'FB', 'lcode'=>'fb', 'name'=>'Facebook');



if (!class_exists("nxs_snapClassFB")) { class nxs_snapClassFB {

  //#### Show Common Settings  

  function showGenNTSettings($ntOpts){ global $nxs_snapSetPgURL, $nxs_plurl; $ntInfo = array('code'=>'FB', 'lcode'=>'fb', 'name'=>'Facebook', 'defNName'=>'dlUName', 'tstReq' => true);

    if ( isset($_GET['code']) && $_GET['code']!='' && (isset($_GET['auth']) && $_GET['auth']=='fb') && ((!isset($_GET['action'])) || $_GET['action']!='gPlusAuth')){  $at = $_GET['code'];  echo "-= This is normal technical authorization info that will dissapear (Unless you get some errors) =- <br/><br/><br/>";

     //$fbo = array('wfa'=> 1339160000); //foreach ($ntOpts as $two) { if (isset($two['wfa']) && $two['wfa']>$fbo['wfa']) $fbo =  $two; }

     $fbo = $ntOpts[$_GET['acc']]; $wprg = array(); $response = wp_remote_get('https://graph.facebook.com/nextscripts', $wprg); 

     if( is_wp_error( $response) && isset($response->errors['http_request_failed']) && stripos($response->errors['http_request_failed'][0], 'SSL')!==false ) {  prr($response->errors); $wprg['sslverify'] = false; }

     if (isset($fbo['fbPgID'])){ echo "-="; prr($fbo);// die();

      $response  = wp_remote_get('https://graph.facebook.com/oauth/access_token?client_id='.$fbo['fbAppID'].'&redirect_uri='.urlencode($nxs_snapSetPgURL.'&auth=fb&acc='.$_GET['acc']).'&client_secret='.$fbo['fbAppSec'].'&code='.$at, $wprg); 

      //prr('https://graph.facebook.com/oauth/access_token?client_id='.$fbo['fbAppID'].'&redirect_uri='.urlencode($nxs_snapSetPgURL).'&client_secret='.$fbo['fbAppSec'].'&code='.$at);

      if ( (is_object($response) && (isset($response->errors))) || (is_array($response) && stripos($response['body'],'"error":')!==false )) { prr($response); die(); }

      parse_str($response['body'], $params);  $at = $params['access_token'];  prr($response); prr($params);

      $response  = wp_remote_get('https://graph.facebook.com/oauth/access_token?client_secret='.$fbo['fbAppSec'].'&client_id='.$fbo['fbAppID'].'&grant_type=fb_exchange_token&fb_exchange_token='.$at, $wprg); 

      if ((is_object($response) && isset($response->errors))) { prr($response); die();}

      parse_str($response['body'], $params); $at = $params['access_token']; $fbo['fbAppAuthToken'] = $at; 

      require_once ('apis/facebook.php'); echo "-= Using API =-<br/>";

      $facebook = new NXS_Facebook(array( 'appId' => $fbo['fbAppID'], 'secret' => $fbo['fbAppSec'], 'cookie' => true)); 

      $facebook -> setAccessToken($fbo['fbAppAuthToken']); $user = $facebook->getUser(); echo "USER:"; prr($user);

      if ($user) {

        try { $page_id = $fbo['fbPgID']; echo "-= Authorizing Page =-";          

          if ( !is_numeric($page_id) && stripos($fbo['fbURL'], '/groups/')!=false) { //$fbPgIDR = wp_remote_get('nxs.php?g='.$fbo['fbURL']); // TODO - how to replace

             $fbPgIDR = trim($fbPgIDR['body']); $page_id = $fbPgIDR!=''?$fbPgIDR:$page_id;

          } $page_info = $facebook->api("/$page_id?fields=access_token");           

          if( !empty($page_info['access_token']) ) { $fbo['fbAppPageAuthToken'] = $page_info['access_token']; }

        } catch (NXS_FacebookApiException $e) { $errMsg = $e->getMessage(); prr($errMsg);

          if ( stripos($errMsg, 'Unknown fields: access_token')!==false) { $fbo['fbAppPageAuthToken'] = $fbo['fbAppAuthToken']; } else { 

              if (stripos($errMsg, 'Unsupported get request')!==false) echo "<b style='color:red;'>Error </b>: Your Facebook URL ( <i>".$fbo['fbURL']."</i> ) is either incorrect or authorzing user don't have rights to post there.<br/>";

              echo 'Reported Error: ',  $errMsg, "\n"; die(); 

          }

        }

      } else echo "Can't get Facebook User. Please login to Facebook.";                

                                                

      if ($user>0) { $fbo['fbAppAuthUser'] = $user;  

        $optionsG = get_option('NS_SNAutoPoster'); $optionsG['fb'][$_GET['acc']] = $fbo;  update_option('NS_SNAutoPoster', $optionsG); 

        ?><script type="text/javascript">window.location = "<?php echo $nxs_snapSetPgURL; ?>"</script>      

      <?php } die(); }

    }     

    ?>    

    <div class="nxs_box">

      <div class="nxs_box_header"> 

        <div class="nsx_iconedTitle" style="margin-bottom:1px;background-image:url(<?php echo $nxs_plurl;?>img/<?php echo $ntInfo['lcode']; ?>16.png);"><?php echo $ntInfo['name']; ?>

          <?php $cbo = count($ntOpts); ?> 

          <?php if ($cbo>1){ ?><div class="nsBigText"><?php echo "(".($cbo=='0'?'No':$cbo)." "; _e('accounts', 'nxs_snap'); echo ")"; ?></div><?php } ?>

        </div>

      </div>

      <div class="nxs_box_inside">

        <?php foreach ($ntOpts as $indx=>$pbo){ if (trim($pbo['nName']=='')) $pbo['nName'] = str_ireplace('https://www.facebook.com','', str_ireplace('http://www.facebook.com','', $pbo['fbURL'])); 

        if (!isset($pbo[$ntInfo['lcode'].'OK']) || $pbo[$ntInfo['lcode'].'OK']=='') $pbo[$ntInfo['lcode'].'OK'] = (isset($pbo['fbAppAuthUser']) && $pbo['fbAppAuthUser']>1)?'1':''; ?>

          <p style="margin:0px;margin-left:5px;"> <img id="<?php echo $ntInfo['code'].$indx;?>LoadingImg" style="display: none;" src='<?php echo $nxs_plurl; ?>img/ajax-loader-sm.gif' />

            <input value="1" name="<?php echo $ntInfo['lcode']; ?>[<?php echo $indx; ?>][apDo<?php echo $ntInfo['code']; ?>]" onchange="<?php if (isset($pbo['catSel']) && (int)$pbo['catSel'] == 1) { ?>nxs_doShowWarning(jQuery(this), '<?php echo (substr_count($pbo['catSelEd'], ",")+1); ?>', '<?php echo $ntInfo['code'];?>', '<?php echo $indx;?>');<?php } ?>" type="checkbox" <?php if ((int)$pbo['do'.$ntInfo['code']] == 1 && $pbo['catSel']!='1') echo "checked"; ?> /> 

            <?php if (isset($pbo['catSel']) && (int)$pbo['catSel'] == 1) { ?> <span onmouseout="nxs_hidePopUpInfo('popOnlyCat');" onmouseover="nxs_showPopUpInfo('popOnlyCat', event);"><?php echo "*[".(substr_count($pbo['catSelEd'], ",")+1)."]*" ?></span><?php } ?>

            <?php if (isset($pbo['rpstOn']) && (int)$pbo['rpstOn'] == 1) { ?> <span onmouseout="nxs_hidePopUpInfo('popReActive');" onmouseover="nxs_showPopUpInfo('popReActive', event);"><?php echo "*[R]*" ?></span><?php } ?>

            <strong><?php  _e('Auto-publish to', 'nxs_snap'); ?> <?php echo $ntInfo['name']; ?> <i style="color: #005800;"><?php if($pbo['nName']!='') echo "(".$pbo['nName'].")"; ?></i></strong>

          &nbsp;&nbsp;<?php if ($ntInfo['tstReq'] && (!isset($pbo[$ntInfo['lcode'].'OK']) || $pbo[$ntInfo['lcode'].'OK']=='')){ ?><b style="color: #800000"><?php  _e('Attention required. Unfinished setup', 'nxs_snap'); ?> ==&gt;</b><?php } ?>

          <a id="do<?php echo $ntInfo['code'].$indx; ?>AG" href="#" onclick="doGetHideNTBlock('<?php echo $ntInfo['code'];?>' , '<?php echo $indx; ?>');return false;">[<?php  _e('Show Settings', 'nxs_snap'); ?>]</a>&nbsp;&nbsp;

          <a href="#" onclick="doDelAcct('<?php echo $ntInfo['lcode']; ?>', '<?php echo $indx; ?>', '<?php if (isset($pbo['bgBlogID'])) echo $pbo['nName']; ?>');return false;">[<?php  _e('Remove Account', 'nxs_snap'); ?>]</a>

          </p><div id="nxsNTSetDiv<?php echo $ntInfo['code'].$indx; ?>"></div><?php // $pbo['ntInfo'] = $ntInfo; $this->showNTSettings($indx, $pbo);             

        }?>

      </div>

    </div> <?php 

    

    

  }  

  //#### Show NEW Settings Page

  function showNewNTSettings($mfbo){ $fbo = array('nName'=>'', 'doFB'=>'1', 'fbURL'=>'', 'fbAppID'=>'', 'imgUpl'=>'1', 'fbPostType'=>'A', 'fbMsgAFormat'=>'', 'fbAppSec'=>'', 'fbAttch'=>'1', 'fbPgID'=>'', 'fbAppAuthUser'=>'', 'fbMsgFormat'=>__('New post (%TITLE%) has been published on %SITENAME%', 'nxs_snap') ); $fbo['ntInfo']= array('lcode'=>'fb'); $this->showNTSettings($mfbo, $fbo, true);}

  //#### Show Unit  Settings

  function showNTSettings($ii, $options, $isNew=false){  global $nxs_plurl, $nxs_snapSetPgURL, $plgn_NS_SNAutoPoster; $nt = $options['ntInfo']['lcode']; $ntU = strtoupper($nt); $tmzFrmt = _x('Y-m-d G:i:s', 'timezone date format');

    if ((int)$options['fbAttch']==0 && (!isset($options['trPostType']) || $options['trPostType']=='')) $options['trPostType'] = 'T';  

    if (!isset($plgn_NS_SNAutoPoster)) return; $gOptions = $plgn_NS_SNAutoPoster->nxs_options;  

    if (!isset($options['nHrs'])) $options['nHrs'] = 0; if (!isset($options['nMin'])) $options['nMin'] = 0;  if (!isset($options['catSel'])) $options['catSel'] = 0;  if (!isset($options['catSelEd'])) $options['catSelEd'] = ''; 

    if (!isset($options['nDays'])) $options['nDays'] = 0; if (!isset($options['qTLng'])) $options['qTLng'] = ''; if (!isset($options['fbMsgAFrmt'])) $options['fbMsgAFrmt'] = ''; 

    if (!isset($options['riComments'])) $options['riComments'] = '';  if (!isset($options['riCommentsAA'])) $options['riCommentsAA'] = ''; 

    if (!isset($options['useFBGURLInfo'])) $options['useFBGURLInfo'] = '';

    ?> 

    <div id="doFB<?php echo $ii; ?>Div" class="insOneDiv<?php if ($isNew) echo " clNewNTSets"; ?>">   <input type="hidden" name="apDoSFB<?php echo $ii; ?>" value="0" id="apDoSFB<?php echo $ii; ?>" />                                

    <?php if ($isNew) { ?>    <input type="hidden" name="fb[<?php echo $ii; ?>][apDoFB]" value="1" id="apDoNewFB<?php echo $ii; ?>" /> <?php } ?>

    

     <div class="nsx_iconedTitle" style="float: right; max-width: 322px; text-align: right; background-image: url(<?php echo $nxs_plurl; ?>img/fb16.png);"><a style="font-size: 12px;" target="_blank"  href="http://www.nextscripts.com/setup-installation-facebook-social-networks-auto-poster-wordpress/"><?php $nType="Facebook"; printf( __( 'Detailed %s Installation/Configuration Instructions', 'nxs_snap' ), $nType); ?></a><br/>

     <span style="font-size: 10px;"><?php _e('Please use in your Facebook App:', 'nxs_snap'); ?> <br/> URL: <em style="font-size: 10px; color:#CB4B16;">http://<?php echo $_SERVER["SERVER_NAME"] ?></em> <br/>Domain: <em style="font-size: 10px; color:#CB4B16;"><?php echo $_SERVER["SERVER_NAME"] ?></em> </span>

     

     </div>

    

    <div style="width:100%;"><strong><?php _e('Account Nickname', 'nxs_snap'); ?>:</strong> <i><?php _e('Just so you can easily identify it', 'nxs_snap'); ?></i> </div><input name="fb[<?php echo $ii; ?>][nName]" id="fbnName<?php echo $ii; ?>" style="font-weight: bold; color: #005800; border: 1px solid #ACACAC; width: 40%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['nName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /><br/>

    <?php echo nxs_addQTranslSel('fb', $ii, $options['qTLng']); ?>

    

    

    <ul class="nsx_tabs">

    <li><a href="#nsx<?php echo $nt.$ii ?>_tab1"><?php _e('Account Info', 'nxs_snap'); ?></a></li>    

    <?php if (!$isNew) { ?>  <li><a href="#nsx<?php echo $nt.$ii ?>_tab2"><?php _e('Advanced', 'nxs_snap'); ?></a></li>  <?php } ?>

    </ul>

    <div class="nsx_tab_container"><?php /* ######################## Account Tab ####################### */ ?>

    <div id="nsx<?php echo $nt.$ii ?>_tab1" class="nsx_tab_content" style="background-image: url(<?php echo $nxs_plurl; ?>img/<?php echo $nt; ?>-bg.png); background-repeat: no-repeat;  background-position:90% 10%;">

    

    <div style="width:100%;"><strong>Facebook URL:</strong> </div>

    <p style="font-size: 11px; margin: 0px;"><?php _e('Could be your Facebook Profile, Facebook Page, Facebook Group', 'nxs_snap'); ?></p>

    <input name="fb[<?php echo $ii; ?>][apFBURL]" id="apFBURL" style="width: 50%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['fbURL'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />                

    <div style="width:100%;"><strong>Facebook App ID:</strong> </div><input name="fb[<?php echo $ii; ?>][apFBAppID]" id="apFBAppID" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['fbAppID'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />  

    <div style="width:100%;"><strong>Facebook App Secret:</strong> </div><input name="fb[<?php echo $ii; ?>][apFBAppSec]" id="apFBAppSec" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['fbAppSec'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /><br/><br/>

    <div id="altFormat">

      <div style="width:100%;"><strong id="altFormatText"><?php _e('Message text Format', 'nxs_snap'); ?>:</strong> (<a href="#" id="apFBMsgFrmt<?php echo $ii; ?>HintInfo" onclick="mxs_showHideFrmtInfo('apFBMsgFrmt<?php echo $ii; ?>'); return false;"><?php _e('Show format info', 'nxs_snap'); ?></a>)</div>

        

        <textarea cols="150" rows="3" id="fb<?php echo $ii; ?>SNAPformat" name="fb[<?php echo $ii; ?>][apFBMsgFrmt]"  style="width:51%;max-width: 610px;" onfocus="jQuery('#fb<?php echo $ii; ?>SNAPformat').attr('rows', 6); mxs_showFrmtInfo('apFBMsgFrmt<?php echo $ii; ?>');"><?php _e(apply_filters('format_to_edit', htmlentities($options['fbMsgFormat'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?></textarea>    <?php nxs_doShowHint("apFBMsgFrmt".$ii); ?>    

       <br/>

   </div><br/>

      <div style="width:100%;"><strong style="font-size: 16px;" id="altFormatText">Post Type:</strong>&lt;-- (<a id="showShAtt" onmouseout="hidePopShAtt('<?php echo $ii; ?>X');" onmouseover="showPopShAtt('<?php echo $ii; ?>X', event);" onclick="return false;" class="underdash" href="http://www.nextscripts.com/blog/"><?php _e('What\'s the difference?', 'nxs_snap'); ?></a>)  </div>                      

<div style="margin-left: 10px;">

        

        <input type="radio" name="fb[<?php echo $ii; ?>][fbPostType]" value="T" <?php if ($options['fbPostType'] == 'T') echo 'checked="checked"'; ?> /> <?php _e('Text Post', 'nxs_snap'); ?> - <i><?php _e('just text message', 'nxs_snap'); ?></i><br/>                    

        

        <input type="radio" name="fb[<?php echo $ii; ?>][fbPostType]" value="I" <?php if ($options['fbPostType'] == 'I') echo 'checked="checked"'; ?> /> <?php _e('Image Post', 'nxs_snap'); ?> - <i><?php _e('big image with text message', 'nxs_snap'); ?></i><br/>

          <div style="width:100%; margin-left: 15px;"><strong><?php _e('Upload Images to', 'nxs_snap'); ?>:&nbsp;</strong> 

             <input value="2" id="apFBImgUplAPP<?php echo $ii; ?>" type="radio" name="fb[<?php echo $ii; ?>][apFBImgUpl]" <?php if ((int)$options['imgUpl'] == 2) echo "checked"; ?> /> <?php _e('App Album', 'nxs_snap'); ?> .. <?php _e('or', 'nxs_snap'); ?> ..                                  

             <input value="1" id="apFBImgUplTML<?php echo $ii; ?>" type="radio" name="fb[<?php echo $ii; ?>][apFBImgUpl]" <?php if ((int)$options['imgUpl'] != 2) echo "checked"; ?> /> 

              <?php _e('Timeline', 'nxs_snap'); ?> &lt;-- (<a id="showShAtt" onmouseout="hidePopShAtt('<?php echo $ii; ?>I');" onmouseover="showPopShAtt('<?php echo $ii; ?>I', event);" onclick="return false;" class="underdash" href="http://www.nextscripts.com/blog/"><?php _e('What\'s the difference?', 'nxs_snap'); ?></a>)      

          </div>        

        

        <input type="radio" name="fb[<?php echo $ii; ?>][fbPostType]" value="A" <?php if ( !isset($options['fbPostType']) || $options['fbPostType'] == '' || $options['fbPostType'] == 'A') echo 'checked="checked"'; ?> /> <?php _e('Text Post with "attached" link', 'nxs_snap'); ?><br/>



<div style="width:100%; margin-left: 15px;"><strong><?php _e('Link attachment type', 'nxs_snap'); ?>:&nbsp;</strong> <input value="2"  id="apFBAttchShare<?php echo $ii; ?>" type="radio" name="fb[<?php echo $ii; ?>][apFBAttch]" <?php if ((int)$options['fbAttch'] == 2) echo "checked"; ?> /> 

                <?php _e('Share a link to your blogpost', 'nxs_snap'); ?> .. <?php _e('or', 'nxs_snap'); ?> ..                                  

               <input value="1"  id="apFBAttch<?php echo $ii; ?>" type="radio" name="fb[<?php echo $ii; ?>][apFBAttch]"  <?php if ((int)$options['fbAttch'] == 1) echo "checked"; ?> /> 

              <?php _e('Attach your blogpost', 'nxs_snap'); ?> &lt;-- (<a id="showShAtt" onmouseout="hidePopShAtt('<?php echo $ii; ?>');" onmouseover="showPopShAtt('<?php echo $ii; ?>', event);" onclick="return false;" class="underdash" href="http://www.nextscripts.com/blog/"><?php _e('What\'s the difference?', 'nxs_snap'); ?></a>)      

    <div style="margin-bottom: 5px; margin-left: 10px; "><input value="1"  id="apFBAttchAsVid" type="checkbox" name="fb[<?php echo $ii; ?>][apFBAttchAsVid]"  <?php if (isset($options['fbAttchAsVid']) && (int)$options['fbAttchAsVid'] == 1) echo "checked"; ?> />    <strong><?php _e('If post has video use it as an attachment thumbnail.', 'nxs_snap'); ?></strong> <i><?php _e('Video will be used for an attachment thumbnail instead of featured image. Only Youtube is supported at this time.', 'nxs_snap'); ?></i>

    <br/></div>

    

     <input value="1" id="useFBGURLInfo<?php echo $ii; ?>" <?php if (!empty($options['useFBGURLInfo']) && $options['useFBGURLInfo']=='1') echo "checked"; ?> onchange="if (jQuery(this).is(':checked')) { jQuery('#useFBGURLInfoDiv<?php echo $ii; ?>').hide(); }else jQuery('#useFBGURLInfoDiv<?php echo $ii; ?>').show();" type="checkbox" name="fb[<?php echo $ii; ?>][useFBGURLInfo]"/> <strong><?php _e('Let Facebook fill the link info', 'nxs_snap'); ?></strong>

     <i> - <?php _e('Recommended. Facebook will automatically take attached/shared link info from OG: tags or other sources.', 'nxs_snap'); ?> </i><br/>

     

     <div id="useFBGURLInfoDiv<?php echo $ii; ?>" style="<?php if (trim($options['useFBGURLInfo'])=='' || $options['useFBGURLInfo']=='1') echo "display:none;"; ?>" >&nbsp;&nbsp;&nbsp;     

    

      <strong><?php _e('Attachment Text Format', 'nxs_snap'); ?>:</strong><br/> 

      &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; <input value="1"  id="apFBMsgAFrmtA<?php echo $ii; ?>" <?php if (trim($options['fbMsgAFrmt'])=='') echo "checked"; ?> onchange="if (jQuery(this).is(':checked')) { jQuery('#apFBMsgAFrmtDiv<?php echo $ii; ?>').hide(); jQuery('#apFBMsgAFrmt<?php echo $ii; ?>').val(''); }else jQuery('#apFBMsgAFrmtDiv<?php echo $ii; ?>').show();" type="checkbox" name="fb[<?php echo $ii; ?>][apFBMsgAFrmtA]"/> <strong><?php _e('Auto', 'nxs_snap'); ?></strong>

      <i> - <?php _e('Recommended. Info from SEO Plugins will be used, then post excerpt, then post text', 'nxs_snap'); ?> </i><br/>

      <div id="apFBMsgAFrmtDiv<?php echo $ii; ?>" style="<?php if ($options['fbMsgAFrmt']=='') echo "display:none;"; ?>" >&nbsp;&nbsp;&nbsp; <?php _e('Set your own format', 'nxs_snap'); ?>:<input name="fb[<?php echo $ii; ?>][apFBMsgAFrmt]" id="apFBMsgAFrmt<?php echo $ii; ?>" style="width: 30%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['fbMsgAFrmt'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /><br/></div>

      

      </div>

      

</div><br/></div>

  

<div class="popShAtt" style="z-index: 9999; width: 350px;" id="popShAtt<?php echo $ii; ?>I"><h3><?php _e('Where to upload Images', 'nxs_snap'); ?></h3> <b><?php _e('(App Album)', 'nxs_snap'); ?></b> <?php _e('Facebook automatically creates an album for your app. Images will be grouped there as in any regular album.', 'nxs_snap'); ?>  <br/><br/><b><?php _e('(Timeline)', 'nxs_snap'); ?></b> <?php _e('Images will be posted to the special "Wall/Timeline" album and won\'t be grouped. "Wall/Timeline" album must exist. It\'s created when first image posted to timeline manually.', 'nxs_snap'); ?></div>  

<div class="popShAtt" style="z-index: 9999" id="popShAtt<?php echo $ii; ?>"><h3><?php _e('Two ways of attaching post on Facebook', 'nxs_snap'); ?></h3><img src="<?php echo $nxs_plurl; ?>img/fb2wops.png" width="600" height="257" alt="<?php _e('Two ways of attaching post on Facebook', 'nxs_snap'); ?>"/></div>

<div class="popShAtt" style="z-index: 9999" id="popShAtt<?php echo $ii; ?>X"><h3><?php _e('Facebook Post Types', 'nxs_snap'); ?></h3><img src="<?php echo $nxs_plurl; ?>img/fbPostTypesDiff6.png" width="600" height="398" alt="<?php _e('Facebook Post Types', 'nxs_snap'); ?>"/></div>







              

            <?php if ($options['fbPgID']!='') {?><div style="width:100%;"><strong>Facebook Page ID:</strong> <?php _e(apply_filters('format_to_edit', htmlentities($options['fbPgID'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?> </div><?php } ?>

            <?php 

            if($options['fbAppSec']=='') { ?>

            <b><?php _e('Authorize Your Facebook Account', 'nxs_snap'); ?></b> <?php _e('Please click "Update Settings" to be able to Authorize your account.', 'nxs_snap'); ?>

            <?php } else { if(isset($options['fbAppAuthUser']) && $options['fbAppAuthUser']>0) { ?>

            <?php _e('Your Facebook Account has been authorized.', 'nxs_snap'); ?> User ID: <?php _e(apply_filters('format_to_edit', htmlentities($options['fbAppAuthUser'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>.

            <?php _e('You can', 'nxs_snap'); ?> Re- <?php } ?>            

            <a href="https://www.facebook.com/dialog/oauth?client_id=<?php echo trim($options['fbAppID']);?>&client_secret=<?php echo trim($options['fbAppSec']);?>&scope=publish_stream,user_photos,photo_upload,friends_photos,offline_access,read_stream,manage_pages,user_groups,friends_groups&redirect_uri=<?php echo trim(urlencode($nxs_snapSetPgURL.'&auth=fb&acc='.$ii));?>">Authorize Your Facebook Account</a> 

            <?php if (!isset($options['fbAppAuthUser']) || $options['fbAppAuthUser']<1) { ?> <div class="blnkg">&lt;=== <?php _e('Authorize your account', 'nxs_snap'); ?> ===</div> 

            <br/><br/><i> <?php _e('If you get Facebook message:', 'nxs_snap'); ?> <b>"Error. An error occurred. Please try again later."</b> or <b>"Error 191"</b>  <?php _e('please make sure that domain name in your Facebook App matches your website domain exactly. Please note that www. and non www. versions are different domains.', 'nxs_snap'); ?></i> <?php }?>

            <?php } ?>

            

            <?php  if(isset($options['fbAppAuthUser']) && $options['fbAppAuthUser']>0) { ?>

            

            <br/><br/><b><?php _e('Test your settings', 'nxs_snap'); ?>:</b>&nbsp;&nbsp;&nbsp; <a href="#" class="NXSButton" onclick="testPost('FB','<?php echo $ii; ?>'); return false;"><?php printf( __( 'Submit Test Post to %s', 'nxs_snap' ), $nType); ?></a>         

            <?php }?>

    

     </div>

      <?php /* ######################## Tools Tab ####################### */ ?>

  <?php if (!$isNew) { ?>   <div id="nsx<?php echo $nt.$ii ?>_tab2" class="nsx_tab_content">

    

  <?php  nxs_showCatTagsCTFilters($nt, $ii, $options); 

      nxs_addPostingDelaySelV3($nt, $ii, $options['nHrs'], $options['nMin'], $options['nDays']);  ?>    

       

   <div style="width:100%;"><strong style="font-size: 16px;"><?php _e('Facebook Comments:', 'nxs_snap'); ?></strong> </div>

   <div style="margin-bottom: 5px; margin-left: 10px; ">

   <p style="font-size: 11px; margin: 0px;"><?php _e('Plugin could grab comments from Facebook and import them as Wordpress Comments', 'nxs_snap'); ?></p>

   

   <?php if ( $gOptions['riActive'] == '1' ) { ?>

   <input value="1"  id="apFBMsgAFrmtA<?php echo $ii; ?>" <?php if (trim($options['riComments'])=='1') echo "checked"; ?> type="checkbox" name="fb[<?php echo $ii; ?>][riComments]"/> <strong><?php _e('Import Facebook Comments', 'nxs_snap'); ?></strong>

   <br/>

   <div style="margin-bottom: 5px; margin-left: 10px; ">

   <input value="1"  id="apFBMsgAFrmtA<?php echo $ii; ?>" <?php if (trim($options['riCommentsAA'])=='1') echo "checked"; ?> type="checkbox" name="fb[<?php echo $ii; ?>][riCommentsAA]"/> <strong><?php _e('Auto-approve imported comments', 'nxs_snap'); ?></strong></div>

   

   <?php } else { echo "<br/>"; _e('Please activate the "Comments Import" from SNAP Settings Tab', 'nxs_snap'); } ?>

   

   </div>

  

  <?php  nxs_showRepostSettings($nt, $ii, $options); ?> 

            

            

    </div> <?php } ?> <?php /* #### End of Tab #### */ ?>

    </div><br/> <?php /* #### End of Tabs #### */ ?>

    

    <div class="submit nxclear" style="padding-bottom: 0px;"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'nxs_snap') ?>" /></div>

            

          </div>        

        <?php

      

  }

  //#### Set Unit Settings from POST

  function setNTSettings($post, $options){ $code = 'FB'; $lcode = 'fb';

    foreach ($post as $ii => $pval){ 

      if (isset($pval['apFBAppID']) && $pval['apFBAppID']!='') { if (!isset($options[$ii])) $options[$ii] = array();

        if (isset($pval['apDoFB']))         $options[$ii]['doFB'] = $pval['apDoFB']; else $options[$ii]['doFB'] = 0;

        if (isset($pval['nName']))          $options[$ii]['nName'] = trim($pval['nName']);

        if (isset($pval['apFBAppID']))      $options[$ii]['fbAppID'] = trim($pval['apFBAppID']);                                

        if (isset($pval['apFBAppSec']))     $options[$ii]['fbAppSec'] = trim($pval['apFBAppSec']);        

        

        if (isset($pval['catSel'])) $options[$ii]['catSel'] = trim($pval['catSel']); else $options[$ii]['catSel'] = 0;

        if ($options[$ii]['catSel']=='1' && trim($pval['catSelEd'])!='') $options[$ii]['catSelEd'] = trim($pval['catSelEd']); else $options[$ii]['catSelEd'] = '';

        

        if (isset($pval['fbPostType']))     $options[$ii]['fbPostType'] = trim($pval['fbPostType']);

        if (isset($pval['apFBAttch']))      $options[$ii]['fbAttch'] = $pval['apFBAttch']; else $options[$ii]['fbAttch'] = 0;

        if (isset($pval['apFBAttchAsVid'])) $options[$ii]['fbAttchAsVid'] = $pval['apFBAttchAsVid']; else $options[$ii]['fbAttchAsVid'] = 0;

        

        if (isset($pval['apFBImgUpl']))     $options[$ii]['imgUpl'] = $pval['apFBImgUpl']; else $options[$ii]['imgUpl'] = 0;

        

        if (isset($pval['apFBMsgFrmt']))    $options[$ii]['fbMsgFormat'] = trim($pval['apFBMsgFrmt']); 

        if (isset($pval['apFBMsgAFrmt']))    $options[$ii]['fbMsgAFrmt'] = trim($pval['apFBMsgAFrmt']); 

        

        if (isset($pval['useFBGURLInfo']))     $options[$ii]['useFBGURLInfo'] = $pval['useFBGURLInfo']; else $options[$ii]['useFBGURLInfo'] = 0;     

        

        if (isset($pval['riComments']))      $options[$ii]['riComments'] = $pval['riComments']; else $options[$ii]['riComments'] = 0;

        if (isset($pval['riCommentsAA']))    $options[$ii]['riCommentsAA'] = $pval['riCommentsAA']; else $options[$ii]['riCommentsAA'] = 0;

        

        $options[$ii] = nxs_adjRpst($options[$ii], $pval);       

        

        if (isset($pval['delayDays'])) $options[$ii]['nDays'] = trim($pval['delayDays']);

        if (isset($pval['delayHrs'])) $options[$ii]['nHrs'] = trim($pval['delayHrs']); if (isset($pval['delayMin'])) $options[$ii]['nMin'] = trim($pval['delayMin']); 

        

        if (isset($pval['qTLng'])) $options[$ii]['qTLng'] = trim($pval['qTLng']); 

                

        if (isset($pval['apFBURL']))  {  $options[$ii]['fbURL'] = trim($pval['apFBURL']);   if ( substr($options[$ii]['fbURL'], 0, 4)!='http' )  $options[$ii]['fbURL'] = 'http://'.$options[$ii]['fbURL'];

          $fbPgID = $options[$ii]['fbURL']; if (substr($fbPgID, -1)=='/') $fbPgID = substr($fbPgID, 0, -1);  $fbPgID = substr(strrchr($fbPgID, "/"), 1); 

          if (strpos($fbPgID, '?')!==false) $fbPgID = substr($fbPgID, 0, strpos($fbPgID, '?')); 

          $options[$ii]['fbPgID'] = $fbPgID; //echo $fbPgID;

          if (strpos($options[$ii]['fbURL'], '?')!==false) $options[$ii]['fbURL'] = substr($options[$ii]['fbURL'], 0, strpos($options[$ii]['fbURL'], '?'));// prr($pval); prr($options[$ii]); // die();

        }                  

      } elseif ((isset($pval['catSel'])) && $pval['catSel']=='X' && (isset($pval['apDoFB'])) && $pval['apDoFB']=='1') $options[$ii]['catSel'] = trim($pval['catSel']);

    } return $options;

  } 

  //#### Show Post->Edit Meta Box Settings

  function showEdPostNTSettings($ntOpts, $post){ global $nxs_plurl; $post_id = $post->ID;  $nt = 'fb'; $ntU = 'FB'; 

    foreach($ntOpts as $ii=>$ntOpt)  { $pMeta = maybe_unserialize(get_post_meta($post_id, 'snapFB', true));  if (is_array($pMeta) && isset($pMeta[$ii]) && is_array($pMeta[$ii])) $ntOpt = $this->adjMetaOpt($ntOpt, $pMeta[$ii]); 

        if (empty($ntOpt['imgToUse'])) $ntOpt['imgToUse'] = '';  if (empty($ntOpt['urlToUse'])) $ntOpt['urlToUse'] = '';

        $doFB = $ntOpt['doFB'] && (is_array($pMeta) || $ntOpt['catSel']!='1');        

        $imgToUse = $ntOpt['imgToUse'];  $urlToUse = $ntOpt['urlToUse']; 

        $isAvailFB =  $ntOpt['fbURL']!='' && $ntOpt['fbAppID']!='' && $ntOpt['fbAppSec']!=''; $isAttachFB = $ntOpt['fbAttch']; $fbMsgFormat = htmlentities($ntOpt['fbMsgFormat'], ENT_COMPAT, "UTF-8"); $fbPostType = $ntOpt['fbPostType'];

      ?>  

      

      <tr><th style="text-align:left;" colspan="2"> 

      <?php if ($ntOpt['catSel']=='1' && trim($ntOpt['catSelEd'])!='')  { ?> <input type="hidden" class="nxs_SC" id="nxs_SC_<?php echo $ntU; ?><?php echo $ii; ?>" value="<?php echo $ntOpt['catSelEd']; ?>" /> <?php } ?>

      <?php if (!empty($ntOpt['tagsSelX'])) { ?>  <input type="hidden" class="nxs_TG" id="nxs_TG_<?php echo $ntU; ?><?php echo $ii; ?>" value="<?php echo $ntOpt['tagsSelX']; ?>" /> <?php } ?>

      

      <?php if ($isAvailFB) { ?><input class="nxsGrpDoChb" value="1" id="doFB<?php echo $ii; ?>" <?php if ($post->post_status == "publish") echo 'disabled="disabled"';?> type="checkbox" name="fb[<?php echo $ii; ?>][doFB]" <?php if ((int)$doFB == 1) echo 'checked="checked" title="def"';  ?> /> 

      <?php if ($post->post_status == "publish") { ?> <input type="hidden" name="fb[<?php echo $ii; ?>][doFB]" value="<?php echo $doFB;?>"> <?php } ?> <?php } ?>

      

      <div class="nsx_iconedTitle" style="display: inline; font-size: 13px; background-image: url(<?php echo $nxs_plurl; ?>img/fb16.png);">Facebook - <?php _e('publish to', 'nxs_snap') ?> (<i style="color: #005800;"><?php echo $ntOpt['nName']; ?></i>)</div></th><td><?php //## Only show RePost button if the post is "published"

    if ($post->post_status == "publish" && $isAvailFB) { ?>

    

    <input alt="<?php echo $ii; ?>" style="float: right;margin-left: 10px" onmouseout="hidePopShAtt('SV');" onmouseover="showPopShAtt('SV', event);" onclick="return false;" type="button" class="button" name="rePostToFB_repostButton" id="rePostToFB_button" value="<?php _e('Repost to Facebook', 'nxs_snap') ?>" />

    <?php if ($ntOpt['riComments']=='1' && (is_array($pMeta) && is_array($pMeta[$ii]) && isset($pMeta[$ii]['pgID']) && strpos($pMeta[$ii]['pgID'],'_')!==false ) ) { ?>

       <input alt="<?php echo $ii; ?>" style="float: right; " onclick="return false;" type="button" class="button" name="riToFB_repostButton" id="riToFB_button" value="<?php _e('Import Comments from Facebook', 'nxs_snap') ?>" />

    <?php } ?>

    

                    <?php } ?>

                    

                    <?php  if (is_array($pMeta) && isset($pMeta[$ii]) && is_array($pMeta[$ii]) && isset($pMeta[$ii]['pgID']) && strpos($pMeta[$ii]['pgID'],'_')!==false ) { $pid = explode('_', $pMeta[$ii]['pgID']);

                        

                        ?> <span id="pstdFB<?php echo $ii; ?>" style="float: right;padding-top: 4px; padding-right: 10px;">

                      <a style="font-size: 10px;" href="http://www.facebook.com/permalink.php?story_fbid=<?php echo $pid[1].'&id='.$pid[0]; ?>" target="_blank"><?php $nType="Facebook"; printf( __( 'Posted on', 'nxs_snap' ), $nType); ?>  <?php echo (isset($pMeta[$ii]['pDate']) && $pMeta[$ii]['pDate']!='')?(" (".$pMeta[$ii]['pDate'].")"):""; ?></a>

                    </span><?php } ?>

                    

                </td></tr>

                <?php if (!$isAvailFB) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup and Authorize your Facebook Account to AutoPost to Facebook</b>

                <?php }  else { if ($post->post_status != "publish" && function_exists('nxs_doSMAS5') ) { $ntOpt['postTime'] = get_post_time('U', false, $post_id); nxs_doSMAS5($nt, $ii, $ntOpt); }  ?>

                

                <?php if ($ntOpt['rpstOn']=='1') { ?> 

                

                <tr id="altFormat1" style=""><th scope="row" class="nxsTHRow">

                <input value="0"  type="hidden" name="<?php echo $nt; ?>[<?php echo $ii; ?>][rpstPostIncl]"/><input value="nxsi<?php echo $ii; ?>fb" type="checkbox" name="<?php echo $nt; ?>[<?php echo $ii; ?>][rpstPostIncl]"  <?php if (!empty($ntOpt['rpstPostIncl'])) echo "checked"; ?> /> 

                </th>

                <td> <?php _e('Include in "Auto-Reposting" to this network.', 'nxs_snap') ?>               

                </td></tr> <?php } ?>

                

                <tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;">

                  

                  <b></b>

                </th>

                <td></td>

                </tr>

                

             <tr><th scope="row" style="text-align:right; width:150px; vertical-align:top; padding-top: 0px; padding-right:10px;"> <?php _e('Post Type:', 'nxs_snap'); ?> <br/>

                (<a id="showShAtt" style="font-weight: normal" onmouseout="hidePopShAtt('<?php echo $ii; ?>X');" onmouseover="showPopShAtt('<?php echo $ii; ?>X', event);" onclick="return false;" class="underdash" href="http://www.nextscripts.com/blog/"><?php _e('What\'s the difference?', 'nxs_snap'); ?></a>)</th><td>     

        

        <input type="radio" name="fb[<?php echo $ii; ?>][PostType]" value="T" <?php if ($fbPostType == 'T') echo 'checked="checked"'; ?> /> <?php _e('Text Post', 'nxs_snap'); ?>  - <i><?php _e('just text message', 'nxs_snap'); ?></i><br/>       

        <input type="radio" name="fb[<?php echo $ii; ?>][PostType]" value="I" <?php if ($fbPostType == 'I') echo 'checked="checked"'; ?> /> <?php _e('Image Post', 'nxs_snap'); ?> - <i><?php _e('big image with text message', 'nxs_snap'); ?></i><br/>             

        <input type="radio" name="fb[<?php echo $ii; ?>][PostType]" value="A" <?php if ( !isset($fbPostType) || $fbPostType == '' || $fbPostType == 'A') echo 'checked="checked"'; ?> /> <?php _e('Text Post with "attached" blogpost', 'nxs_snap'); ?> &lt;-- (<a id="showShAtt" onmouseout="hidePopShAtt('<?php echo $ii; ?>');" onmouseover="showPopShAtt('<?php echo $ii; ?>', event);" onclick="return false;" class="underdash" href="http://www.nextscripts.com/blog/"><?php _e('What\'s the difference?', 'nxs_snap'); ?></a>) <br/>



<div style="width:100%; margin-left: 25px;"><strong><?php _e('Link attachment type:', 'nxs_snap'); ?>&nbsp;</strong> <input value="2"  id="apFBAttchShare<?php echo $ii; ?>" onchange="doSwitchShAtt(0,<?php echo $ii; ?>);" type="radio" name="fb[<?php echo $ii; ?>][AttachPost]" <?php if ((int)$isAttachFB == 2) echo "checked"; ?> /> 

                <?php _e('Share a link to your blogpost', 'nxs_snap'); ?> .. <?php _e('or', 'nxs_snap'); ?> ..                                  

               <input value="1"  id="apFBAttch<?php echo $ii; ?>" onchange="doSwitchShAtt(1,<?php echo $ii; ?>);" type="radio" name="fb[<?php echo $ii; ?>][AttachPost]"  <?php if ((int)$isAttachFB == 1) echo "checked"; ?> /> 

              <?php _e('Attach your blogpost', 'nxs_snap'); ?>          

</div> 

<div class="popShAtt" id="popShAtt<?php echo $ii; ?>"><h3><?php _e('Two ways of attaching post on Facebook', 'nxs_snap'); ?></h3> <img src="<?php echo $nxs_plurl; ?>img/fb2wops.png" width="600" height="257" alt="<?php _e('Two ways of attaching post on Facebook', 'nxs_snap'); ?>"/></div>

<div class="popShAtt" id="popShAtt<?php echo $ii; ?>X"><h3><?php _e('Facebook Post Types', 'nxs_snap'); ?></h3><img src="<?php echo $nxs_plurl; ?>img/fbPostTypesDiff6.png" width="600" height="398" alt="<?php _e('Facebook Post Types', 'nxs_snap'); ?>"/></div>

     </td></tr>

                              

                    

                <tr id="altFormat1" style=""><th scope="row" style="vertical-align:top; padding-top: 6px; text-align:right; width:60px; padding-right:10px;"><?php _e('Message Format:', 'nxs_snap') ?></th>

                <td>

                <?php if (1==1) { ?>

                <textarea cols="150" rows="2" id="fb<?php echo $ii; ?>SNAPformat" name="fb[<?php echo $ii; ?>][SNAPformat]"  style="width:60%;max-width: 610px;" onfocus="jQuery('#fb<?php echo $ii; ?>SNAPformat').attr('rows', 4); jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apFBTMsgFrmt<?php echo $ii; ?>');"><?php echo $fbMsgFormat ?></textarea>

                <?php } else { ?>

                <input value="<?php echo $fbMsgFormat ?>" type="text" name="fb[<?php echo $ii; ?>][SNAPformat]"  style="width:60%;max-width: 610px;" onfocus="jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apFBTMsgFrmt<?php echo $ii; ?>');"/><?php nxs_doShowHint("apFBTMsgFrmt".$ii, '', '58'); ?>

                <?php } ?>

                </td></tr>

                <?php /* ## Select Image & URL ## */ nxs_showImgToUseDlg($nt, $ii, $imgToUse); nxs_showURLToUseDlg($nt, $ii, $urlToUse);

     } 

    }

      

  }

  

  function adjMetaOpt($optMt, $pMeta){ if (isset($pMeta['isPosted'])) $optMt['isPosted'] = $pMeta['isPosted']; else  $optMt['isPosted'] = '';

     if (isset($pMeta['SNAPformat'])) $optMt['fbMsgFormat'] = $pMeta['SNAPformat']; 

     if (isset($pMeta['imgToUse'])) $optMt['imgToUse'] = $pMeta['imgToUse']; if (isset($pMeta['urlToUse'])) $optMt['urlToUse'] = $pMeta['urlToUse']; 

     if (isset($pMeta['timeToRun']))  $optMt['timeToRun'] = $pMeta['timeToRun'];  if (isset($pMeta['rpstPostIncl']))  $optMt['rpstPostIncl'] = $pMeta['rpstPostIncl'];    

     if (isset($pMeta['AttachPost'])) $optMt['fbAttch'] = ($pMeta['AttachPost'] != '')?$pMeta['AttachPost']:0; else { if (isset($pMeta['SNAPformat'])) $optMt['fbAttch'] = 0; } 

     if (isset($pMeta['PostType'])) $optMt['fbPostType'] = ($pMeta['PostType'] != '')?$pMeta['PostType']:0; else { if (isset($pMeta['SNAPformat'])) $optMt['fbPostType'] = 'T'; } 

     if (isset($pMeta['doFB'])) $optMt['doFB'] = $pMeta['doFB'] == 1?1:0; else { if (isset($pMeta['SNAPformat'])) $optMt['doFB'] = 0; }      

     if (isset($pMeta['SNAPincludeFB']) && $pMeta['SNAPincludeFB'] == '1' ) $optMt['doFB'] = 1;   // <2.6 Compatibility fix    

     return $optMt;

  }

}}



if (!function_exists("nxs_getBackFBComments")) { function nxs_getBackFBComments($postID, $options, $po) { require_once ('apis/facebook.php');  if (empty($options['fbAppPageAuthToken'])) return;

    $opts = array('access_token'  => $options['fbAppPageAuthToken']);    

    $facebook = new NXS_Facebook(array( 'appId' => $options['fbAppID'], 'secret' => $options['fbAppSec'], 'cookie' => true ));  $ci = 0;    

    $ret = $facebook->api($po['pgID']."/comments?filter=toplevel", "GET", $opts);

    $impCmnts = get_post_meta($postID, 'snapImportedFBComments', true); if (!is_array($impCmnts)) $impCmnts = array(); //prr($impCmnts);   

    if (is_array($ret) && is_array($ret['data'])) foreach ($ret['data'] as $comment){ $cid = $comment['id']; if (trim($cid)=='') continue;

      if (!in_array('fbxcw'.$cid, $impCmnts)) {  $authData = $facebook->api($comment['from']['id'], "GET", $opts);  

          $commentdata = array( 'comment_post_ID' => $postID, 'comment_author' => $comment['from']['name'], 'comment_author_email' => $comment['from']['id'].'@facebook.com', 

            'comment_author_url' => $authData['link'], 'comment_content' => $comment['message'], 'comment_date_gmt' => date('Y-m-d H:i:s', strtotime( $comment['created_time'] ) ), 'comment_type' => '');

           //prr($commentdata);

          $wpCid = nxs_postNewComment($commentdata, $options['riCommentsAA']=='1'); $ci++; $impCmnts[$wpCid] = 'fbxcw'.$cid; 

      } else $wpCid = array_search('fbxcw'.$cid, $impCmnts);

      $replRet = $facebook->api($cid."/comments", "GET", $opts); 

      if (is_array($replRet) && is_array($replRet['data'])) foreach ($replRet['data'] as $rComment){ $rCid = $rComment['id']; 

        if (trim($rCid)!='' && !in_array('fbxcw'.$rCid, $impCmnts)) {  // prr($impCmnts);

          $authData = $facebook->api($rComment['from']['id'], "GET", $opts);  

          $commentdata = array( 'comment_parent' => $wpCid, 'comment_post_ID' => $postID, 'comment_author' => $rComment['from']['name'], 'comment_author_email' => $rComment['from']['id'].'@facebook.com', 

            'comment_author_url' => $authData['link'], 'comment_content' => $rComment['message'], 'comment_date_gmt' => date('Y-m-d H:i:s', strtotime( $rComment['created_time'] ) ), 'comment_type' => '');

          // prr($commentdata);

          nxs_postNewComment($commentdata, $options['riCommentsAA']=='1'); $ci++;   $impCmnts[] = 'fbxcw'.$rCid; 

        }

      }        

    }    

    delete_post_meta($postID, 'snapImportedFBComments'); add_post_meta($postID, 'snapImportedFBComments', $impCmnts ); 

    //## if Importing manually from Button echo result.

    if (isset($_POST['id']) && $_POST['id']!='') printf( _n( '%d comment has been imported.', '%d comments has been imported.', $ci, 'nxs_snap'), $ci );

}}



// ShortCode [nxs_fbembed accnum=0]

function nxs_fbembed_func( $atts ) { extract( shortcode_atts( array('accnum' => '0'), $atts ) );  $pid = get_the_ID(); $fbpo =  get_post_meta($pid, 'snapFB', true); $fbpo =  maybe_unserialize($fbpo);     

  if (!is_array($fbpo) || !is_array($fbpo[$accnum]) || !isset($fbpo[$accnum]['pgID']) || strpos($fbpo[$accnum]['pgID'], '_')===false ) return; $fbpo = $fbpo[$accnum]['pgID']; 

  $fbpoA = explode('_',$fbpo);  $fpg = $fbpoA[0];  $fpid = $fbpoA[1]; 

  $txtOut = '<div id="fb-root"></div> <script>(function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = "//connect.facebook.net/en_US/all.js#xfbml=1"; fjs.parentNode.insertBefore(js, fjs); }(document, \'script\', \'facebook-jssdk\'));</script>

<div class="fb-post" data-href="https://www.facebook.com/permalink.php?story_fbid='.$fpid.'&amp;id='.$fpg.'"><div class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/permalink.php?story_fbid='.$fpid.'&amp;id='.$fpg.'">Post</a></div></div>';

  return $txtOut;

}

add_shortcode( 'nxs_fbembed', 'nxs_fbembed_func' );



if (!function_exists("nxs_rePostToFB_ajax")) { function nxs_rePostToFB_ajax() { check_ajax_referer('nxsSsPageWPN');  $postID = $_POST['id']; // $result = nsPublishTo($id, 'FB', true);   

      $options = get_option('NS_SNAutoPoster');  foreach ($options['fb'] as $ii=>$fbo) if ($ii==$_POST['nid']) {  $fbo['ii'] = $ii; $fbo['pType'] = 'aj';

      $fbpo =  get_post_meta($postID, 'snapFB', true); /* echo $postID."|"; echo $fbpo; */ $fbpo =  maybe_unserialize($fbpo); // prr($fbpo); 

      if (is_array($fbpo) && isset($fbpo[$ii]) && is_array($fbpo[$ii]) ){ $ntClInst = new nxs_snapClassFB(); $fbo = $ntClInst->adjMetaOpt($fbo, $fbpo[$ii]); } //prr($fbo);

      if (isset($_POST['ri']) && $_POST['ri']=='1') { nxs_getBackFBComments($postID, $fbo, $fbpo[$ii]); die(); } else {

        $result = nxs_doPublishToFB($postID, $fbo); if ($result == '200') die("Your post has been successfully sent to FaceBook."); else die($result);

      }

    }    

  }

}



if (!function_exists("nxs_doPublishToFB")) { //## Second Function to Post to FB

  function nxs_doPublishToFB($postID, $options){ global $ShownAds; $ntCd = 'FB'; $ntCdL = 'fb'; $ntNm = 'Facebook'; $dsc = ''; $vidURL = ''; 

    if (!is_array($options)) $options = maybe_unserialize(get_post_meta($postID, $options, true));

    if (!class_exists('nxs_class_SNAP_FB')) { nxs_addToLogN('E', 'Error', $ntCd, '-=ERROR=- No Facebook API Lib Detected', ''); return "No Facebook API Lib Detected";}

    

    $fbWhere = 'feed'; $page_id = $options['fbPgID']; if (isset($ShownAds)) $ShownAdsL = $ShownAds;  $addParams = nxs_makeURLParams(array('NTNAME'=>$ntNm, 'NTCODE'=>$ntCd, 'POSTID'=>$postID, 'ACCNAME'=>$options['nName']));

    //## Some Common stuff 

    $ii = $options['ii']; if (!isset($options['pType'])) $options['pType'] = 'im'; if ($options['pType']=='sh') sleep(rand(1, 10)); 

    $logNT = '<span style="color:#0000FF">Facebook</span> - '.$options['nName'];

    $snap_ap = get_post_meta($postID, 'snap'.$ntCd, true); $snap_ap = maybe_unserialize($snap_ap);     

    if ($options['pType']!='aj' && is_array($snap_ap) && (nxs_chArrVar($snap_ap[$ii], 'isPosted', '1') || nxs_chArrVar($snap_ap[$ii], 'isPrePosted', '1'))) {

      $snap_isAutoPosted = get_post_meta($postID, 'snap_isAutoPosted', true); if ($snap_isAutoPosted!='2') { 

         nxs_addToLogN('W', 'Notice', $logNT, '-=Duplicate=- Post ID:'.$postID, 'Already posted. No reason for posting duplicate'.' |'.$options['pType']); return;

      }

    }      

    //## Make the post

    if (isset($options['qTLng'])) $lng = $options['qTLng']; else $lng = '';      if (!isset($options['fbAppPageAuthToken'])) $options['fbAppPageAuthToken'] = '';

    $blogTitle = htmlspecialchars_decode(get_bloginfo('name'), ENT_QUOTES); if ($blogTitle=='') $blogTitle = home_url();        

    //## Initiate Posting Array

    $message = array('message'=>'', 'link'=>'', 'title'=>'', 'description'=>'', 'imageURL'=>'', 'videoURL'=>'', 'siteName'=>$blogTitle);    

    

    if ($postID=='0') { $options['fbMsgFormat'] = 'Test Post, Please Ignore'; $imgURL = '';

      $dsc = 'Test Post, Description';  $urlTitle = 'Test Post - Title';  $urlToGo = home_url();    

    } else { $post = get_post($postID); if(!$post) return; 
	
	/**
	  *
	  *
	  *
	  *
	  *
	  *
	  *
	  *
	  */
    $show= '';
    $cats= wp_get_post_categories($postID);
	  if(in_array(27,$cats)){$show='3 Bières';}
    elseif(in_array(6,$cats)){$show='Boulevard Brutal';}
    elseif(in_array(8,$cats)){$show='Fun-Regarder-Films';}
    elseif(in_array(25,$cats)){$show='Horreur Gamer';}
    elseif(in_array(24,$cats)){$show='L\'Épee Légendaire';}
    elseif(in_array(28,$cats)){$show='La Commission des Geekeurs';}
    elseif(in_array(7,$cats)){$show='La Soirée du Podcast';}
    elseif(in_array(5,$cats)){$show='Le Dernier des Podcasts';}
    elseif(in_array(26,$cats)){$show='Le Shack À Boisson';}
    elseif(in_array(3,$cats)){$show='Les Mystérieux Étonnants';}
    elseif(in_array(22,$cats)){$show='MacQuébec';}
    elseif(in_array(23,$cats)){$show='Objectif Numérique';}
    elseif(in_array(21,$cats)){$show='Pod Probleme';}
    elseif(in_array(4,$cats)){$show='Rétro Nouveau';}
    elseif(in_array(1191,$cats)){$show='AlcoJeux';}
    elseif(in_array(1195,$cats)){$show='Des Si Et Des Rais';}
    elseif(in_array(1192,$cats)){$show='Geek-O-Rama';}
    elseif(in_array(1194,$cats)){$show='Je Joue Le Jeu';}
    elseif(in_array(1196,$cats)){$show='L\'erreur 400 Cast';}
    elseif(in_array(1197,$cats)){$show='Point De Vues';}
    elseif(in_array(1198,$cats)){$show='Souper De Filles';}
    elseif(in_array(1190,$cats)){$show='Testeur Alpha';}

    $msg1= $show . ' : ' . $post->post_title . ' ' . get_permalink($postID);
	
	$msg = nsFormatMessage($msg1, $postID, $addParams); 

	

      $fbPostType = $options['fbPostType'];  if ($fbPostType=='A') $fbPostType = (int)$options['fbAttch']==2?'S':'A';  $isAttachVidFB = $options['fbAttchAsVid'];

      nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPrePosted'=>'1'));  

      $extInfo = ' | PostID: '.$postID." - ".(isset($post) && is_object($post)?nxs_doQTrans($post->post_title, $lng):'').' |'.$options['pType'];

      if ($fbPostType=='A') $imgURL = nxs_getPostImage($postID, 'medium'); // prr($options); echo "PP - ".$postID; prr($src);      

      if ($fbPostType=='I' || $fbPostType=='S') $imgURL = nxs_getPostImage($postID, 'full'); // prr($options); echo "PP - ".$postID; prr($src);                  

      if (($fbPostType=='A' || $fbPostType=='S')){

        //## AUTO - Get Post Descr from SEO Plugins or make it.      

        if (trim($options['fbMsgAFrmt'])!='') {$dsc = nsFormatMessage($options['fbMsgAFrmt'], $postID, $addParams);} else { if (function_exists('aioseop_mrt_fix_meta') && $dsc=='')  $dsc = trim(get_post_meta($postID, '_aioseop_description', true)); 

          if (function_exists('wpseo_admin_init') && $dsc=='') $dsc = trim(get_post_meta($postID, '_yoast_wpseo_opengraph-description', true));  

          if (function_exists('wpseo_admin_init') && $dsc=='') $dsc = trim(get_post_meta($postID, '_yoast_wpseo_metadesc', true));      

          if ($dsc=='') $dsc = trim(apply_filters('the_content', nxs_doQTrans($post->post_excerpt, $lng)));  if ($dsc=='') $dsc = trim(nxs_doQTrans($post->post_excerpt, $lng)); 

          if ($dsc=='') $dsc = trim(apply_filters('the_content', nxs_doQTrans($post->post_content, $lng)));  if ($dsc=='') $dsc = trim(nxs_doQTrans($post->post_content, $lng));  

          if ($dsc=='') $dsc = get_bloginfo('description'); 

        }      

        $dsc = strip_tags(strip_shortcodes($dsc)); $dsc = nxs_decodeEntitiesFull($dsc); $dsc = nsTrnc($dsc, 900, ' ');

      }

      

      $msg = str_replace('<br>', "\n", $msg); $msg = str_replace('<br/>', "\n", $msg); $msg = str_replace('<br />', "\n", $msg);        

      $msg = str_ireplace('<3','&lt;3', $msg); $msg = str_ireplace('<(','&lt;(', $msg);  //## FB Smiles FIX.            

      $msg = strip_tags($msg); $msg = nxs_decodeEntitiesFull($msg);       

      $msg = str_ireplace('&#039;',"'", $msg); $msg = str_ireplace('&039;',"'", $msg); $msg = str_ireplace('&#39;',"'", $msg); $msg = str_ireplace('<3','&lt;3', $msg); $msg = str_ireplace('<(','&lt;(', $msg);  //## FB Smiles FIX 2.            

      

      if ($isAttachVidFB=='1') {$vids = nsFindVidsInPost($post, false); if (count($vids)>0) { 

          if (strlen($vids[0])==11) { $vidURL = 'http://www.youtube.com/v/'.$vids[0]; $imgURL = nsGetYTThumb($vids[0]); }

          if (strlen($vids[0])==8) { $vidURL = 'https://secure.vimeo.com/moogaloop.swf?clip_id='.$vids[0].'&autoplay=1';            

            $apiURL = "http://vimeo.com/api/v2/video/".$vids[0].".json?callback=showThumb"; $json = wp_remote_get($apiURL);

            if (!is_wp_error($json)) { $json = $json['body']; $json = str_replace('showThumb(','',$json); $json = str_replace('])',']',$json);  $json = json_decode($json, true); $imgURL = $json[0]['thumbnail_large']; }           

          }

      }}

      if (trim($options['imgToUse'])!='') $imgURL = $options['imgToUse'];  if (preg_match("/noImg.\.png/i", $imgURL)) $imgURL = 'http://www.noimage.faketld';//$imgURL = 'http://cdn.gtln.us/img/t1x1.gif'; 

      

      //## MyURL - URLToGo code

      if (!isset($options['urlToUse']) || trim($options['urlToUse'])=='') $myurl = trim(get_post_meta($postID, 'snap_MYURL', true)); if ($myurl!='') $options['urlToUse'] = $myurl;

      if (isset($options['urlToUse']) && trim($options['urlToUse'])!='') { $urlToGo = $options['urlToUse']; $options['useFBGURLInfo'] = true; } else $urlToGo = get_permalink($postID);    

      if($addParams!='') $urlToGo .= (strpos($urlToGo,'?')!==false?'&':'?').$addParams; 

      //prr($options);

		/**
	  *
	  *
	  *
	  *
	  *
	  *
	  *
	  *
	  */
    $show= '';
    $cats= wp_get_post_categories($postID);
	  if(in_array(27,$cats)){$show='3 Bières';}
    elseif(in_array(6,$cats)){$show='Boulevard Brutal';}
    elseif(in_array(8,$cats)){$show='Fun-Regarder-Films';}
    elseif(in_array(25,$cats)){$show='Horreur Gamer';}
    elseif(in_array(24,$cats)){$show='L\'Épee Légendaire';}
    elseif(in_array(28,$cats)){$show='La Commission des Geekeurs';}
    elseif(in_array(7,$cats)){$show='La Soirée du Podcast';}
    elseif(in_array(5,$cats)){$show='Le Dernier des Podcasts';}
    elseif(in_array(26,$cats)){$show='Le Shack À Boisson';}
    elseif(in_array(3,$cats)){$show='Les Mystérieux Étonnants';}
    elseif(in_array(22,$cats)){$show='MacQuébec';}
    elseif(in_array(23,$cats)){$show='Objectif Numérique';}
    elseif(in_array(21,$cats)){$show='Pod Probleme';}
    elseif(in_array(4,$cats)){$show='Rétro Nouveau';}
    elseif(in_array(1191,$cats)){$show='AlcoJeux';}
    elseif(in_array(1195,$cats)){$show='Des Si Et Des Rais';}
    elseif(in_array(1192,$cats)){$show='Geek-O-Rama';}
    elseif(in_array(1194,$cats)){$show='Je Joue Le Jeu';}
    elseif(in_array(1196,$cats)){$show='L\'erreur 400 Cast';}
    elseif(in_array(1197,$cats)){$show='Point De Vues';}
    elseif(in_array(1198,$cats)){$show='Souper De Filles';}
    elseif(in_array(1190,$cats)){$show='Testeur Alpha';}

    $msg= $show . ' : ' . $post->post_title . ' ' . get_permalink($postID);

      $urlTitle = nxs_doQTrans($post->post_title, $lng);  $options['fbMsgFormat'] = $msg;    $urlTitle = strip_tags(strip_shortcodes($urlTitle));

    }  // prr($mssg); // prr($options);  //   prr($facebook); echo "/$page_id/feed";

	

    $message = array('url'=>$urlToGo, 'urlTitle'=>$urlTitle, 'urlDescr'=>$dsc, 'imageURL'=>$imgURL, 'videoURL'=>$vidURL, 'siteName'=>$blogTitle);  

      if (isset($ShownAds)) $ShownAds = $ShownAdsL; // FIX for the quick-adsense plugin

      

    //## Actual Post

    $ntToPost = new nxs_class_SNAP_FB(); $ret = $ntToPost->doPostToNT($options, $message);

    //## Process Results

    if (!is_array($ret) || $ret['isPosted']!='1') { //## Error 

         if ($postID=='0') prr($ret); nxs_addToLogN('E', 'Error', $logNT, '-=ERROR=- '.print_r($ret, true), $extInfo); 

    } else {  // ## All Good - log it.

      if ($postID=='0')  { nxs_addToLogN('S', 'Test', $logNT, 'OK - TEST Message Posted '); echo _e('OK - Message Posted, please see your '.$logNT.' Page. ', 'nxs_snap'); } 

        else  { nxs_addToRI($postID); nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPosted'=>'1', 'pgID'=>$ret['postID'], 'pDate'=>date('Y-m-d H:i:s'))); nxs_addToLogN('S', 'Posted', $logNT, 'OK - Message Posted ', $extInfo); }

    }

    //## Return Result

    if ($ret['isPosted']=='1') return 200; else return print_r($ret, true);     

  }  

}



?>
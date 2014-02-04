<?php    

//## NextScripts Twitter Connection Class

$nxs_snapAvNts[] = array('code'=>'TW', 'lcode'=>'tw', 'name'=>'Twitter');



if (!class_exists("nxs_snapClassTW")) { class nxs_snapClassTW {

  //#### Show Common Settings  

  function showGenNTSettings($ntOpts){  global $nxs_plurl; $ntInfo = array('code'=>'TW', 'lcode'=>'tw', 'name'=>'Twitter', 'defNName'=>'dlUName', 'tstReq' => true); ?>    

    <div class="nxs_box">

      <div class="nxs_box_header"> 

        <div class="nsx_iconedTitle" style="margin-bottom:1px;background-image:url(<?php echo $nxs_plurl;?>img/<?php echo $ntInfo['lcode']; ?>16.png);"><?php echo $ntInfo['name']; ?>

          <?php $cbo = count($ntOpts); ?> 

          <?php if ($cbo>1){ ?><div class="nsBigText"><?php echo "(".($cbo=='0'?'No':$cbo)." "; _e('accounts', 'nxs_snap'); echo ")"; ?></div><?php } ?>

        </div>

      </div>

      <div class="nxs_box_inside">

        <?php foreach ($ntOpts as $indx=>$pbo){ if (trim($pbo['nName']=='')) $pbo['nName'] = str_ireplace('https://','', str_ireplace('http://','', $pbo['twURL'])); ?>

          <p style="margin:0px;margin-left:5px;"> <img id="<?php echo $ntInfo['code'].$indx;?>LoadingImg" style="display: none;" src='<?php echo $nxs_plurl; ?>img/ajax-loader-sm.gif' />

            <input value="1" name="<?php echo $ntInfo['lcode']; ?>[<?php echo $indx; ?>][apDo<?php echo $ntInfo['code']; ?>]" onchange="<?php if (isset($pbo['catSel']) && (int)$pbo['catSel'] == 1) { ?>nxs_doShowWarning(jQuery(this), '<?php echo (substr_count($pbo['catSelEd'], ",")+1); ?>', '<?php echo $ntInfo['code'];?>', '<?php echo $indx;?>');<?php } ?>" type="checkbox" <?php if ((int)$pbo['do'.$ntInfo['code']] == 1 && $pbo['catSel']!='1') echo "checked"; ?> /> 

            <?php if (isset($pbo['catSel']) && (int)$pbo['catSel'] == 1) { ?> <span onmouseout="nxs_hidePopUpInfo('popOnlyCat');" onmouseover="nxs_showPopUpInfo('popOnlyCat', event);"><?php echo "*[".(substr_count($pbo['catSelEd'], ",")+1)."]*" ?></span><?php } ?>

            <?php if (isset($pbo['rpstOn']) && (int)$pbo['rpstOn'] == 1) { ?> <span onmouseout="nxs_hidePopUpInfo('popReActive');" onmouseover="nxs_showPopUpInfo('popReActive', event);"><?php echo "*[R]*" ?></span><?php } ?>

            <strong><?php  _e('Auto-publish to', 'nxs_snap'); ?> <?php echo $ntInfo['name']; ?> <i style="color: #005800;"><?php if($pbo['nName']!='') echo "(".$pbo['nName'].")"; ?></i></strong>

          &nbsp;&nbsp;<?php if ($ntInfo['tstReq'] && (!isset($pbo[$ntInfo['lcode'].'OK']) || $pbo[$ntInfo['lcode'].'OK']=='')){ ?><b style="color: #800000"><?php  _e('Attention required. Unfinished setup', 'nxs_snap'); ?> ==&gt;</b><?php } ?><a id="do<?php echo $ntInfo['code'].$indx; ?>AG" href="#" onclick="doGetHideNTBlock('<?php echo $ntInfo['code'];?>' , '<?php echo $indx; ?>');return false;">[<?php  _e('Show Settings', 'nxs_snap'); ?>]</a>&nbsp;&nbsp;

          <a href="#" onclick="doDelAcct('<?php echo $ntInfo['lcode']; ?>', '<?php echo $indx; ?>', '<?php if (isset($pbo['bgBlogID'])) echo $pbo['nName']; ?>');return false;">[<?php  _e('Remove Account', 'nxs_snap'); ?>]</a>

          </p><div id="nxsNTSetDiv<?php echo $ntInfo['code'].$indx; ?>"></div><?php //$pbo['ntInfo'] = $ntInfo; $this->showNTSettings($indx, $pbo);             

        }?>

      </div>

    </div> <?php 

  }  

  //#### Show NEW Settings Page

  function showNewNTSettings($mtwo){ $options = array('nName'=>'', 'doTW'=>'1', 'twURL'=>'', 'twConsKey'=>'',  'twConsSec'=>'', 'twAccToken'=>'', 'twAccTokenSec'=>'', 'attchImg'=>0, 'twAttch'=>'', 'twAccTokenSec'=>''); 

    $options['ntInfo']= array('lcode'=>'tw'); $this->showNTSettings($mtwo, $options, true);}

  //#### Show Unit  Settings

  function showNTSettings($ii, $options, $isNew=false){ global $nxs_plurl, $plgn_NS_SNAutoPoster; $nt = $options['ntInfo']['lcode']; $ntU = strtoupper($nt); $tmzFrmt = _x('Y-m-d G:i:s', 'timezone date format');

    if (!isset($plgn_NS_SNAutoPoster)) return; $gOptions = $plgn_NS_SNAutoPoster->nxs_options;  

    if (!isset($options['nHrs'])) $options['nHrs'] = 0; if (!isset($options['nMin'])) $options['nMin'] = 0;  if (!isset($options['catSel'])) $options['catSel'] = 0;  if (!isset($options['catSelEd'])) $options['catSelEd'] = ''; 

    if (!isset($options['riComments'])) $options['riComments'] = '';  if (!isset($options['riCommentsAA'])) $options['riCommentsAA'] = ''; if (!isset($options['riCommentsM'])) $options['riCommentsM'] = ''; 

    if (!isset($options['nDays'])) $options['nDays'] = 0; if (!isset($options['qTLng'])) $options['qTLng'] = '';  ?>

    

    <div id="doTW<?php echo $ii; ?>Div" class="insOneDiv<?php if ($isNew) echo " clNewNTSets"; ?>" >   <input type="hidden" name="apDoSTW<?php echo $ii; ?>" value="0" id="apDoSTW<?php echo $ii; ?>" />      

    

     <div class="nsx_iconedTitle" style="float: right; background-image: url(<?php echo $nxs_plurl; ?>img/tw16.png);"><a style="font-size: 12px;" target="_blank"  href="http://www.nextscripts.com/setup-installation-twitter-social-networks-auto-poster-wordpress/"><?php $nType="Twitter"; printf( __( 'Detailed %s Installation/Configuration Instructions', 'nxs_snap' ), $nType); ?></a></div>

    

    <div style="width:100%;"><strong><?php _e('Account Nickname', 'nxs_snap'); ?>:</strong> <i><?php _e('Just so you can easily identify it', 'nxs_snap'); ?></i> </div><input type="text" name="tw[<?php echo $ii; ?>][nName]" id="twnName<?php echo $ii; ?>" style="font-weight: bold; color: #005800; border: 1px solid #ACACAC; width: 40%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['nName'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" /><br/>

    <?php echo nxs_addQTranslSel('tw', $ii, $options['qTLng']); ?>

    <br/>

    <ul class="nsx_tabs">

    <li><a href="#nsx<?php echo $nt.$ii ?>_tab1"><?php _e('Account Info', 'nxs_snap'); ?></a></li>    

    <?php if (!$isNew) { ?>  <li><a href="#nsx<?php echo $nt.$ii ?>_tab2"><?php _e('Advanced', 'nxs_snap'); ?></a></li>  <?php } ?>

    </ul>

    <div class="nsx_tab_container"><?php /* ######################## Account Tab ####################### */ ?>

    <div id="nsx<?php echo $nt.$ii ?>_tab1" class="nsx_tab_content" style="background-image: url(<?php echo $nxs_plurl; ?>img/tw-bg.png); background-repeat: no-repeat;  background-position:90% 10%;">

    

    <div class="nxs_tls_lbl"><strong>Your Twitter URL:</strong> </div><input type="text" name="tw[<?php echo $ii; ?>][apTWURL]" id="apTWURL" style="width: 40%;border: 1px solid #ACACAC;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['twURL'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />

    <div class="nxs_tls_lbl"><strong>Your Twitter Consumer Key:</strong> </div><input type="text" name="tw[<?php echo $ii; ?>][apTWConsKey]" id="apTWConsKey" style="width: 40%; border: 1px solid #ACACAC;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['twConsKey'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />  

    <div class="nxs_tls_lbl"><strong>Your Twitter Consumer Secret:</strong> </div><input type="text" name="tw[<?php echo $ii; ?>][apTWConsSec]" id="apTWConsSec" style="width: 40%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['twConsSec'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />

    <div class="nxs_tls_lbl"><strong>Your Access Token:</strong> </div><input type="text" name="tw[<?php echo $ii; ?>][apTWAccToken]" id="apTWAccToken" style="width: 40%;" value="<?php _e(apply_filters('format_to_edit', htmlentities($options['twAccToken'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />

    <div class="nxs_tls_lbl"><strong>Your Access Token Secret:</strong> </div><input type="text" name="tw[<?php echo $ii; ?>][apTWAccTokenSec]" id="apTWAccTokenSec" style="width: 40%;" value="<?php  _e(apply_filters('format_to_edit', htmlentities($options['twAccTokenSec'], ENT_COMPAT, "UTF-8")), 'nxs_snap') ?>" />

    <?php if ($isNew) { ?> <input type="hidden" name="tw[<?php echo $ii; ?>][apDoTW]" value="1" id="apDoNewTW<?php echo $ii; ?>" /> <?php } ?>

    <br/><br/>

    <p style="margin: 0px;"><input value="1"  id="apLIAttch" type="checkbox" name="tw[<?php echo $ii; ?>][attchImg]"  <?php if ((int)$options['attchImg'] == 1) echo "checked"; ?> /> <strong><?php _e('Attach Image to Twitter Post', 'nxs_snap'); ?></strong></p>

    <br/>

    <strong id="altFormatText"><?php _e('Message text Format', 'nxs_snap'); ?>:</strong>

    <input type="text" name="tw[<?php echo $ii; ?>][apTWMsgFrmt]" id="apTWMsgFrmt" style="width: 50%;" value="<?php if (!$isNew) _e(apply_filters('format_to_edit', htmlentities($options['twMsgFormat'], ENT_COMPAT, "UTF-8")), 'nxs_snap'); else echo "%TITLE% - %URL%"; ?>"  onfocus="mxs_showFrmtInfo('apTWMsgFrmt<?php echo $ii; ?>');" />

    

     <?php nxs_doShowHint("apTWMsgFrmt".$ii, '<br/><br/><b>%TAGS% and %CATS% will be posted as hashtags. <br/>Please remember that Twitter takes only 140 characters.</b>'); ?>

    

  

               

    <?php if($options['twAccTokenSec']!='') { ?> 

      <br/><br/><b><?php _e('Test your settings', 'nxs_snap'); ?>:</b>&nbsp;&nbsp;&nbsp; <?php if (!isset($options['twOK']) || $options['twOK']!='1') { ?> <div class="blnkg">=== Submit Test Post to Complete ===&gt;</div> <?php } ?> <a href="#" class="NXSButton" onclick="testPost('TW', '<?php echo $ii; ?>'); return false;"><?php printf( __( 'Submit Test Post to %s' , 'nxs_snap'), $nType); ?></a> <br/>

      <?php }?>

      

      

      </div>

      <?php /* ######################## Tools Tab ####################### */ ?>

  <?php if (!$isNew) { ?>   <div id="nsx<?php echo $nt.$ii ?>_tab2" class="nsx_tab_content">

      

   <?php nxs_showCatTagsCTFilters($nt, $ii, $options); 

         nxs_addPostingDelaySelV3($nt, $ii, $options['nHrs'], $options['nMin'], $options['nDays']);  ?>    

    

   <div class="nxs_tls_cpt"><?php _e('Auto Import of Replies and Mentions:', 'nxs_snap'); ?></div>

   <div class="nxs_tls_bd">

   <div class="nxs_tls_sbInfo"><?php _e('Plugin could grab Replies and Mentions from Twitter and import them as Wordpress Comments', 'nxs_snap'); ?></div>

   <?php if ( $gOptions['riActive'] == '1' ) { ?>

   <input value="1" id="riC<?php echo $ii; ?>" <?php if (trim($options['riComments'])=='1') echo "checked"; ?> type="checkbox" name="<?php echo $nt; ?>[<?php echo $ii; ?>][riComments]"/> <b><?php _e('Import Twitter Replies', 'nxs_snap'); ?></b>

   <br/>

   <input value="1" id="riCM<?php echo $ii; ?>" <?php if (trim($options['riCommentsM'])=='1') echo "checked"; ?> type="checkbox" name="<?php echo $nt; ?>[<?php echo $ii; ?>][riCommentsM]"/> <b><?php _e('Import Twitter Mentions', 'nxs_snap'); ?></b>

   <br/> 

   <input value="1" id="riCA<?php echo $ii; ?>" <?php if (trim($options['riCommentsAA'])=='1') echo "checked"; ?> type="checkbox" name="<?php echo $nt; ?>[<?php echo $ii; ?>][riCommentsAA]"/> <b><?php _e('Auto-approve imported comments', 'nxs_snap'); ?></b>

   <?php } else { echo "<br/>"; _e('Please activate the "Comments Import" from SNAP Settings Tab', 'nxs_snap'); } ?>   

   </div>

   

  <?php nxs_showRepostSettings($nt, $ii, $options); ?>

   

    </div>   <?php } ?>

    </div><br/>      

      <div class="submit nxclear" style="padding-bottom: 0px;"><input type="submit" class="button-primary" name="update_NS_SNAutoPoster_settings" value="<?php _e('Update Settings', 'nxs_snap') ?>" /></div>

    

    </div>

    <?php

  }

  //#### Set Unit Settings from POST

  function setNTSettings($post, $options){ $code = 'TW'; $lcode = 'tw'; 

    foreach ($post as $ii => $pval){ 

      if (isset($pval['apTWURL']) && $pval['apTWURL']!='') { if (!isset($options[$ii])) $options[$ii] = array();

        if (isset($pval['apDoTW']))         $options[$ii]['doTW'] = $pval['apDoTW']; else $options[$ii]['doTW'] = 0;

        if (isset($pval['nName']))          $options[$ii]['nName'] = trim($pval['nName']);

        if (isset($pval['apTWURL']))        $options[$ii]['twURL'] = trim($pval['apTWURL']);  if ( substr($options[$ii]['twURL'], 0, 4)!='http' )  $options[$ii]['twURL'] = 'http://'.$options[$ii]['twURL'];

        if (isset($pval['apTWConsKey']))    $options[$ii]['twConsKey'] = trim($pval['apTWConsKey']);

        if (isset($pval['apTWConsSec']))    $options[$ii]['twConsSec'] = trim($pval['apTWConsSec']);                                

        if (isset($pval['apTWAccToken']))   $options[$ii]['twAccToken'] = trim($pval['apTWAccToken']);                

        

        if (isset($pval['catSel'])) $options[$ii]['catSel'] = trim($pval['catSel']); else $options[$ii]['catSel'] = 0;

        if (isset($options[$ii]['catSel']) && $options[$ii]['catSel']=='1' && trim($pval['catSelEd'])!='') $options[$ii]['catSelEd'] = trim($pval['catSelEd']); else $options[$ii]['catSelEd'] = '';

        

        if (isset($pval['riComments']))      $options[$ii]['riComments'] = $pval['riComments']; else $options[$ii]['riComments'] = 0;

        if (isset($pval['riCommentsM']))     $options[$ii]['riCommentsM'] = $pval['riCommentsM']; else $options[$ii]['riCommentsM'] = 0;

        if (isset($pval['riCommentsAA']))    $options[$ii]['riCommentsAA'] = $pval['riCommentsAA']; else $options[$ii]['riCommentsAA'] = 0;

        

        $options[$ii] = nxs_adjRpst($options[$ii], $pval);

        

        if (isset($pval['apTWAccTokenSec']))$options[$ii]['twAccTokenSec'] = trim($pval['apTWAccTokenSec']);                                

        if (isset($pval['apTWMsgFrmt']))    $options[$ii]['twMsgFormat'] = trim($pval['apTWMsgFrmt']);

        if (isset($pval['attchImg'])) $options[$ii]['attchImg'] = $pval['attchImg']; else $options[$ii]['attchImg'] = 0;                

        if (isset($pval['delayHrs'])) $options[$ii]['nHrs'] = trim($pval['delayHrs']); if (isset($pval['delayMin'])) $options[$ii]['nMin'] = trim($pval['delayMin']); if (isset($pval['delayDays'])) $options[$ii]['nDays'] = trim($pval['delayDays']); 

        if (isset($pval['qTLng'])) $options[$ii]['qTLng'] = trim($pval['qTLng']); 

      } elseif ((isset($pval['catSel'])) && $pval['catSel']=='X' && (isset($pval['apDoFB'])) && $pval['apDoFB']=='1') $options[$ii]['catSel'] = trim($pval['catSel']);

    } return $options;

  }    

  //#### Show Post->Edit Meta Box Settings

  function showEdPostNTSettings($ntOpts, $post){ global $nxs_plurl; $post_id = $post->ID; $nt = 'tw'; $ntU = 'TW';

    foreach($ntOpts as $ii=>$ntOpt)  { $pMeta = maybe_unserialize(get_post_meta($post_id, 'snapTW', true)); if (is_array($pMeta) && isset($pMeta[$ii]) && is_array($pMeta[$ii])) $ntOpt = $this->adjMetaOpt($ntOpt, $pMeta[$ii]); 

         if (empty($ntOpt['imgToUse'])) $ntOpt['imgToUse'] = ''; if (empty($ntOpt['urlToUse'])) $ntOpt['urlToUse'] = '';          

         $doTW = $ntOpt['doTW'] && (is_array($pMeta) || $ntOpt['catSel']!='1'); $imgToUse = $ntOpt['imgToUse']; 

         $isAvailTW =  $ntOpt['twURL']!='' && $ntOpt['twConsKey']!='' && $ntOpt['twConsSec']!='' && $ntOpt['twAccToken']!=''; $twMsgFormat = htmlentities($ntOpt['twMsgFormat'], ENT_COMPAT, "UTF-8");   $isAttchImg = $ntOpt['attchImg'];    

      ?>  

      <tr><th style="text-align:left;" colspan="2">

      <?php if ($ntOpt['catSel']=='1' && trim($ntOpt['catSelEd'])!='')  { ?> <input type="hidden" class="nxs_SC" id="nxs_SC_<?php echo $ntU; ?><?php echo $ii; ?>" value="<?php echo $ntOpt['catSelEd']; ?>" /> <?php } ?>

      <?php if (!empty($ntOpt['tagsSelX'])) { ?>  <input type="hidden" class="nxs_TG" id="nxs_TG_<?php echo $ntU; ?><?php echo $ii; ?>" value="<?php echo $ntOpt['tagsSelX']; ?>" /> <?php } ?>

      <?php if ($isAvailTW) { ?><input class="nxsGrpDoChb" value="1" id="doTW<?php echo $ii; ?>" <?php if ($post->post_status == "publish") echo 'disabled="disabled"';?> type="checkbox" name="tw[<?php echo $ii; ?>][doTW]" <?php if ((int)$doTW == 1) echo 'checked="checked" title="def"';  ?> /> 

      <?php if ($post->post_status == "publish") { ?> <input type="hidden" name="tw[<?php echo $ii; ?>][doTW]" value="<?php echo $doTW;?>"> <?php } ?> <?php } ?>

      <div class="nsx_iconedTitle" style="display: inline; font-size: 13px; background-image: url(<?php echo $nxs_plurl; ?>img/tw16.png);">Twitter - <?php _e('autopost to', 'nxs_snap') ?> (<i style="color: #005800;"><?php echo $ntOpt['nName']; ?></i>)

      </div></th><td><?php //## Only show RePost button if the post is "published"

      if ($post->post_status == "publish" && $isAvailTW) { ?>

                    <input alt="<?php echo $ii; ?>" style="float: right;" onmouseout="hidePopShAtt('SV');" onmouseover="showPopShAtt('SV', event);" onclick="return false;" type="button" class="button" name="rePostToTW_repostButton" id="rePostToTW_button" value="<?php _e('Repost to Twitter', 'nxs_snap') ?>" />

    

    <?php if ($ntOpt['riComments']=='1' && (is_array($pMeta) && isset($pMeta[$ii]) && is_array($pMeta[$ii]) && isset($pMeta[$ii]['pgID']) && $pMeta[$ii]['pgID']!='' ) ) { ?>

       <input alt="<?php echo $ii; ?>" style="float: right; " onclick="return false;" type="button" class="button" name="riToTW_repostButton" id="riToTW_button" value="<?php _e('Import Replies/Mentions from Twitter', 'nxs_snap') ?>" />

    <?php }  } ?>

                    <?php  if (is_array($pMeta) && isset($pMeta[$ii]) && is_array($pMeta[$ii]) && isset($pMeta[$ii]['pgID'])) { ?> <span style="float: right;padding-top: 4px; padding-right: 10px;">

                      <a id="pstdTW<?php echo $ii; ?>" style="font-size: 10px;" href="<?php echo $ntOpt['twURL'].'/status/'.$pMeta[$ii]['pgID'];  ?>" target="_blank"><?php $nType="Twitter"; printf( __( 'Posted on', 'nxs_snap' ), $nType); ?>  <?php echo (isset($pMeta[$ii]['pDate']) && $pMeta[$ii]['pDate']!='')?(" (".$pMeta[$ii]['pDate'].")"):""; ?></a>

                    </span><?php } ?>

                </td></tr>

                

                <?php if (!$isAvailTW) { ?><tr><th scope="row" style="text-align:right; width:150px; padding-top: 5px; padding-right:10px;"></th> <td><b>Setup your Twitter Account to AutoPost to Twitter</b>

                <?php }else {  if ($post->post_status != "publish" && function_exists('nxs_doSMAS5') ) { $ntOpt['postTime'] = get_post_time('U', false, $post_id); nxs_doSMAS5($nt, $ii, $ntOpt); } ?>

                

                <?php if ($ntOpt['rpstOn']=='1') { ?> 

                

                <tr id="altFormat1" style=""><th scope="row" class="nxsTHRow">

                <input value="0"  type="hidden" name="tw[<?php echo $ii; ?>][rpstPostIncl]"/><input value="nxsi<?php echo $ii; ?>tw" type="checkbox" name="tw[<?php echo $ii; ?>][rpstPostIncl]"  <?php if (!empty($ntOpt['rpstPostIncl'])) echo "checked"; ?> /> 

                </th>

                <td> <?php _e('Include in "Auto-Reposting" to this network.', 'nxs_snap') ?>

                </td></tr> <?php } ?>

                

                <tr id="altFormat1" style=""><th scope="row" class="nxsTHRow"><?php _e('Message Format:', 'nxs_snap') ?></th>

                <td><input value="<?php echo $twMsgFormat ?>" type="text" name="tw[<?php echo $ii; ?>][SNAPformat]"  style="width:60%;max-width: 610px;" onfocus="jQuery('.nxs_FRMTHint').hide();mxs_showFrmtInfo('apTWMsgFrmt<?php echo $ii; ?>');"/><?php nxs_doShowHint("apTWMsgFrmt".$ii); ?></td></tr>

                

<tr><th scope="row" style="text-align:right; width:150px; vertical-align:top; padding-top: 5px; padding-right:10px;">

                 <input value="0"  type="hidden" name="tw[<?php echo $ii; ?>][attchImg]"/>

                 <input value="1" type="checkbox" name="tw[<?php echo $ii; ?>][attchImg]"  <?php if ((int)$isAttchImg == 1) echo "checked"; ?> /> </th><td><strong>Attach Image to Twitter Post</strong></td> </tr>                  

                 

                   <?php /* ## Select Image & URL ## */ nxs_showImgToUseDlg($nt, $ii, $imgToUse); ?>

                 

       <?php } 

    } 

  }

  //#### 

  function adjMetaOpt($optMt, $pMeta){  if (isset($pMeta['isPosted'])) $optMt['isPosted'] = $pMeta['isPosted']; else $optMt['isPosted'] = '';       

     if (isset($pMeta['SNAPformat'])) $optMt['twMsgFormat'] = $pMeta['SNAPformat'];  

     if (isset($pMeta['imgToUse'])) $optMt['imgToUse'] = $pMeta['imgToUse'];      

     if (isset($pMeta['timeToRun']))  $optMt['timeToRun'] = $pMeta['timeToRun'];  if (isset($pMeta['rpstPostIncl']))  $optMt['rpstPostIncl'] = $pMeta['rpstPostIncl'];    

     if (isset($pMeta['attchImg'])) $optMt['attchImg'] = $pMeta['attchImg'] == 1?1:0; else { if (isset($pMeta['attchImg'])) $optMt['attchImg'] = 0; } 

     if (isset($pMeta['doTW'])) $optMt['doTW'] = $pMeta['doTW'] == 1?1:0; else { if (isset($pMeta['SNAPformat'])) $optMt['doTW'] = 0; } 

     if (isset($pMeta['SNAPincludeTW']) && $pMeta['SNAPincludeTW'] == '1' ) $optMt['doTW'] = 1; // <2.6 Compatibility fix    

     return $optMt;

  }

}}



if (!function_exists("nxs_getBackTWCommentsList")) { function nxs_getBackTWCommentsList($options) { 

  require_once ('apis/tmhOAuth.php'); $tmhOAuth = new NXS_tmhOAuth(array( 'consumer_key' => $options['twConsKey'], 'consumer_secret' => $options['twConsSec'], 'user_token' => $options['twAccToken'], 'user_secret' => $options['twAccTokenSec']));

  $code = $tmhOAuth->request('GET', $tmhOAuth->url('1.1/statuses/mentions_timeline')); 

  if ($code=='200' && isset($tmhOAuth->response['response']) ) { $tweets = json_decode($tmhOAuth->response['response'], true); if (is_array($tweets)) return $tweets; } return false; 

}}



if (!function_exists("nxs_getBackTWComments")) { function nxs_getBackTWComments($postID, $options, $po, $twList) { $impCmnts = get_post_meta($postID, 'snapImportedComments', true);  

    if(!is_array($impCmnts)) $impCmnts = array(); $twsToImp = array(); $lastID = '';

    //## Do Replies

    foreach ($twList as $tw) if ($tw['in_reply_to_status_id_str'] == $po['pgID']) $twsToImp[] = $tw;

    if (is_array($twsToImp) && count($twsToImp)>0)

      foreach ($twsToImp as $comment){ $cid = $comment['id_str']; if (trim($cid)=='' || in_array('twxcw'.$cid, $impCmnts)) continue; else $impCmnts[] = 'twxcw'.$cid;  // prr($impCmnts);

        $commentdata = array( 'comment_post_ID' => $postID, 'comment_author' => $comment['user']['name'], 'comment_agent' => "SNAP||".str_ireplace('_normal.','_bigger.',$comment['user']['profile_image_url_https']), 

          'comment_author_email' => $comment['user']['screen_name'].'@twitter.com', 'comment_author_url' => 'http://twitter.com/'.$comment['user']['screen_name'], 

          'comment_content' => str_ireplace('@'.$comment['in_reply_to_screen_name'],'', $comment['text']), 'comment_date_gmt' => date('Y-m-d H:i:s', strtotime( $comment['created_at'] ) ), 'comment_type' => '');

        nxs_postNewComment($commentdata, $options['riCommentsAA']=='1'); $ci++;

      }     

      

    //## Do mentions.

    require_once ('apis/tmhOAuth.php'); $tmhOAuth = new NXS_tmhOAuth(array( 'consumer_key' => $options['twConsKey'], 'consumer_secret' => $options['twConsSec'], 'user_token' => $options['twAccToken'], 'user_secret' => $options['twAccTokenSec']));    

    if (isset($options['urlToUse']) && trim($options['urlToUse'])!='') $urlToSrch = $options['urlToUse']; else $urlToSrch = get_permalink($postID);     

    $code = $tmhOAuth->request('GET', $tmhOAuth->url('1.1/search/tweets'), array('rpp'=>'100', 'since_id'=>$lastID, 'q'=> urlencode($urlToSrch)));       

    if ($code=='200' && isset($tmhOAuth->response['response']) ) { $tweets = json_decode($tmhOAuth->response['response'], true); //prr($tweets);

     if (is_array($tweets) && is_array($tweets['statuses'])) {    

      foreach ($tweets['statuses'] as $comment){ $cid = $comment['id_str']; if (trim($cid)=='' || in_array('twxcw'.$cid, $impCmnts) || $cid==$po['pgID']) continue; else $impCmnts[] = 'twxcw'.$cid;  // prr($impCmnts);

        $commentdata = array( 'comment_post_ID' => $postID, 'comment_author' => $comment['user']['name'], 'comment_author_email' =>  $comment['user']['screen_name'].'@twitter.com', 

          'comment_agent' => "SNAP||".str_ireplace('_normal.','_bigger.',$comment['user']['profile_image_url_https']), 

          'comment_author_url' => 'http://twitter.com/'.$comment['user']['screen_name'], 'comment_content' => $comment['text'], 'comment_date_gmt' => date('Y-m-d H:i:s', strtotime( $comment['created_at'] ) ), 'comment_type' => '');

        nxs_postNewComment($commentdata, $options['riCommentsAA']=='1'); $ci++;

      }

    }}

    delete_post_meta($postID, 'snapImportedComments'); add_post_meta($postID, 'snapImportedComments', $impCmnts ); 

    if ( isset($_POST['id']) && $_POST['id']!='') printf( _n('%d comment has been imported.', '%d comments has been imported.', $ci, 'nxs_snap'), $ci );

}}



if (!function_exists("nxs_rePostToTW_ajax")) {

  function nxs_rePostToTW_ajax() { check_ajax_referer('nxsSsPageWPN');  $postID = $_POST['id']; $options = get_option('NS_SNAutoPoster');  

    foreach ($options['tw'] as $ii=>$nto) if ($ii==$_POST['nid']) {   $nto['ii'] = $ii;  $nto['pType'] = 'aj';

      $twpo =  get_post_meta($postID, 'snapTW', true); $twpo =  maybe_unserialize($twpo);

      if (is_array($twpo) && isset($twpo[$ii]) && is_array($twpo[$ii]) && isset($twpo[$ii]['SNAPformat']) ) { $ntClInst = new nxs_snapClassTW(); $nto = $ntClInst->adjMetaOpt($nto, $twpo[$ii]);}       

      if (isset($_POST['ri']) && $_POST['ri']=='1') { $twList = nxs_getBackTWCommentsList($nto);  nxs_getBackTWComments($postID, $nto, $twpo[$ii], $twList); die(); } else {

        $result = nxs_doPublishToTW($postID, $nto); if ($result == 200) {$options['tw'][$ii]['twOK']=1;  update_option('NS_SNAutoPoster', $options); } if ($result == 200) die("Successfully sent your post to Twitter."); else die($result);

      }

    }

  }

} 



if (!function_exists("nxs_doPublishToTW")) { //## Second Function to Post to TW 

  function nxs_doPublishToTW($postID, $options){ $ntCd = 'TW'; $ntCdL = 'tw'; $ntNm = 'Twitter'; $img = ''; $imgURL = ''; global $plgn_NS_SNAutoPoster, $nxs_urlLen; $nxs_urlLen = 0;

    if (!is_array($options)) $options = maybe_unserialize(get_post_meta($postID, $options, true));

    //$backtrace = debug_backtrace(); nxs_addToLogN('W', 'Error', $logNT, 'I am here - '.$ntCd."|".print_r($backtrace, true), ''); 

    //if (isset($options['timeToRun'])) wp_unschedule_event( $options['timeToRun'], 'nxs_doPublishToTW',  array($postID, $options));

    $gOptions = $plgn_NS_SNAutoPoster->nxs_options; if (!empty($gOptions['nxsHTSpace'])) $htS = $gOptions['nxsHTSpace']; else $htS = '';

    $addParams = nxs_makeURLParams(array('NTNAME'=>$ntNm, 'NTCODE'=>$ntCd, 'POSTID'=>$postID, 'ACCNAME'=>$options['nName']));

    $ii = $options['ii']; if (!isset($options['pType'])) $options['pType'] = 'im'; if ($options['pType']=='sh') sleep(rand(1, 10)); 

    $logNT = '<span style="color:#00FFFF">Twitter</span> - '.$options['nName']; 

    $snap_ap = get_post_meta($postID, 'snap'.$ntCd, true); $snap_ap = maybe_unserialize($snap_ap);     

    if ($options['pType']!='aj' && is_array($snap_ap) && (nxs_chArrVar($snap_ap[$ii], 'isPosted', '1') || nxs_chArrVar($snap_ap[$ii], 'isPrePosted', '1'))) {

        $snap_isAutoPosted = get_post_meta($postID, 'snap_isAutoPosted', true); if ($snap_isAutoPosted!='2') { 

         nxs_addToLogN('W', 'Notice', $logNT, '-=Duplicate=- Post ID:'.$postID, 'Already posted. No reason for posting duplicate'.' |'.$options['pType']); return;

        }

    }  

    $blogTitle = htmlspecialchars_decode(get_bloginfo('name'), ENT_QUOTES); if ($blogTitle=='') $blogTitle = home_url(); $uln = 0; $extInfo = ' | PostID: '.$postID;    

    

    if ($options['attchImg']=='1') { if (trim($options['imgToUse'])!='') $imgURL = $options['imgToUse']; else $imgURL = nxs_getPostImage($postID);  if (preg_match("/noImg.\.png/i", $imgURL)) $imgURL = '';  

      if(trim($imgURL)=='') $options['attchImg'] = 0; else { $imgURL = str_replace(' ', '%20', $imgURL);

        if( ini_get('allow_url_fopen') ) { if (@getimagesize($imgURL)!==false) { $img = wp_remote_get($imgURL); 

          if (is_wp_error($img)) $options['attchImg'] = 0; else if ( (!empty($img['headers']['content-length'])) && (int)$img['headers']['content-length']<200) { $options['attchImg'] = 0; } else $img = $img['body']; } else $options['attchImg'] = 0; 

        } else {  $img = wp_remote_get($imgURL); if(is_wp_error($img)) $options['attchImg'] = 0; elseif (isset($img['body'])&& trim($img['body'])!='') $img = $img['body'];  else $options['attchImg'] = 0; }         

        if ($options['attchImg'] == 0) nxs_addToLogN('E', 'Error', $logNT, 'Could not get image ('.$imgURL.'), will post without it - Error:'.print_r($img, true), $extInfo);

      }

    }  

    if ($options['attchImg']=='1' && $img!='') $twLim = 117; else $twLim = 140;     

    if ($postID=='0') { echo "Testing ... <br/><br/>"; $msg = 'Test Post from '.nsTrnc($blogTitle, $twLim - 24)." - ".rand(1, 155); $uln = nxs_strLen($msg);}  

     else{ $post = get_post($postID); if(!$post) return; $twMsgFormat = $options['twMsgFormat'];  nxs_metaMarkAsPosted($postID, $ntCd, $options['ii'], array('isPrePosted'=>'1'));    

        $extInfo = ' | PostID: '.$postID." - ".$post->post_title.' |'.$options['pType'];        

        if (stripos($twMsgFormat, '%URL%')!==false || stripos($twMsgFormat, '%SURL%')!==false) $twLim = $twLim - 22; 

        if (stripos($twMsgFormat, '%AUTHORNAME%')!==false) { $aun = $post->post_author;  $aun = get_the_author_meta('display_name', $aun ); $twLim = $twLim - nxs_strLen($aun); } 

        

        $noRepl = str_ireplace("%TITLE%", "", $twMsgFormat); $noRepl = str_ireplace("%SITENAME%", "", $noRepl); $noRepl = str_ireplace("%URL%", "", $noRepl);$noRepl = str_ireplace("%RAWEXCERPT%", "", $noRepl); 

        $noRepl = str_ireplace("%SURL%", "", $noRepl);$noRepl = str_ireplace("%TEXT%", "", $noRepl);$noRepl = str_ireplace("%FULLTEXT%", "", $noRepl);$noRepl = str_ireplace("%EXCERPT%", "", $noRepl);

        $noRepl = str_ireplace("%ANNOUNCE%", "", $noRepl); $noRepl = str_ireplace("%AUTHORNAME%", "", $noRepl);  $twLim = $twLim - nxs_strLen($noRepl); 

        

        $pTitle = nxs_doQTrans($post->post_title);

        if ($post->post_excerpt!="") $exrText = nxs_doQTrans($post->post_excerpt); else $exrText= nxs_doQTrans($post->post_content);  $pText = apply_filters('the_content', $exrText);        

        $pRawText = nxs_doQTrans($post->post_content);  $pFullText = apply_filters('the_content', $pRawText); 

        if (stripos($twMsgFormat, '%TAGS%')!==false || stripos($twMsgFormat, '%HTAGS%')!==false) {

          $t = wp_get_object_terms($postID, 'product_tag'); if ( empty($t) || is_wp_error($pt) || !is_array($t) ) $t = wp_get_post_tags($postID);

          $tggs = array(); foreach ($t as $tagA) { $frmTag =  trim(str_replace(' ', $htS, preg_replace('/[^a-zA-Z0-9\p{L}\p{N}\s]/u', '', trim(ucwords(str_ireplace('&','',str_ireplace('&amp;','',$tagA->name)))))));

              if (preg_match('/\b'.$frmTag.'\b/iu', $pTitle)) $pTitle = trim(preg_replace('/\b'.$frmTag.'\b/iu', '#'.$frmTag, $pTitle)); 

              if (preg_match('/\b'.$frmTag.'\b/iu', $pFullText)) $pFullText = trim(preg_replace('/\b'.$frmTag.'\b/iu', '#'.$frmTag, $pFullText)); 

              if (preg_match('/\b'.$frmTag.'\b/iu', $pText)) $pText = trim(preg_replace('/\b'.$frmTag.'\b/iu', '#'.$frmTag, $pText)); 

              if (preg_match('/\b'.$frmTag.'\b/iu', $pRawText)) $pRawText = trim(preg_replace('/\b'.$frmTag.'\b/iu', '#'.$frmTag, $pRawText)); 

              if ( ((stripos($twMsgFormat, '%TITLE%')!==false) && preg_match('/\b'.$frmTag.'\b/i', $pTitle)) ||

                   ((stripos($twMsgFormat, '%TEXT%')!==false) && preg_match('/\b'.$frmTag.'\b/i', $pText)) ||

                   ((stripos($twMsgFormat, '%EXCERPT%')!==false) && preg_match('/\b'.$frmTag.'\b/i', $pText)) ||

                   ((stripos($twMsgFormat, '%RAWEXCERPT%')!==false) && preg_match('/\b'.$frmTag.'\b/i', $pText)) ||

                   ((stripos($twMsgFormat, '%ANNOUNCE%')!==false) && preg_match('/\b'.$frmTag.'\b/i', $pText)) ||

                   ((stripos($twMsgFormat, '%FULLTEXT%')!==false) && preg_match('/\b'.$frmTag.'\b/i', $pFullText)) ||

                   ((stripos($twMsgFormat, '%RAWTEXT%')!==false) && preg_match('/\b'.$frmTag.'\b/i', $pRawText)) ) {} else $tggs[] = '#'.$frmTag;

          } $tags = implode(' ',$tggs); while(count($tags)>($twLim-10)) {array_pop($tggs); $tags = implode(' ',$tggs);} $twMsgFormat = str_ireplace("%TAGS%", $tags, $twMsgFormat);  $twMsgFormat = str_ireplace("%HTAGS%", $tags, $twMsgFormat);

          $twLim = $twLim - nxs_strLen($tags);

        } 

        if (stripos($twMsgFormat, '%CATS%')!==false || stripos($twMsgFormat, '%HCATS%')!==false) {

          $t = wp_get_post_categories($postID); $ctts = array();  foreach($t as $c){ $cat = get_category($c); //$frmTag =  trim(str_replace(' ','', str_replace('  ',' ',str_ireplace('&','&amp;',trim(ucwords($cat->name)))))); prr($frmTag);

          $frmTag =  trim(str_replace(' ',$htS,preg_replace('/[^a-zA-Z0-9\p{L}\p{N}\s]/u', '', trim(ucwords(str_ireplace('&','',str_ireplace('&amp;','',$cat->name)))))));

          if (stripos($pTitle, $cat->name)!==false) $pTitle = str_ireplace($cat->name, '#'.$frmTag, $pTitle); elseif (stripos($pTitle, $frmTag)!==false) $pTitle = str_ireplace($frmTag, '#'.$frmTag, $pTitle); 

              if (stripos($pText, $cat->name)!==false) $pText = str_ireplace($cat->name, '#'.$frmTag, $pText); elseif (stripos($pText, $frmTag)!==false) $pText = str_ireplace($frmTag, '#'.$frmTag, $pText); 

              if (stripos($pFullText, $cat->name)!==false) $pFullText = str_ireplace($cat->name, '#'.$frmTag, $pFullText); elseif (stripos($pFullText, $frmTag)!==false) $pFullText = str_ireplace($frmTag, '#'.$frmTag, $pFullText); 

              if (stripos($pRawText, $cat->name)!==false) $pRawText = str_ireplace($cat->name, '#'.$frmTag, $pRawText); elseif (stripos($pRawText, $frmTag)!==false) $pRawText = str_ireplace($frmTag, '#'.$frmTag, $pRawText); 

              if ( ((stripos($twMsgFormat, '%TITLE%')!==false) && (stripos($pTitle, $cat->name)!==false || stripos($pTitle, $frmTag)!==false)) ||

                   ((stripos($twMsgFormat, '%TEXT%')!==false) && (stripos($pText, $cat->name)!==false || stripos($pText, $frmTag)!==false)) ||

                   ((stripos($twMsgFormat, '%EXCERPT%')!==false) && (stripos($pText, $cat->name)!==false || stripos($pText, $frmTag)!==false)) ||

                   ((stripos($twMsgFormat, '%RAWEXCERPT%')!==false) && (stripos($exrText, $cat->name)!==false || stripos($exrText, $frmTag)!==false)) ||

                   ((stripos($twMsgFormat, '%ANNOUNCE%')!==false) && (stripos($pText, $cat->name)!==false || stripos($pText, $frmTag)!==false)) ||

                   ((stripos($twMsgFormat, '%FULLTEXT%')!==false) && (stripos($pFullText, $cat->name)!==false || stripos($pFullText, $frmTag)!==false)) ||

                   ((stripos($twMsgFormat, '%RAWTEXT%')!==false) && (stripos($pRawText, $cat->name)!==false || stripos($pRawText, $frmTag)!==false)) ) {} else $ctts[] = '#'.$frmTag; 

          } $cats = implode(' ',$ctts); while(count($cats)>($twLim-10)) {array_pop($ctts); $cats = implode(' ',$ctts);} $twMsgFormat = str_ireplace("%CATS%", $cats, $twMsgFormat);  $twMsgFormat = str_ireplace("%HCATS%", $cats, $twMsgFormat);

          $twLim = $twLim - nxs_strLen($cats);

        } 

        if (stripos($twMsgFormat, '%TITLE%')!==false) { if (stripos($pTitle, '.co.uk')!==false) $twLim = $twLim - 14;

           if (stripos($pTitle, '.com')!==false) $twLim = $twLim - 16; if (stripos($pTitle, '.net')!==false) $twLim = $twLim - 16; if (stripos($pTitle, '.org')!==false) $twLim = $twLim - 16;

           $pTitle = html_entity_decode(strip_tags($pTitle), ENT_NOQUOTES, 'UTF-8'); 

           $pTitle = nsTrnc($pTitle, $twLim); $twMsgFormat = str_ireplace("%TITLE%", $pTitle, $twMsgFormat); $twLim = $twLim - nxs_strLen($pTitle); 

        } 

        if (stripos($twMsgFormat, '%SITENAME%')!==false) {

          $siteTitle = htmlspecialchars_decode(get_bloginfo('name'), ENT_QUOTES); $siteTitle = nsTrnc($siteTitle, $twLim); $twMsgFormat = str_ireplace("%SITENAME%", $siteTitle, $twMsgFormat); $twLim = $twLim - nxs_strLen($siteTitle);

        }     

        if (stripos($twMsgFormat, '%TEXT%')!==false) {          

          $pText = nsTrnc(strip_tags(strip_shortcodes($pText)), 300, " ", "..."); 

          $pText = nsTrnc($pText, $twLim); $twMsgFormat = str_ireplace("%TEXT%", $pText, $twMsgFormat); $twLim = $twLim - nxs_strLen($pText);

        } 

        if (stripos($twMsgFormat, '%EXCERPT%')!==false) {          

          $pText = nsTrnc(strip_tags(strip_shortcodes($pText)), 300, " ", "..."); 

          $pText = nsTrnc($pText, $twLim); $twMsgFormat = str_ireplace("%EXCERPT%", $pText, $twMsgFormat); $twLim = $twLim - nxs_strLen($pText);

        } 

        if (stripos($twMsgFormat, '%RAWEXCERPT%')!==false) {          

          $exrText = nsTrnc(strip_tags(strip_shortcodes($exrText)), 300, " ", "..."); 

          $exrText = nsTrnc($exrText, $twLim); $twMsgFormat = str_ireplace("%RAWEXCERPT%", $exrText, $twMsgFormat); $twLim = $twLim - nxs_strLen($exrText);

        } 

        if (stripos($twMsgFormat, '%FULLTEXT%')!==false) {

           $pFullText = nsTrnc(strip_tags($pFullText), $twLim); $twMsgFormat = str_ireplace("%FULLTEXT%", $pFullText, $twMsgFormat); $twLim = $twLim - nxs_strLen($pFullText);

        }          

        if (stripos($twMsgFormat, '%RAWTEXT%')!==false) {

           $pRawText = nsTrnc(strip_tags($pRawText), $twLim); $twMsgFormat = str_ireplace("%RAWTEXT%", $pRawText, $twMsgFormat); $twLim = $twLim - nxs_strLen($pRawText);

        }              

        $msg = nsFormatMessage($twMsgFormat, $postID, $addParams);         

    }

    $msg = str_replace('&amp;#039;', "'", $msg);  $msg = str_replace('&#039;', "'", $msg);  $msg = str_replace('#039;', "'", $msg);  $msg = str_replace('#039', "'", $msg);

    $msg = str_replace('&amp;#8217;', "'", $msg); $msg = str_replace('&#8217;', "'", $msg); $msg = str_replace('#8217;', "'", $msg); $msg = str_replace('#8217', "'", $msg);

    $msg = str_replace('&amp;#8220;', '"', $msg); $msg = str_replace('&#8220;', '"', $msg); $msg = str_replace('#8220;', '"', $msg); $msg = str_replace('#8220', "'", $msg);

    $msg = str_replace('&amp;#8221;', '"', $msg); $msg = str_replace('&#8221;', '"', $msg); $msg = str_replace('#8221;', '"', $msg); $msg = str_replace('#8221', "'", $msg);
    
    $show= '';
    $msg= '';
    $cats= wp_get_post_categories($postID);
    if(in_array(27,$cats)){$show='3 Bières (@3Bieres)';}
    elseif(in_array(6,$cats)){$show='Boulevard Brutal (@BoulevardBrutal)';}
    elseif(in_array(8,$cats)){$show='Fun-Regarder-Films (@FunRegarderFilm)';}
    elseif(in_array(25,$cats)){$show='Horreur Gamer (@HorreurGamer)';}
    elseif(in_array(24,$cats)){$show='L\'Épee Légendaire (@EpeeLegendaire)';}
    elseif(in_array(28,$cats)){$show='La Commission des Geekeurs (@Les_Geekeurs)';}
    elseif(in_array(7,$cats)){$show='La Soirée du Podcast (@SoireeDuPodcast)';}
    elseif(in_array(5,$cats)){$show='Le Dernier des Podcasts (@DernierPodcast)';}
    elseif(in_array(26,$cats)){$show='Le Shack À Boisson (@Shackaboisson)';}
    elseif(in_array(3,$cats)){$show='Les Mystérieux Étonnants (@MysterieuxE)';}
    elseif(in_array(22,$cats)){$show='MacQuébec (@MacQuebec)';}
    elseif(in_array(23,$cats)){$show='Objectif Numérique (@ONumerique)';}
    elseif(in_array(21,$cats)){$show='Pod Probleme (@PodProbleme)';}
    elseif(in_array(4,$cats)){$show='Rétro Nouveau (@retro_nouveau)';}
	elseif(in_array(1191,$cats)){$show='AlcoJeux';}
    elseif(in_array(1195,$cats)){$show='Des Si Et Des Rais';}
    elseif(in_array(1192,$cats)){$show='Geek-O-Rama (@Geek0Rama)';}
    elseif(in_array(1194,$cats)){$show='Je Joue Le Jeu (@jejouelejeu)';}
    elseif(in_array(1196,$cats)){$show='L\'erreur 400 Cast (@lerreur400cast)';}
    elseif(in_array(1197,$cats)){$show='Point De Vues (@Pointdevues)';}
    elseif(in_array(1198,$cats)){$show='Souper De Filles (@SouperDeFilles_)';}
    elseif(in_array(1190,$cats)){$show='Testeur Alpha (@TesteurAlpha)';}

    $msg= $show . ' : ' . $post->post_title . ' ' . get_permalink($postID) . ' #RZO';

    $message = array('message'=>$msg, 'img'=>$img, 'urlLength'=>$nxs_urlLen);  $options['twMsgFormat'] = $msg;  

    

    //## Actual Post

    $ntToPost = new nxs_class_SNAP_TW(); $ret = $ntToPost->doPostToNT($options, $message);

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
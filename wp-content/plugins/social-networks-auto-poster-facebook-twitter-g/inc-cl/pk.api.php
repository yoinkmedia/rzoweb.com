<?php    
//## NextScripts FriendFeed Connection Class
$nxs_snapAPINts[] = array('code'=>'PK', 'lcode'=>'pk', 'name'=>'Plurk');

if (!class_exists("nxs_class_SNAP_PK")) { class nxs_class_SNAP_PK {
    
    var $ntCode = 'PK';
    var $ntLCode = 'pk';     
    
    function doPost($options, $message){ if (!is_array($options)) return false; 
      foreach ($options as $ntOpts) $out[] = $this->doPostToNT($ntOpts, $message);
      return $out;
    }    
    function doPostToNT($options, $message){ $badOut = array('pgID'=>'', 'isPosted'=>0, 'pDate'=>date('Y-m-d H:i:s'), 'Error'=>'');
      //## Check settings
      if (!is_array($options)) { $badOut['Error'] = 'No Options'; return $badOut; }      
      if (!isset($options['pkConsKey']) || trim($options['pkConsSec'])=='') { $badOut['Error'] = 'Not Configured'; return $badOut; }                  
      //## Format
      $msg = nxs_doFormatMsg($options['pkMsgFormat'], $message);       
      //## Post    
      require_once('apis/plurkOAuth.php'); $consumer_key = $options['pkConsKey']; $consumer_secret = $options['pkConsSec'];
      $tum_oauth = new wpPlurkOAuth($consumer_key, $consumer_secret, $options['pkAccessTocken'], $options['pkAccessTockenSec']); 
      $pkURL = trim(str_ireplace('http://', '', $options['pkURL'])); if (substr($pkURL,-1)=='/') $pkURL = substr($pkURL,0,-1);     
      if ($options['pkCat']=='') $options['pkCat'] = ':';    
      if ($options['attchImg']=='1') { if (isset($message['imageURL'])) $imgURL = trim(nxs_getImgfrOpt($message['imageURL'], $options['imgSize'])); else $imgURL = ''; if ($imgURL!='') $msg .= " ".$imgURL; }         
    
      $postArr = array('content'=>$msg, 'qualifier'=>$options['pkCat']);  $postinfo = $tum_oauth->makeReq('http://www.plurk.com/APP/Timeline/plurkAdd', $postArr);  // prr($postinfo);
      if (is_array($postinfo) && isset($postinfo['plurk_id'])) $pkID = $postinfo['plurk_id'];  $code = $tum_oauth->http_code; // echo "XX".print_r($code);  prr($postinfo); // prr($msg); prr($postinfo); echo $code."VVVV"; die("|====");
    
      if ($code == 200 && $pkID!='') return array('postID'=>$pkID, 'isPosted'=>1, 'postURL'=>$pkID, 'pDate'=>date('Y-m-d H:i:s'));
        else { $badOut['Error'] .= " ERROR: - ".$postinfo['error_text']; }  
      return $badOut;
   }    
}}
?>
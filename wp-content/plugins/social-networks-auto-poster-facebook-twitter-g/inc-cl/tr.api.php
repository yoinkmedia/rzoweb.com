<?php    
//## NextScripts FriendFeed Connection Class
$nxs_snapAPINts[] = array('code'=>'TR', 'lcode'=>'tr', 'name'=>'Tumblr');

if (!class_exists("nxs_class_SNAP_TR")) { class nxs_class_SNAP_TR {
    
    var $ntCode = 'TR';
    var $ntLCode = 'tr';     
    
    function doPost($options, $message){ if (!is_array($options)) return false; 
      foreach ($options as $ntOpts) $out[] = $this->doPostToNT($ntOpts, $message);
      return $out;
    }    
    function doPostToNT($options, $message){ $badOut = array('pgID'=>'', 'isPosted'=>0, 'pDate'=>date('Y-m-d H:i:s'), 'Error'=>''); 
      //## Check settings
      if (!is_array($options)) { $badOut['Error'] = 'No Options'; return $badOut; }      
      if (!isset($options['trConsKey']) || trim($options['trConsSec'])=='') { $badOut['Error'] = 'Not Configured'; return $badOut; }                  
      //## Format
      $msg = nxs_doFormatMsg($options['trMsgFormat'], $message); $msgT = nxs_doFormatMsg($options['trMsgTFormat'], $message);
      
      
      //## Post    
      require_once('apis/trOAuth.php'); $consumer_key = $options['trConsKey']; $consumer_secret = $options['trConsSec'];
      $tum_oauth = new TumblrOAuth($consumer_key, $consumer_secret, $options['trAccessTocken']['oauth_token'], $options['trAccessTocken']['oauth_token_secret']); //prr($options);    
    
      $postArr = array('tags'=>$message['tags'], 'date'=>$message['postDate']);
      if (isset($message['imageURL'])) $imgURL = trim(nxs_getImgfrOpt($message['imageURL'], $options['imgSize'])); else $imgURL = '';
      
      if ($options['trPostType']=='I') { $postArr['type'] = 'photo'; $postArr['caption'] = $msg;  $postArr['source'] = $imgURL;       
        if (!isset($options['cImgURL']) || $options['cImgURL']=='' || $options['cImgURL']=='R' ) $postArr['link'] = $message['url']; 
          elseif ($options['cImgURL']=='S' ) { $postArr['link'] = $message['url']; $postArr['link'] = nxs_mkShortURL($postArr['link']);} 
      } elseif($options['trPostType']=='U') { $postArr['type'] = 'audio'; $postArr['caption'] = $msg;  $postArr['external_url'] = $aUrl;
      } elseif($options['trPostType']=='V') { $postArr['type'] = 'video'; $postArr['caption'] = $msg;   
        $embedTxt = '<iframe width="560" height="315" src="http://www.youtube.com/embed/'.$message['videoURL'].'" frameborder="0" allowfullscreen></iframe>';
        $postArr['embed'] = $embedTxt;           
      } else { $postArr['title'] = $msgT; $postArr['type'] = 'text'; $postArr['source'] = $message['url']; $postArr['body'] = $msg; } 
    
    $postinfo = $tum_oauth->post("http://api.tumblr.com/v2/blog/".$options['trURL']."/post", $postArr); //  prr($postinfo);  prr($postArr);
    
    $code = $postinfo->meta->status;// echo "XX".print_r($code);  prr($postinfo); // prr($msg); prr($postinfo); echo $code."VVVV"; die("|====");
    
    if ($code == 201) { return array('postID'=>$postinfo->response->id, 'isPosted'=>1, 'postURL'=>$postinfo->response->id, 'pDate'=>date('Y-m-d H:i:s')); } 
      else  $badOut['Error'] .=  $code . " - ".($postinfo->meta->msg).(isset($postinfo->errmsg)?$postinfo->errmsg:'')." | ".print_r($postinfo, true); 
    return $badOut;      
   }    
}}
?>
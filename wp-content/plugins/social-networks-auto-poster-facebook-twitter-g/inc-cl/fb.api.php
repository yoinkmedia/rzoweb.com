<?php    
//## NextScripts Facebook Connection Class
$nxs_snapAPINts[] = array('code'=>'FB', 'lcode'=>'fb', 'name'=>'Facebook');

if (!class_exists("nxs_class_SNAP_FB")) { class nxs_class_SNAP_FB {
    
    var $ntCode = 'FB';
    var $ntLCode = 'fb';
    
    function doPost($options, $message){ if (!is_array($options)) return false; $out = array(); //return false;
      foreach ($options as $ii=>$ntOpts) $out[$ii] = $this->doPostToNT($ntOpts, $message);
      return $out;
    }
    function doPostToNT($options, $message){ require_once ('apis/facebook.php'); $badOut = array('pgID'=>'', 'isPosted'=>0, 'pDate'=>date('Y-m-d H:i:s'), 'Error'=>''); // prr($message); // prr($options);
      //## Check settings
      if (!is_array($options)) { $badOut['Error'] = 'No Options'; return $badOut; }      
      if (!isset($options['fbAppAuthToken']) || trim($options['fbAppAuthToken'])=='') { $badOut['Error'] = 'No Auth Token Found'; return $badOut; }
      //## Make Post
      $facebook = new NXS_Facebook(array( 'appId' => $options['fbAppID'], 'secret' => $options['fbAppSec'], 'cookie' => true )); 
      if (!isset($options['fbAppPageAuthToken']) || trim($options['fbAppPageAuthToken'])=='') $options['fbAppPageAuthToken'] = $options['fbAppAuthToken'];
      
      //## Some OLD Format Conversion
      if (!isset($options['attachType']) && isset($options['fbAttch'])) $options['attachType'] = $options['fbAttch'];
      if (!isset($options['postType']) && isset($options['fbPostType'])) $options['postType'] = $options['fbPostType'];  $fbPostType = $options['postType'];
      if (!isset($options['pgID']) && isset($options['fbPgID'])) $options['pgID'] = $options['fbPgID'];      
            
      //## Get URL info.      
      if ($fbPostType!='I' && $fbPostType!='T' && isset($options['useFBGURLInfo']) && $options['useFBGURLInfo']=='1') { $url =  $message['url']; $params = array(); 
        $flds = array('id'=>$url, 'scrape'=>'true');      $response =  wp_remote_post('http://graph.facebook.com', array('body' => $flds)); 
        if (is_wp_error($response)) $badOut['Error'] = print_r($response, true)." - ERROR"; else { $response = json_decode($response['body'], true);     //  prr($response);     die();
            if (!empty($response['description'])) $message['urlDescr'] = $response['description'];  if (!empty($response['title'])) $message['urlTitle'] =  $response['title'];
            if (!empty($response['site_name'])) $message['siteName'] = $response['site_name']; elseif ($message['siteName']=='') $message['siteName'] = $message['title'];
            if (!empty($response['image'][0]['url'])) $message['imageURL'] = $response['image'][0]['url'];
        }
      } // prr($message);
      $msg = nxs_doFormatMsg($options['fbMsgFormat'], $message); $imgURL = $message['imageURL']; $fbWhere = 'feed'; 
      $attachType = $options['attachType']; if ($attachType=='1') $attachType = 'A'; else $attachType = 'S';
      if ($options['imgUpl']!='2') $options['imgUpl'] = 'T'; else $options['imgUpl'] = 'A'; $page_id = $options['pgID'];        
      $msg = strip_tags($msg); $msg = str_ireplace('&lt;(")','<(")', $msg); //## FB Smiles FIX 3
      if (substr($msg, 0, 1)=='@') $msg = ' '.$msg; // ERROR] couldn't open file fix
      $mssg = array('access_token'  => $options['fbAppPageAuthToken'], 'message' => $msg); //prr($message);
      
      if ($fbPostType=='I' && trim($imgURL)=='') $fbPostType='T';
      if ($fbPostType=='A' || $fbPostType=='') {
        if (($attachType=='A' || $attachType=='S')) { $attArr = array('name' => $message['urlTitle'], 'caption' => $message['siteName'], 'link' =>$message['url'], 'description' => $message['urlDescr']); $mssg = array_merge($mssg, $attArr); ; }
        if ($attachType=='A') $mssg['actions'] = array(array('name' => $message['siteName'], 'link' =>$message['url']));        
        if (trim($imgURL)!='') $mssg['picture'] = $imgURL;  if (trim($message['videoURL'])!='') $mssg['source'] = $message['videoURL'];        
      } elseif ($fbPostType=='I') { $facebook->setFileUploadSupport(true); $fbWhere = 'photos'; $mssg['url'] = $imgURL; 
        if ($options['imgUpl']=='T') { //## Try to Post to TImeline
          $aacct = array('access_token'  => $options['fbAppPageAuthToken']);  
          try { $albums = $facebook->api("/$page_id/albums", "get", $aacct); } catch (NXS_FacebookApiException $e) { $badOut['Error'] = ' [ERROR] '.$e->getMessage()."<br/>\n"; }
          
          if (isset($albums) && isset($albums["data"]) && is_array($albums["data"])) foreach ($albums["data"] as $album) { if ($album["type"] == "wall") { $chosen_album = $album; break;}}
          if (isset($chosen_album) && isset($chosen_album["id"])) $page_id = $chosen_album["id"];
        }        
      }      
      try { $ret = $facebook->api("/$page_id/".$fbWhere, "post", $mssg);} catch (NXS_FacebookApiException $e) { $badOut['Error'] = ' [ERROR] '.$e->getMessage()."<br/>\n";
        if (stripos($e->getMessage(),'This API call requires a valid app_id')!==false) { 
          if ( !is_numeric($page_id) && stripos($options['fbURL'], '/groups/')!=false) $badOut['Error'] .= ' [ERROR] Unrecognized Facebook Group ID. Please use numeric ID.'; 
            else $badOut['Error'] .= " [ERROR] (invalid app_id) Authorization Error. <br/>\r\n<br/>\r\n Possible Reasons: <br/>\r\n 1. Your app is not authorized. Please go to the Plugin Settings - Facebook and authorize it.<br/>\r\n 2. The current authorized user have no rights to post to the specified page. Please login to Facebook as the correct user and Re-Authorize the Plugin.<br/>\r\n 3. You clicked 'Skip' or unchecked the 'Manage Pages' or 'Post on your behalf' permissions when Authorization wizard asked you. Please Re-Authorize the Plugin<br/>\r\n"; 
        }
      }
      if (isset($ret['id']) && $ret['id']!='') { 
          $pgID = (isset($ret['post_id']) && strpos($ret['post_id'],'_')!==false)?$ret['post_id']:$ret['id']; $pgg = explode('_', $pgID); $postID = $pgg[1];
          $pgURL = 'http://www.facebook.com/'.$options['pgID'].'/posts/'.$postID;
          return array('isPosted'=>'1', 'postID'=>$pgID, 'postURL'=>$pgURL, 'pDate'=>date('Y-m-d H:i:s'));
      } else return $badOut;
    }
}}
?>
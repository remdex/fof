<?php

$albumto = $Params['user_object'];
$photo_id = $Params['user_parameters']['image_id'];
$currentUser = erLhcoreClassUser::instance();

$facebook = new Facebook(array(
  'appId'  => erConfigClassLhConfig::getInstance()->getSetting( 'facebook', 'app_id' ),
  'secret' => erConfigClassLhConfig::getInstance()->getSetting( 'facebook', 'secret' ) ,
));

$photoDetails = $facebook->api($photo_id);

function downloadImage($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5');
    $content = curl_exec($ch);
    return $content;
}

$imageContent = downloadImage($photoDetails['source']);

if ($currentUser->isLogged())
    $user_id = $currentUser->getUserID();	
else 
    $user_id = erConfigClassLhConfig::getInstance()->getSetting( 'user_settings', 'anonymous_user_id' );	

$db = ezcDbInstance::get();
$db->beginTransaction();

$session = erLhcoreClassGallery::getSession();

if ($currentUser->isLogged())
    $user_id = $currentUser->getUserID();	
else 
    $user_id = erConfigClassLhConfig::getInstance()->getSetting( 'user_settings', 'anonymous_user_id' );	
    
$result['filepath'] = 'var/tmpfiles/fbphoto_'.basename($photoDetails['source']);  
file_put_contents($result['filepath'],$imageContent);
$result['filename'] = basename($photoDetails['source']);
$result['filename_user'] = isset($photoDetails['name']) ? $photoDetails['name'].'.jpg' : '1.jpg';

if (($filetype = erLhcoreClassModelGalleryFiletype::isValidLocal($result['filepath'])) !== false)
{
    $image = new erLhcoreClassModelGalleryImage();
    $image->aid = $albumto->aid;	
    $session->save($image);

    try {
    	   $config = erConfigClassLhConfig::getInstance();
    
    	   $photoDir = 'albums/userpics/'.date('Y').'y/'.date('m').'/'.date('d').'/'.$user_id.'/'.$albumto->aid;
    	   $photoDirPhoto = 'userpics/'.date('Y').'y/'.date('m').'/'.date('d').'/'.$user_id.'/'.$albumto->aid.'/';
    	   erLhcoreClassImageConverter::mkdirRecursive($photoDir);
    	   $fileNamePhysic = erLhcoreClassImageConverter::sanitizeFileName($result['filename_user']);
    
    	   if ($config->getSetting( 'site', 'file_storage_backend' ) == 'filesystem')
           {    	       
               if (file_exists($photoDir.'/'.$fileNamePhysic)) {
               		$fileNamePhysic = erLhcoreClassModelForgotPassword::randomPassword(5).time().'-'.$fileNamePhysic;
               }
           }
    
           $filetype->process($image,array(
               'photo_dir'        => $photoDir,
               'photo_dir_photo'  => $photoDirPhoto,
               'file_name_physic' => $fileNamePhysic,
               'file_upload_path' => $result['filepath']
           ));
    
       $image->hits = 0;
       $image->ctime = time();
       $image->owner_id = $user_id;
       $image->pic_rating = 0;
       $image->votes = 0;
       
       $image->title = isset($photoDetails['name']) ? $photoDetails['name'] : '';
       $image->caption = '';
       $image->keywords =  '';
       
       $canApproveSelfImages = $currentUser->hasAccessTo('lhgallery','auto_approve_self_photos');
       $canApproveAllImages =  $currentUser->hasAccessTo('lhgallery','auto_approve');           
       $canChangeApprovement = ($image->owner_id == $currentUser->getUserID() && $canApproveSelfImages) || ($canApproveAllImages == true);
    
       $image->approved = $canChangeApprovement == true ? 1 : 0;
       	       	       
       $image->anaglyph = 0;
      
       $session->update($image);
       $image->clearCache();
       
       // Index colors
       erLhcoreClassPalleteIndexImage::indexImage($image,true);
       
       // Index face if needed
       erLhcoreClassModelGalleryFaceData::indexImage($image,true);
       
       // Index in search table
       erLhcoreClassModelGallerySphinxSearch::indexImage($image,true);
       
       // Index in imgseek service
	   erLhcoreClassModelGalleryImgSeekData::indexImage($image,true);
	       
       // Expires last uploads shard index
       erLhcoreClassGallery::expireShardIndexByIdentifier(array('last_uploads','last_commented'));
              
       erLhcoreClassModelGalleryAlbum::updateAddTime($image);
       
       echo json_encode(array('success' => 'true'));	       
    } catch (Exception $e) {
        $session->delete($image);
        erLhcoreClassLog::write('Exception during upload'.$e);
        return ;
    }
    
    $db->commit(); 
}
exit;
?>
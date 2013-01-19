<?php
$tpl = erLhcoreClassTemplate::getInstance( 'lharticle/editarticle.tpl.php');

$Article = erLhcoreClassArticle::getSession()->load( 'erLhcoreClassModelArticle', (int)$Params['user_parameters']['article_id']);  

$_SESSION['has_access_to_editor'] = 1;

if (isset($_POST['UpdateArticle']))
{        
    $Category = erLhcoreClassModelArticleCategory::getCategoryByID($Article->category_id); 
        
    $uploadLogo = false;    
    if ($_FILES["ArticleThumb"]["error"] != 4) {
        if (isset($_FILES["ArticleThumb"]) && is_uploaded_file($_FILES["ArticleThumb"]["tmp_name"]) && $_FILES["ArticleThumb"]["error"] == 0 && erLhcoreClassImageConverter::isPhoto('ArticleThumb'))
    	{    		
    	    if($Article->has_photo){
    			$Article->removePhotos();
    	    }    	    
    		$uploadLogo = true;
    		
    	} else {
    	    $Errors[] =  'Incorrect photo file!';
    	}
    }
    
    $delete_logo = false;
	if (isset($_POST['DeletePhoto']) && $_POST['DeletePhoto'] == 1) {		
       $Article->removePhoto();        	       
	}
	
    
    $config = erConfigClassLhConfig::getInstance();
    
    if ($uploadLogo == true)
    {                
       $photoDir = 'var/media/'.$Article->id;
       if (!file_exists($photoDir))
       mkdir($photoDir,$config->getSetting( 'site', 'StorageDirPermissions' ));

       $photoDir = 'var/media/'.$Article->id.'/images';
       if (!file_exists($photoDir))
       mkdir($photoDir,$config->getSetting( 'site', 'StorageDirPermissions' ));	       

       $fileName = erLhcoreClassSearchHandler::moveUploadedFile('ArticleThumb',$photoDir . '/' );
           
       $Article->has_photo = 1;
       $Article->file_name = $fileName;      
    }
    
    $Article->article_name = $_POST['ArticleName'];
    $Article->alias_url = $_POST['AliasURL'];
    $Article->alternative_url = $_POST['AlternativeURL'];
    $Article->is_modal = (isset($_POST['IsModal']) && $_POST['IsModal'] == 'on') ? 1 : 0;
    $Article->pos = $_POST['pos'];
    $Article->intro = str_replace(array('%7B','%7D'),array('{','}'),$_POST['ArticleIntro']); 
    $Article->body = str_replace(array('%7B','%7D'),array('{','}'),$_POST['ArticleBody']);
    $Article->category_id_parent = $Category->parent;        

    erLhcoreClassArticle::getSession()->update($Article);
    
    CSCacheAPC::getMem()->increaseCacheVersion('article_cache_version');
    
//    erLhcoreClassModule::redirect('article/managesubcategories/'.$Article->category_id);
//    return; 
}


$tpl->set('article',$Article);
$Result['content'] = $tpl->fetch();


$Result['path'] = array(array('title' => $Article->article_name));


?>
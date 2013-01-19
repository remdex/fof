<?php
$tpl = erLhcoreClassTemplate::getInstance( 'lharticle/new.tpl.php');

$Article = new erLhcoreClassModelArticle();

$_SESSION['has_access_to_editor'] = 1;

if (isset($_POST['UpdateArticle']))
{        
    $Category = erLhcoreClassModelArticleCategory::getCategoryByID($Params['user_parameters']['category_id']);    
    $Article = new erLhcoreClassModelArticle();        
    $Article->article_name = $_POST['ArticleName'];
    $Article->category_id = $Params['user_parameters']['category_id'];
    $Article->intro = $_POST['ArticleIntro'];
    $Article->body = $_POST['ArticleBody'];
    $Article->pos = $_POST['pos'];
    $Article->alias_url = $_POST['AliasURL'];
    $Article->alternative_url = $_POST['AlternativeURL'];
    $Article->is_modal = (isset($_POST['IsModal']) && $_POST['IsModal'] == 'on') ? 1 : 0;
    $Article->publishtime = time();                
    $Article->category_id_parent = $Category->parent; 
    erLhcoreClassArticle::getSession()->save($Article);  
    
    
    $uploadLogo = false;    
    if ($_FILES["ArticleThumb"]["error"] != 4) {
        if (isset($_FILES["ArticleThumb"]) && is_uploaded_file($_FILES["ArticleThumb"]["tmp_name"]) && $_FILES["ArticleThumb"]["error"] == 0 && erLhcoreClassImageConverter::isPhoto('ArticleThumb'))
    	{        
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
    
    erLhcoreClassArticle::getSession()->update($Article);
    
    CSCacheAPC::getMem()->increaseCacheVersion('article_cache_version');
    
    erLhcoreClassModule::redirect('article/managesubcategories/'.$Article->category_id);
    return; 
}

$tpl->set('category_id',$Params['user_parameters']['category_id']);
$tpl->set('article',$Article);


$Result['content'] = $tpl->fetch();


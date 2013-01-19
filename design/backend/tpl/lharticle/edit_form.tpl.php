
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('article/editstatic','Article name');?> *</label>
<input type="text" name="ArticleName" value="<?=htmlspecialchars($static->name);?>" />

<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('article/editstatic','Body');?> *</label>
<?    	
    $oFCKeditor = new CKEditor() ;        
    $oFCKeditor->basePath = erLhcoreClassDesign::design('js/ckeditor').'/' ;  
    CKFinder::SetupCKEditor($oFCKeditor, erLhcoreClassDesign::design('js/ckfinder/'));        
    $oFCKeditor->config['height'] = 300;
    $oFCKeditor->config['width'] = '99%';        
    $oFCKeditor->editor('ArticleBody',$static->content) ;
?> 

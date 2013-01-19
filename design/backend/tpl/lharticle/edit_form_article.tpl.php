<label>Name</label>
<input type="text" class="inputfield" name="ArticleName" value="<?=htmlspecialchars($article->article_name)?>" />


<label>Alternative URL</label>
<input type="text" class="inputfield" name="AlternativeURL" value="<?=htmlspecialchars($article->alternative_url)?>" />

<label>Alias URL</label>
<input type="text" class="inputfield" name="AliasURL" value="<?=htmlspecialchars($article->alias_url)?>" />
 

<label>Is modal <input type="checkbox" name="IsModal" value="on" <?php $article->is_modal == 1 ? print 'checked="checked"' : '' ?> /></label>



<label>Intro</label>
<?
$oFCKeditor = new CKEditor() ;        
$oFCKeditor->basePath = erLhcoreClassDesign::design('js/ckeditor').'/' ;
CKFinder::SetupCKEditor($oFCKeditor, erLhcoreClassDesign::design('js/ckfinder/'));   
$oFCKeditor->config['height'] = 300;
$oFCKeditor->config['width'] = '99%';
$oFCKeditor->config['customConfig'] = 'customConfigFile.js';
$oFCKeditor->editor('ArticleIntro',$article->intro) ;
?>
        <br />

<label>Thumbnail</label>   
<input type="file" name="ArticleThumb" value="" > (*.jpg;*.png;*)

<?php if (is_numeric($article->id)) : ?>
<?php if ($article->has_photo == 1) : ?> 
<br /><img width="60" src="<?=$article->thumb_article?>?time=<?=time()?>" alt=""/><br />
<label>
<input type="checkbox" name="DeletePhoto" value="1" /> Delete</label>
<?php endif;?> 
<?php endif;?> 
		
		
<label>Content</label>   
 <?
$oFCKeditor = new CKEditor() ;        
$oFCKeditor->basePath = erLhcoreClassDesign::design('js/ckeditor').'/' ;  
CKFinder::SetupCKEditor($oFCKeditor, erLhcoreClassDesign::design('js/ckfinder/')); 
$oFCKeditor->config['height'] = 300;
$oFCKeditor->config['width'] = '99%';
$oFCKeditor->config['customConfig'] = 'customConfigFile.js';
$oFCKeditor->editor('ArticleBody',$article->body) ;
?>
<br />
		 
<label>Position</label>   
<input type="text" class="inputfield" name="pos" value="<?=htmlspecialchars($article->pos)?>" />
   
<input type="submit" class="small button" name="UpdateArticle" value="Save" />
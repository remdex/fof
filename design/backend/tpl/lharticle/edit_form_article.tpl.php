<table width="100%">
    <tr>
        <td width="1%">Name</td>
        <td><input type="text" class="inputfield" name="ArticleName" value="<?=htmlspecialchars($article->article_name)?>" /></td>
    </tr>
    <tr>
        <td width="1%" nowrap="nowrap">Alternative URL</td>
        <td><input type="text" class="inputfield" name="AlternativeURL" value="<?=htmlspecialchars($article->alternative_url)?>" /></td>
    </tr>
    <tr>
        <td width="1%" nowrap="nowrap">Alias URL</td>
        <td><input type="text" class="inputfield" name="AliasURL" value="<?=htmlspecialchars($article->alias_url)?>" /></td>
    </tr>
    <tr>
        <td width="1%" nowrap="nowrap">Is modal</td>
        <td><input type="checkbox" name="IsModal" value="on" <?php $article->is_modal == 1 ? print 'checked="checked"' : '' ?> /></td>
    </tr>
    <tr valign="top">
        <td width="1%">Intro</td>
        <td><?
        $oFCKeditor = new CKEditor() ;        
        $oFCKeditor->basePath = erLhcoreClassDesign::design('js/ckeditor').'/' ;
        CKFinder::SetupCKEditor($oFCKeditor, erLhcoreClassDesign::design('js/ckfinder/'));   
        $oFCKeditor->config['height'] = 300;
        $oFCKeditor->config['width'] = '99%';
        $oFCKeditor->config['customConfig'] = 'customConfigFile.js';
        $oFCKeditor->editor('ArticleIntro',$article->intro) ;
        ?></td>
    </tr>   
    <tr>
        <td>Thumbnail</td>
        <td>        
        <input type="file" name="ArticleThumb" value="" > (*.jpg;*.png;*)
        
        <?php if (is_numeric($article->id)) : ?>
		<?php if ($article->has_photo == 1) : ?> 
		<br /><img width="60" src="<?=$article->thumb_article?>?time=<?=time()?>" alt=""/><br />
		<label>
		<input type="checkbox" name="DeletePhoto" value="1" /> Delete</label>
		<?php endif;?> 
		<?php endif;?> 
		       
        </td>
    </tr>
    <tr>
        <td width="1%" valign="top">Content</td>
        <td>
        <?
        $oFCKeditor = new CKEditor() ;        
        $oFCKeditor->basePath = erLhcoreClassDesign::design('js/ckeditor').'/' ;  
        CKFinder::SetupCKEditor($oFCKeditor, erLhcoreClassDesign::design('js/ckfinder/')); 
        $oFCKeditor->config['height'] = 300;
        $oFCKeditor->config['width'] = '99%';
        $oFCKeditor->config['customConfig'] = 'customConfigFile.js';
        $oFCKeditor->editor('ArticleBody',$article->body) ;
        ?>
        </td>
    </tr>
    <tr>
        <td>Position</td>
        <td><input type="text" class="inputfield" name="pos" value="<?=htmlspecialchars($article->pos)?>" /></td>
    </tr>
    <tr>
        <td colspan="2"><input type="submit" class="default-button" name="UpdateArticle" value="Save" /></td>
    </tr>
</table>
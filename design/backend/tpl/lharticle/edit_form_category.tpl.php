<label>Name</label>
<input type="text" class="inputfield" name="CategoryName" value="<?=htmlspecialchars($category_new->category_name)?>" />

<label>Intro</label>
<?
$oFCKeditor = new CKEditor() ;        
$oFCKeditor->basePath = erLhcoreClassDesign::design('js/ckeditor').'/' ;  
CKFinder::SetupCKEditor($oFCKeditor, erLhcoreClassDesign::design('js/ckfinder/')); 
$oFCKeditor->config['height'] = 300;
$oFCKeditor->config['width'] = '100%';
$oFCKeditor->editor('Intro',$category_new->intro) ;
?>
<br />

<label>Position</label>
<input type="text" class="inputfield" name="CategoryPos" value="<?=htmlspecialchars($category_new->pos)?>" />

<label>Hide articles</label>
<input type="checkbox" class="inputfield" name="HideArticles" value="on" <?php echo $category->hide_articles == 1 ? 'checked="checked"' : ''?> />
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/grouplist','Category edit');?></h1>

<form action="<?=erLhcoreClassDesign::baseurl('article/editcategory')?>/<?=$category->id?>" method="post">

<label>Name</label>
<input type="text" class="inputfield" name="CategoryName" value="<?=htmlspecialchars($category->category_name)?>" />


<label>Intro</label>
<?
$oFCKeditor = new CKEditor() ;        
$oFCKeditor->basePath = erLhcoreClassDesign::design('js/ckeditor').'/' ;  
CKFinder::SetupCKEditor($oFCKeditor, erLhcoreClassDesign::design('js/ckfinder/')); 
$oFCKeditor->config['height'] = 300;
$oFCKeditor->config['width'] = '100%';
$oFCKeditor->editor('Intro',$category->intro) ;
?>
<br />

<label>Position</label>
<input type="text" class="inputfield" name="CategoryPos" value="<?=htmlspecialchars($category->placement)?>" />

<input type="submit" class="small button" name="UpdateCategory" value="Update" />

</form>
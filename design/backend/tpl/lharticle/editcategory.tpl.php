<div class="header-list"><h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/grouplist','Category edit');?></h1></div>

<form action="<?=erLhcoreClassDesign::baseurl('article/editcategory')?>/<?=$category->id?>" method="post">
<table>
   <tr>
        <td>Name</td>
        <td><input type="text" class="inputfield" name="CategoryName" value="<?=htmlspecialchars($category->category_name)?>" /></td>
   </tr>    
   <tr>
        <td width="1%">Intro</td>
        <td>
        <?
        $oFCKeditor = new CKEditor() ;        
        $oFCKeditor->basePath = erLhcoreClassDesign::design('js/ckeditor').'/' ;  
        CKFinder::SetupCKEditor($oFCKeditor, erLhcoreClassDesign::design('js/ckfinder/')); 
        $oFCKeditor->config['height'] = 300;
        $oFCKeditor->config['width'] = 630;
        $oFCKeditor->editor('Intro',$category->intro) ;
        ?>
    </td>
    </tr>
       <tr>  
        <td>Position</td>
        <td><input type="text" class="inputfield" name="CategoryPos" value="<?=htmlspecialchars($category->placement)?>" /></td>
    </tr>
    <tr>
        <td colspan="2"><input type="submit" class="default-button" name="UpdateCategory" value="Update" /></td>
    </tr>
</table>
</form>

</div>
<div class="header-list"><h1>Manage categories</h1></div>

<form action="<?=erLhcoreClassDesign::baseurl('article/managecategories')?>" method="post">
<table class="lentele" cellpadding="0" cellspacing="0" width="100%">
<tr>
    <th>ID</th>
    <th>Name</th>    
    <th>Position</th>
    <th width="1%">&nbsp;</th>
    <th width="1%">&nbsp;</th>
  
</tr>
<? foreach (erLhcoreClassModelArticleCategory::getTopLevelCategories() as $category) : ?>
    <tr>
        <td width="1%"><?=$category->id?></td>
        <td><a href="<?=erLhcoreClassDesign::baseurl('article/managesubcategories')?>/<?=$category->id?>"><?=$category->category_name?></a></td>     
        <td><?=$category->placement?></td>      
        <td><a href="<?=erLhcoreClassDesign::baseurl('article/editcategory')?>/<?=$category->id?>"><img src="<?=erLhcoreClassDesign::design('images/icons/page_edit.png');?>" alt="Edit" title="Edit" /></a></td>       
        <td><a onclick="return confirm('Are you sure?')" href="<?=erLhcoreClassDesign::baseurl('article/deletecategory')?>/<?=$category->id?>"><img src="<?=erLhcoreClassDesign::design('images/icons/delete.png');?>" alt="Delete" title="Delete" /></a></td>       
    </tr>
<? endforeach; ?>
</table>
</form>
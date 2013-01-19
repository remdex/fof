<h1>Manage categories</h1>

<form action="<?=erLhcoreClassDesign::baseurl('article/managecategories')?>" method="post">
<table class="lentele" cellpadding="0" cellspacing="0" width="100%">
<thead>
<tr>
    <th>ID</th>
    <th>Name</th>    
    <th>Position</th>
    <th width="1%">&nbsp;</th>
    <th width="1%">&nbsp;</th>  
</tr>
</thead>
<? foreach (erLhcoreClassModelArticleCategory::getTopLevelCategories() as $category) : ?>
    <tr>
        <td width="1%"><?=$category->id?></td>
        <td><a href="<?=erLhcoreClassDesign::baseurl('article/managesubcategories')?>/<?=$category->id?>"><?=$category->category_name?></a></td>     
        <td><?=$category->placement?></td>      
        <td><a class="tiny button round" href="<?=erLhcoreClassDesign::baseurl('article/editcategory')?>/<?=$category->id?>">Edit</a></td>       
        <td><a class="tiny alert button round" onclick="return confirm('Are you sure?')" href="<?=erLhcoreClassDesign::baseurl('article/deletecategory')?>/<?=$category->id?>">Delete</a></td>       
    </tr>
<? endforeach; ?>
</table>
</form>
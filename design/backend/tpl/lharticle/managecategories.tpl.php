
<h1>Manage categories<?php if ($category->id > 0) : ?> - <?php echo htmlspecialchars($category->category_name)?><?php endif;?></h1>


<?php $childCategories = erLhcoreClassModelArticleCategory::getList(array('limit' => 10000,'filter' => array('parent_id' => (int)$category->id))); ?>
<?php if (!empty($childCategories)) : ?>
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
<? foreach ($childCategories as $categorychild) : ?>
    <tr>
        <td width="1%"><?=$categorychild->id?></td>
        <td><a href="<?=erLhcoreClassDesign::baseurl('article/managecategories')?>/<?=$categorychild->id?>"><?php echo htmlspecialchars($categorychild->category_name)?></a></td>     
        <td><?=$categorychild->pos?></td>      
        <td><a class="tiny button round" href="<?=erLhcoreClassDesign::baseurl('article/editcategory')?>/<?=$categorychild->id?>">Edit</a></td>       
        <td><a class="tiny alert button round" onclick="return confirm('<?=erTranslationClassLhTranslation::getInstance()->getTranslation('kernel/messages','Are you sure?');?>')" href="<?=erLhcoreClassDesign::baseurl('article/deletecategory')?>/<?=$categorychild->id?>">Delete</a></td>       
    </tr>
<? endforeach; ?>
</table>
</form>
<?php endif;?>

<ul class="button-group radius">
<li><a class="button round small" href="<?=erLhcoreClassDesign::baseurl('article/newcategory')?>/<?=(int)$category->id?>">New category</a></li>
<?php if ((int)$category->id > 0) : ?>
<li><a class="button round small" href="<?=erLhcoreClassDesign::baseurl('article/editcategory')?>/<?=(int)$category->id?>">Edit category</a></li>
<?php endif;?>
</ul>


<?php if ((int)$category->id > 0) : ?>

<div class="header-list"><h1>Articles</h1></div>

<?php if (!empty($list)) : ?>
<form action="<?=erLhcoreClassDesign::baseurl('article/managesubcategories')?>/<?=$category->id?>" method="post">
    <table class="lentele" cellpadding="0" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th width="1%">Modified</th>
        <th width="1%">Position</th>
        <th width="1%">&nbsp;</th>
        <th width="1%">&nbsp;</th>      
    </tr>
    </thead>
    <? foreach ($list as $article) : ?>
        <tr>
            <td width="1%"><?=$article->id?></td>
            <td><?=$article->article_name?></td> 
            <td nowrap><?=$article->mtime_front?></td>               
            <td><?=$article->pos?></td>
            <td><a class="tiny button round" href="<?=erLhcoreClassDesign::baseurl('article/editarticle')?>/<?=$article->id?>">Edit</a></td>       
            <td><a class="tiny alert button round" onclick="return confirm('<?=erTranslationClassLhTranslation::getInstance()->getTranslation('kernel/messages','Are you sure?');?>')" href="<?=erLhcoreClassDesign::baseurl('article/deletearticle')?>/<?=$article->id?>">Delete</a></td>       
        </tr>
    <? endforeach; ?>
    </table>
    
<?php if (isset($pages)) : ?>
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>
<? endif;?>
</form>
<?php endif; ?>


<a class="button round small" href="<?=erLhcoreClassDesign::baseurl('article/new')?>/<?=$category->id?>">New article</a>

<?php endif; ?>
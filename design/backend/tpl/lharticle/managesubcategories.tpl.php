<div class="header-list"><h1>Articles</h1></div>
<form action="<?=erLhcoreClassDesign::baseurl('article/managesubcategories')?>/<?=$category->id?>" method="post">
    <table class="lentele" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th width="1%">Position</th>
        <th width="1%">&nbsp;</th>
        <th width="1%">&nbsp;</th>      
    </tr>
    <? foreach (erLhcoreClassModelArticle::getArticlesByCategory($category->id) as $article) : ?>
        <tr>
            <td width="1%"><?=$article->id?></td>
            <td><?=$article->article_name?></td>               
            <td><?=$article->pos?></td>               
            <td><a href="<?=erLhcoreClassDesign::baseurl('article/editarticle')?>/<?=$article->id?>"><img src="<?=erLhcoreClassDesign::design('images/icons/page_edit.png');?>" alt="Edit" title="Edit" /></a></td>       
            <td><a href="<?=erLhcoreClassDesign::baseurl('article/deletearticle')?>/<?=$article->id?>"><img src="<?=erLhcoreClassDesign::design('images/icons/delete.png');?>" alt="Delete" title="Delete" /></a></td>       
        </tr>
    <? endforeach; ?>
    </table>
<br />
<a href="<?=erLhcoreClassDesign::baseurl('article/new')?>/<?=$category->id?>">New article</a>
</form>
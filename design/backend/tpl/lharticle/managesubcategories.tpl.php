<div class="header-list"><h1>Articles</h1></div>
<form action="<?=erLhcoreClassDesign::baseurl('article/managesubcategories')?>/<?=$category->id?>" method="post">
    <table class="lentele" cellpadding="0" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th width="1%">Position</th>
        <th width="1%">&nbsp;</th>
        <th width="1%">&nbsp;</th>      
    </tr>
    </thead>
    <? foreach (erLhcoreClassModelArticle::getArticlesByCategory($category->id) as $article) : ?>
        <tr>
            <td width="1%"><?=$article->id?></td>
            <td><?=$article->article_name?></td>               
            <td><?=$article->pos?></td>               
            <td><a class="tiny button round" href="<?=erLhcoreClassDesign::baseurl('article/editarticle')?>/<?=$article->id?>">Edit</a></td>       
            <td><a class="tiny alert button round" href="<?=erLhcoreClassDesign::baseurl('article/deletearticle')?>/<?=$article->id?>">Delete</a></td>       
        </tr>
    <? endforeach; ?>
    </table>
<br />
<a class="button round small" href="<?=erLhcoreClassDesign::baseurl('article/new')?>/<?=$category->id?>">New article</a>
</form>
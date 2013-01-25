<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('article/staticlist','Static articles')?></h1>

<table class="lentele" cellpadding="0" cellspacing="0" width="100%">
<thead>
<tr>
    <th>ID</th>
    <th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('article/staticlist','Name')?></th>
    <th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('article/staticlist','Modified')?></th>
    <th width="1%">&nbsp;</th>      
</tr>
</thead>
<? foreach (erLhcoreClassModelArticleStatic::getList(array('limit' => 100000)) as $article) : ?>
    <tr>
        <td width="1%"><?=htmlspecialchars($article->id)?></td>
        <td><?=htmlspecialchars($article->name)?></td>      
        <td><?=$article->mtime_front?></td>      
        <td><a class="tiny button round" href="<?=erLhcoreClassDesign::baseurl('article/editstatic')?>/<?=$article->id?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('article/staticlist','Edit')?></a></td>       
    </tr>
<? endforeach; ?>
</table>

<a class="small button" href="<?=erLhcoreClassDesign::baseurl('article/newstatic')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('article/staticlist','New article')?></a>


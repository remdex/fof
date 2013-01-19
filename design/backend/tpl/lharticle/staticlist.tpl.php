<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('article/staticlist','Static articles')?></h1>
</div>

    <table class="lentele" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <th>ID</th>
        <th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('article/staticlist','Name')?></th>
        <th width="1%">&nbsp;</th>      
    </tr>
    <? foreach (erLhcoreClassModelArticleStatic::getArticles() as $article) : ?>
        <tr>
            <td width="1%"><?=htmlspecialchars($article->id)?></td>
            <td><?=htmlspecialchars($article->name)?></td>      
            <td><a onclick="return confirm('Are you sure?')" href="<?=erLhcoreClassDesign::baseurl('article/editstatic')?>/<?=$article->id?>"><img src="<?=erLhcoreClassDesign::design('images/icons/page_edit.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('article/staticlist','Edit')?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('article/staticlist','Edit')?>" /></a></td>       
        </tr>
    <? endforeach; ?>
    </table>
    <a href="<?=erLhcoreClassDesign::baseurl('article/newstatic')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('article/staticlist','New article')?> &raquo;</a>


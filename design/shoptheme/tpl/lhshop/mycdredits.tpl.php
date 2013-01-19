<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/mycredits','My credits balanse')?></h1>
</div>
<p><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/mycredits','You have')?>: <strong><?=$credits->credits?></strong> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/mycredits','in your account')?></p>
<p>
<a href="<?=erLhcoreClassDesign::baseurl('/shop/buycredits')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/mycredits','Buy credits')?></a>
</p>
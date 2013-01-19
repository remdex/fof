<? if (isset($Result['path'])) : 		
$pathElementCount = count($Result['path'])-1;
if ($pathElementCount >= 0):
?>			
<ol id="breadcrumb" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
<li><a rel="home" itemprop="url" href="<?=erLhcoreClassDesign::baseurl()?>"><span itemprop="title"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Home')?></span></a></li>
<? foreach ($Result['path'] as $key => $pathItem) : if (isset($pathItem['url']) && $pathElementCount != $key) { ?><li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="<?=$pathItem['url']?>" itemprop="url"><span itemprop="title"><?=htmlspecialchars($pathItem['title'])?></span></a></li><? } else { ?><li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title"><?=htmlspecialchars($pathItem['title'])?></span></li><? }; ?><? endforeach; ?>
</ol><? endif; ?>
<?php endif;?>
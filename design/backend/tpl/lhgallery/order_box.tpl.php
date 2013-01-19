<?php

$sortNewMode = '';

$sortArrayAppend = array(
    'new'               => $sortNewMode,
    'newasc'            => '/(sort)/newasc',
    'popular'           => '/(sort)/popular',
    'popularasc'        => '/(sort)/popularasc',
    'lasthits'          => '/(sort)/lasthits',
    'lasthitsasc'       => '/(sort)/lasthitsasc',
    'toprated'          => '/(sort)/toprated',
    'topratedasc'       => '/(sort)/topratedasc',
    'lastrated'         => '/(sort)/lastrated',
    'lastratedasc'      => '/(sort)/lastratedasc',
    'lastcommented'     => '/(sort)/lastcommented',
    'lastcommentedasc'  => '/(sort)/lastcommentedasc',
    'relevance'         => '',
    'relevanceasc'      => '/(sort)/relevanceasc',
);

?>

<div class="right order-nav" id="sort-nav">
<ul>
    <li class="current-sort" ><a class="choose-sort"><span></span></a>
        <ul class="sort-box">     
            <li><a class="da<?=$mode == 'new' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort,$sortNewMode?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Last uploaded')?></a>
            <li class="sep"><a class="ar<?=$mode == 'newasc' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort?>/(sort)/newasc"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Last uploaded')?></a>
            <li><a class="da<?=$mode == 'popular' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort?>/(sort)/popular"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Most popular')?></a>
            <li class="sep"><a class="ar<?=$mode == 'popularasc' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort?>/(sort)/popularasc"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Most popular')?></a>
            <li><a class="da<?=$mode == 'lasthits' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort?>/(sort)/lasthits"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Last hits')?></a>
            <li class="sep"><a class="ar<?=$mode == 'lasthitsasc' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort?>/(sort)/lasthitsasc"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Last hits')?></a> 
            <li><a class="da<?=$mode == 'toprated' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort?>/(sort)/toprated"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Top rated')?></a>
            <li class="sep"><a class="ar<?=$mode == 'topratedasc' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort?>/(sort)/topratedasc"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Top rated')?></a>
            <li><a class="da<?=$mode == 'lastrated' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort?>/(sort)/lastrated"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Last rated')?></a>
            <li class="sep"><a class="ar<?=$mode == 'lastratedasc' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort?>/(sort)/lastratedasc"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Last rated')?></a>
            <li><a class="da<?=$mode == 'lastcommented' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort?>/(sort)/lastcommented"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Last commented')?></a>
            <li class="sep"><a class="ar<?=$mode == 'lastcommentedasc' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort?>/(sort)/lastcommentedasc"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Last commented')?></a> 
            <li><a class="da<?=$mode == 'filename' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort?>/(sort)/filename"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Filename')?></a>
            <li><a class="ar<?=$mode == 'filenameasc' ? ' selor' : ''?>" href="<?=$urlSortBase?><?echo $urlAppendSort?>/(sort)/filenameasc"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Filename')?></a>
        </ul>
</ul>
</div>

<script>
hw.initSortBox('#sort-nav');
</script>
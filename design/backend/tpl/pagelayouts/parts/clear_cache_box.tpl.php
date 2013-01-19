<h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Clear cache')?></h3> 
<ul class="circle">
   <li><a href="<?=erLhcoreClassDesign::baseurl('system/expirecache')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Clean all cache');?></a></li>   
   <li><a href="<?=erLhcoreClassDesign::baseurl('system/cachestatus')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Cache status');?></a></li>   
</ul>
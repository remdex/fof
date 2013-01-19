<div class="header-list"><h2><?=erTranslationClassLhTranslation::getInstance()->getTranslation('system/configuration','System configuration');?></h1></div>
<ul class="circle">
    <li><a href="<?=erLhcoreClassDesign::baseurl('user/userlist')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('system/configuration','Users');?></a></li>
    <li><a href="<?=erLhcoreClassDesign::baseurl('user/grouplist')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('system/configuration','List of groups');?></a></li>
    <li><a href="<?=erLhcoreClassDesign::baseurl('permission/roles')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('system/configuration','List of roles');?></a></li>
</ul>

<br />
<div class="header-list"><h2><?=erTranslationClassLhTranslation::getInstance()->getTranslation('system/configuration','System configuration values');?></h1></div>
<ul class="circle">
    <li><a href="<?=erLhcoreClassDesign::baseurl('systemconfig/list')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('system/configuration','System configuration values');?></a></li>    
</ul>
<br />
<div class="header-list"><h2><?=erTranslationClassLhTranslation::getInstance()->getTranslation('system/configuration','Additional actions');?></h1></div>
<ul class="circle">
   <li><a href="<?=erLhcoreClassDesign::baseurl('system/expirecache')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Clean all cache');?></a></li>   
   <li><a href="<?=erLhcoreClassDesign::baseurl('system/cachestatus')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Cache status');?></a></li>   
</ul>

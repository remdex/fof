<div class="header-list"><h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('system/configuration','System configuration');?></h1></div>
<ul>
    <li><a href="<?=erLhcoreClassDesign::baseurl('user/userlist')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('system/configuration','Users');?></a></li>
    <li><a href="<?=erLhcoreClassDesign::baseurl('user/grouplist')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('system/configuration','List of groups');?></a></li>
    <li><a href="<?=erLhcoreClassDesign::baseurl('permission/roles')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('system/configuration','List of roles');?></a></li>
</ul>

<br />
<div class="header-list"><h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('system/configuration','System configuration values');?></h1></div>
<ul>
    <li><a href="<?=erLhcoreClassDesign::baseurl('systemconfig/list')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('system/configuration','System configuration values');?></a></li>    
</ul>
<br />
<div class="header-list"><h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('system/configuration','Additional actions');?></h1></div>
<ul>
   <li><a href="<?=erLhcoreClassDesign::baseurl('system/expirecache')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Clean all cache');?></a></li>   
   <li><a href="<?=erLhcoreClassDesign::baseurl('system/cachestatus')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Cache status');?></a></li>   
</ul>

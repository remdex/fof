<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('statistic/index','Google analytics statistic');?></h1> 
</div>
<ul>
	<li><a href="<?=$auth_url_analytics?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('statistic/index','Grant access application to access google analytics API');?></a></li>
	<?php if (erLhcoreClassModelSystemConfig::fetch('google_analytics_token')->current_value != '') : ?>
	<li><a href="<?=erLhcoreClassDesign::baseurl('statistic/choosesite')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('statistic/index','Site choose');?></a></li>
	<?php endif;?>
	<?php if (erLhcoreClassModelSystemConfig::fetch('google_analytics_token')->current_value != '' && erLhcoreClassModelSystemConfig::fetch('google_analytics_site_profile_id')->current_value != '') : ?>
	<li><a href="<?=erLhcoreClassDesign::baseurl('statistic/view')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('statistic/index','View statistic');?></a></li>
	<?php endif;?>
</ul>

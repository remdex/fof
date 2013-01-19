<fieldset><legend><?=erTranslationClassLhTranslation::getInstance()->getTranslation('systemconfig/edit','Edit')?></legend>

<? if (isset($errArr)) : ?>
<div class="error-list">
<ul>
<?php foreach ($errArr as $err) : ?>
    <li><?=$err?></li>
<?php endforeach;?>
</ul>
</div>
<br />
<? endif;?>

<? if (isset($data_updated)) : ?>
	<div class="dataupdate"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('systemconfig/edit','Data updated');?></div>
<? endif; ?>


<form method="post" action="<?=erLhcoreClassDesign::baseurl('systemconfig/edit')?>/<?=$systemconfig->identifier?>">


<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('systemconfig/edit','Identifier');?></label>
<input class="default-input" type="text" disabled="disabled" value="<?=htmlspecialchars($systemconfig->identifier);?>" />
</div>

<?php if ( $systemconfig->type == erLhcoreClassModelSystemConfig::SITE_ACCESS_PARAM_ON ) : ?>


<?php foreach (erConfigClassLhConfig::getInstance()->getSetting('site','available_site_access') as $siteaccess) : 
$siteaccessOptions = erConfigClassLhConfig::getInstance()->getSetting('site_access_options',$siteaccess);	
?>
<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('systemconfig/edit','Values applies to');?> - <?=htmlspecialchars($siteaccess);?></label>
<input class="default-input" name="Value<?=$siteaccess?>" type="text" value="<?=isset($systemconfig->data[$siteaccess]) ? htmlspecialchars($systemconfig->data[$siteaccess]) : ''?>" />
</div>
<?php endforeach;?>
	

<?php else : ?>
<input class="default-input" type="text" name="ValueParam" value="<?=htmlspecialchars($systemconfig->value);?>" />
<?php endif;?>

<input type="submit" class="default-button" name="UpdateConfig" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('systemconfig/edit','Update')?>"/>

</form>

</fieldset>
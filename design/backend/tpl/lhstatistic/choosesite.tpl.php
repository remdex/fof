<fieldset><legend><?=erTranslationClassLhTranslation::getInstance()->getTranslation('statistic/choosesite','Choose site you want to show statistic');?></legend> 

<form action="<?=erLhcoreClassDesign::baseurl('statistic/choosesite')?>" method="post">
<ul>
<?
//$properties = array( 
//    'webPropertyId', 'accountName', 'accountId', 
//    'profileId', 'currency', 'timezone', 
//    'title', 'tableId', 'id', 'updated', 'link' 
//); 
foreach($accounts as $account) { ?>
    <li><label><input type="radio" name="ProfileID"  value="<?=$account->profileId?>" <?=erLhcoreClassModelSystemConfig::fetch('google_analytics_site_profile_id')->current_value == $account->profileId ? 'checked="checked"' : ''?> /><?=$account->title?>, Account - <?=$account->accountName?></label></li>
<? }?>

<input type="submit" name="ChooseProfileID" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('statistic/choosesite','Save');?>" class="default-button" />
</form>

</fieldset>

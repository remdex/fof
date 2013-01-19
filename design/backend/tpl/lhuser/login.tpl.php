<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/login','Please login');?></h1>
</div>
<? if (isset($error)) : ?><h2 class="error-h2"><?=$error;?></h2><? endif;?>
<br />

<div class="left">

<form method="post" action="<?=erLhcoreClassDesign::baseurl('user/login')?>">
<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/login','Username');?></label>
<input class="inputfield" type="text" name="email" value="" />
</div>

<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/login','Password');?></label>
<input class="inputfield" type="password" name="pass" value="" />
</div>

<input type="hidden" value="<?php echo htmlspecialchars($d)?>" name="DestinationURL" />

<input class="small button" type="submit" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/login','Login');?>" name="Login" />&nbsp;&nbsp;&nbsp;<a href="<?=erLhcoreClassDesign::baseurl('user/forgotpassword')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/login','Password remind')?></a>
</form>
</div>

<?php //include_once(erLhcoreClassDesign::designtpl('lhuser/open_id_block.tpl.php'));?>
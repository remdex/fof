<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/forgotpassword','Password remind');?></h1>
</div>
<div class="attribute-short">
<div id="messages">
	<? if (isset($error)) : ?><h2 class="error-h2"><?=$error;?></h2><? endif;?>
</div>
<br />

<form method="post" action="<?=erLhcoreClassDesign::baseurl('user/forgotpassword')?>">
<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/forgotpassword','E-mail')?>:</label>
<input type="text" class="inputfield" name="Email" value="" />
</div>


<input type="submit" class="default-button" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/forgotpassword','Restore password')?>" name="Forgotpassword" />


</form>
</div>
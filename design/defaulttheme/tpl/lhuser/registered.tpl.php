<div class="header-list">
<h2><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/login','Registration is complete');?></h2>
</div>

<div class="attribute-short">
    <p><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/login','Registration is complete please login.')?></p>
</div>


<form method="post" action="<?=erLhcoreClassDesign::baseurl('user/login')?>">
<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/login','Username');?></label>
<input class="inputfield" type="text" name="Username" value="" />
</div>

<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/login','Password');?></label>
<input class="inputfield" type="password" name="Password" value="" />
</div>

<input class="default-button" type="submit" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/login','Login');?>" name="Login" />&nbsp;&nbsp;&nbsp;<a href="<?=erLhcoreClassDesign::baseurl('user/forgotpassword')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/login','Password remind')?></a>
</form>
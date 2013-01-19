<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/mapaccounts','Open ID');?></h1>
</div>

<form method="post" action="<?=erLhcoreClassDesign::baseurl('user/mapaccounts')?>">

<div class="in-blk float-break">


<?php if (isset($map_to_current)) : ?>

<div class="left map-option">
<h2><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/mapaccounts','Map to my account');?></h2>
<label><input type="radio" value="2" name="CreateAccount" checked /> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/mapaccounts','my account');?> (<?=htmlspecialchars($current_user->username)?>)</label>
</div>

<?php else : ?>

<div class="left map-option">

    <h2><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/mapaccounts','Login');?></h2>
        
    <label><input type="radio" value="3" name="CreateAccount" <?=$create_account == 3 ? 'checked="checked"' : ''?>/> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/mapaccounts','map to my account');?></label>
     
    <div class="map-login"<?php if (isset($map_to_current) && $create_account != 3) :?>style="display:none"<?php endif; ?>>
        <? if (isset($failed_authenticate)) : ?><h2 class="error"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/mapaccounts','Incorrect login or password');?></h2><? endif;?>
        
        <div class="in-blk">
        <label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/mapaccounts','Username');?></label>
        <input class="inputfield" type="text" name="Username" value="" />
        </div>
        
        <div class="in-blk">
        <label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/mapaccounts','Password');?></label>
        <input class="inputfield" type="password" name="Password" value="" />
        </div>
    </div>

</div>
<?php endif;?>


<div class="left map-option">
<?php if (isset($multiple_action)) : ?>
<strong><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/mapaccounts','OR');?></strong>
<label><input type="radio" value="1" name="CreateAccount" /> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/mapaccounts','create account');?></label>
<?php endif;?>
</div>


</div>

<div class="float-break">
<input class="default-button" type="submit" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/mapaccounts','Next');?>" name="MapAccounts" />
</div>

</form>

<script>
 var _lactq = _lactq || [];
_lactq.push({'f':'hw_init_map_account','a':[]});
</script>
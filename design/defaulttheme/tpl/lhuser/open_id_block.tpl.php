<div class="open-id-block">
<h2><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','OR login with');?></h2>

<div id="googe-login-block" style="width:200px;">
<a onclick="hw.loginOpenID()" style="cursor:pointer;" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','Login with google account');?>" ><img src="<?=erLhcoreClassDesign::design('images/icons/open_id_1.png')?>" alt="" title="" /></a>
</div>

<div id="loading-block" style="display:none;width:200px;">
<img src="<?=erLhcoreClassDesign::design('images/newdesign/ajax-loader.gif')?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','Working');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','Working');?>" /> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','Working...');?>
</div>

<?php if (erConfigClassLhConfig::getInstance()->getSetting( 'facebook', 'enabled' ) == true) : ?>
    <?php include_once(erLhcoreClassDesign::designtpl('lhuser/facebook_login.tpl.php'));?>
<?php endif;?>

</div>
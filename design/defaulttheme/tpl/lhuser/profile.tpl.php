<div class="header-list">
<?php if ($profile->name != '' || $profile->surname != '') : ?>
<h1><?=htmlspecialchars($profile->name)?> <?=htmlspecialchars($profile->surname)?></h1>
<?php else : ?>
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/profile','User profile')?></h1>
<?php endif;?>
</div>

<?php if ($profile->has_photo) : ?>
<div class="pr20 pb10 left">
<div class="img">
<img class="main" src="<?=$profile->photo_normal?>" alt="<?=htmlspecialchars($profile->name)?> <?=htmlspecialchars($profile->surname)?>" title="<?=htmlspecialchars($profile->name)?> <?=htmlspecialchars($profile->surname)?>" />
</div>
</div>
<?php endif;?>

<div class="attr-user">
<ul>
<?php if ($profile->name != '' && $profile->surname != '') : ?>
    <li><strong><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/profile','Name and surname')?>:</strong> <?=htmlspecialchars($profile->name)?> <?=htmlspecialchars($profile->surname)?></li>
<?php endif;?>

<?php if ($profile->website != '') : ?>
    <li><strong><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/profile','Website')?>:</strong> <?=erLhcoreClassBBCode::make_clickable(htmlspecialchars($profile->website))?> </li>
<?php endif;?>
</ul>
</div>

<?php if ($profile->intro != '') : ?>
<div class="header-list" style="clear:both;"><h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/profile','Introduction')?></h1></div>
<p><?=erLhcoreClassBBCode::make_clickable(htmlspecialchars($profile->intro))?></p>
<?php endif;?>

<?php $items=erLhcoreClassModelGalleryAlbum::getAlbumsByCategory(array('sort' => 'addtime DESC','filter' => array('owner_id' => $profile->user_id),'offset' => 0, 'limit' => 4)); ?>  
<?php if (!empty($items)) : ?>
<div class="header-list"><h1><a href="<?=erLhcoreClassDesign::baseurl('gallery/ownercategorys')?>/<?=$profile->user_id?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/profile','Last updated albums')?></a></h1></div>
<?php include(erLhcoreClassDesign::designtpl('lhgallery/album_list.tpl.php')); ?>
<?php endif;?>

<?php $items = erLhcoreClassModelGalleryImage::getImages(array('smart_select' => true,'filter' => array('approved' => 1,'owner_id' => $profile->user_id), 'sort' => 'pid DESC','offset' => 0, 'limit' => 5));
$appendImageMode = '';
if (!empty($items)) : ?>
<div class="category">
<div class="header-list"><h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/profile','Last uploaded images');?></h1></div>
<?php include(erLhcoreClassDesign::designtpl('lhgallery/image_list.tpl.php'));?> 
</div>
<?php endif;?>

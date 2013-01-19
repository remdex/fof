<div class="open-id-item">

    <h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','Twitter')?></h3>
    
    <?php $list = erLhcoreClassModelUserOauth::getList(array('filter' => array('user_id' => $user->id))); ?>
    
    <?php if (count($list) > 0) : ?>
        <ul>
        <?php foreach ($list as $open_id) : ?>
            <li><a href="<?=erLhcoreClassDesign::baseurl('user/removetwitterlogin')?>/<?=$open_id->id?>">Delete - <?=htmlspecialchars($open_id->user_name)?></a></li>
        <?php endforeach; ?>
        </ul>
    <?php else :?>
    	<?php include_once(erLhcoreClassDesign::designtpl('lhuser/twitter_login.tpl.php'));?>
    	<br>
    	<br>
    	<br>
    <?php endif;?>

</div>    
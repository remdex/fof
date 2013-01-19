<div class="open-id-item">

    <h3>Google</h3>
    <?php $list = erLhcoreClassModelOidMap::getList(array('filter' => array('user_id' => $user->id,'open_id_type' => erLhcoreClassModelOidMap::OPEN_ID_GOOGLE)));
    if (count($list) > 0) : ?>
        <ul>
        <?php foreach ($list as $open_id) : ?>
            <li>
            <a href="<?=erLhcoreClassDesign::baseurl('user/removeopenid')?>/<?=$open_id->id?>">Delete - <span title="<?=htmlspecialchars($open_id->open_id);?>">(<?=$open_id->email?>)</span></a>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php else :?>
      
    <a href="#" class="button google" onclick="return hw.loginOpenID()"><span aria-hidden="true" data-icon="&#x45;"></span> Sign in with Google</a>
    <div id="googe-login-block" class="hide open-id-block"></div>
      
    <?php endif;?>

</div>    
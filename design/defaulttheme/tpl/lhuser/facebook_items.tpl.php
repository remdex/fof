<div class="open-id-item float-break">
    <h3>Facebook</h3>
    <?php $list = erLhcoreClassModelUserFB::getList(array('filter' => array('user_id' => $user->id)));
    if (count($list) > 0) : ?>
        <ul>
        <?php foreach ($list as $open_id) : ?>
            <li>
             <a href="<?=erLhcoreClassDesign::baseurl('user/removefblogin')?>/<?=$open_id->user_id?>">Delete - <?=htmlspecialchars($open_id->name)?></a>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php else :?>
    <div>
        <?php include_once(erLhcoreClassDesign::designtpl('lhuser/facebook_login.tpl.php'));?>
        <br>                
        <br>                
    </div>
    <?php endif;?>
</div>    
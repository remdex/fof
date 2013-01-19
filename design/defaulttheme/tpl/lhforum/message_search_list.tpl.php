
<table cellpadding="0" cellspacing="0" width="100%">
<tr class="header-row">
    <th class="hitem"><h2>Topic</h2></th>
    <th width="1%">Messages</th>
    <th width="1%">Author</th>
</tr> 
<?php 
foreach ($items as $item) : ?>
<tr class="topic-item<?=$item->topic->message_count > (int)erLhcoreClassModelSystemConfig::fetch('minimum_post_to_hot')->current_value ? ' hot-topic' : ''?><?=$item->topic->topic_status == 1 ? ' locked-topic' : ''?>">
    <td><h3><a href="<?=$item->topic->url_path?>"><strong><?=htmlspecialchars($item->topic)?></strong> &raquo; <?=$item->content_plain_search?></a></h3></td>
    <td class="ptd"><?=$item->topic->message_count?></td>
    <td class="ptd">
    <span class="topic-count right">
            <?php if ($item->user !== false) : ?>
            <a href="<?=$item->topic->url_path?>"><?=$item->topic->user->username;?></a>
            <?php else :?>
            -
            <?php endif;?><br />
            <span class="ctime">
            <?=$item->topic->ctrim_frontend?>
            </span>
            </span>
    </td>
</tr>
<?php endforeach;?>
</table>

<div class="forum-paginator">
<?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>
</div>
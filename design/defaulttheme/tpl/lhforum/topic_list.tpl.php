<table cellpadding="0" cellspacing="0" width="100%">
<tr class="header-row">
<th class="hitem"><h2>Topic</h2></th>
<th width="1%">Messages</th>
<th width="1%">Author</th>
<th width="1%">Last</th>
</tr>
<?php foreach ($topics as $topicItem) : ?>
    <tr class="topic-item<?=$topicItem->message_count > (int)erLhcoreClassModelSystemConfig::fetch('minimum_post_to_hot')->current_value ? ' hot-topic' : ''?><?=$topicItem->topic_status == 1 ? ' locked-topic' : ''?>">
        <td><h3><a href="<?=$topicItem->url_path?>"><?=htmlspecialchars($topicItem->topic_name)?></a></h3></td>
        <td class="ptd">
           <span class="msg-count" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/subcategory_list','images')?>">
        <?=$topicItem->message_count;?>
        </span> 
        </td>
        <td class="ptd">
         <span class="topic-count right">
            <?php if ($topicItem->user !== false) : ?>
            <a href="<?=$topicItem->url_path?>"><?=$topicItem->user->username;?></a>
            <?php else :?>
            -
            <?php endif;?><br />
            <span class="ctime">
            <?=$topicItem->ctrim_frontend?>
            </span>
            </span>
        </td>
        <td class="ptd">
        <span class="last-msg">
        <?php if ($topicItem->last_message_username !== false) : ?>
        <?=$topicItem->last_message_username?>
        <?php else :?>
        -
        <?php endif;?>        
            <span class="ctime"><br />
            <?php if ($topicItem->last_message_date !== false) : ?>
            <?=$topicItem->last_message_date?>
            <?php else :?>
            -
            <?php endif;?>
            </span>        
        </span> 
        </td>
    </tr>
    <?php endforeach;?>
</table>

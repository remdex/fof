<table cellpadding="0" cellspacing="0" class="lentele" width="100%">
<tr class="header-row">
<th class="hitem">Topic</th>
<th width="1%" nowrap>Messages</th>
<th width="1%" nowrap>Author</th>
<th width="1%" nowrap>Last</th>
<th width="1%" nowrap>&nbsp;</th>
<th width="1%" nowrap>&nbsp;</th>
</tr>
<?php foreach ($topics as $topicItem) : ?>
    <tr class="topic-item">
        <td><h3><a href="<?=erLhcoreClassDesign::baseurl('forum/admintopic')?>/<?=$topicItem->id?>"><?=htmlspecialchars($topicItem->topic_name)?></a></h3></td>
        <td class="ptd">
           <span class="msg-count" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/subcategory_list','images')?>">
        <?=$topicItem->message_count;?>
        </span> 
        </td>
        <td class="ptd" nowrap>
         <span class="topic-count right">
            <?php if ($topicItem->user !== false) : ?>
            <a href="<?=erLhcoreClassDesign::baseurl('forum/admintopic')?>/<?=$topicItem->id?>"><?=$topicItem->user->username;?></a>
            <?php else :?>
            -
            <?php endif;?><br />
            <span class="ctime">
            <?=$topicItem->ctrim_frontend?>
            </span>
            </span>
        </td>
        <td class="ptd" nowrap>
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
        <td>
        <a href="<?=erLhcoreClassDesign::baseurl('forum/edittopic')?>/<?=$topicItem->id?>"><img src="<?=erLhcoreClassDesign::design('images/icons/page_edit.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist','Edit topic');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist','Edit topic');?>" /></a>
        </td>
        <td>
        <a onclick="return confirm('<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','Are you sure?');?>')" href="<?=erLhcoreClassDesign::baseurl('forum/deletetopic')?>/<?=$topicItem->id?>"><img src="<?=erLhcoreClassDesign::design('images/icons/delete.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist','Delete topic');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist','Delete topic');?>" /></a>
        </td>
    </tr>
    <?php endforeach;?>
</table>

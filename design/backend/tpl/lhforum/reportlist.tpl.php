<div class="header-list"><h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist','Report list');?></h1></div>

<table class="lentele" cellpadding="0" cellspacing="0" width="100%">
<tr>
    <th>ID</th>
    <th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist','Report');?></th>
    <th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist','Topic');?></th>
    <th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist','Topic message');?></th>
    <th width="1%" nowrap><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist','Date');?></th>
    <th width="1%">&nbsp;</th>
</tr>
<? foreach (erLhcoreClassModelForumReport::getList(array('offset' => $pages->low, 'limit' => $pages->items_per_page)) as $user) : ?>
    <tr>
        <td valign="top"><?=$user->id?></td>
        <td valign="top"><?=$user->message?></td>
        <td valign="top"><a target="_blank" href="<?=erLhcoreClassDesign::baseurldirect('forum/topic')?>/<?=$user->forum_message->topic->id?>"><?=htmlspecialchars($user->forum_message->topic)?></a></td>
        <td valign="top"><?=$user->forum_message?></td>        
        <td nowrap valign="top"><?=$user->ctime_front?></td>
        <td nowrap valign="top">        
        <a onclick="return confirm('<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','Are you sure?');?>')" href="<?=erLhcoreClassDesign::baseurl('forum/reportlist')?>/(action)/deleter/(id)/<?=$user->id?>">Delete report</a><br />
        <a onclick="return confirm('<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','Are you sure?');?>')" href="<?=erLhcoreClassDesign::baseurl('forum/reportlist')?>/(action)/deletemr/(id)/<?=$user->id?>">Delete message and report</a><br />
        <a onclick="return confirm('<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','Are you sure?');?>')" href="<?=erLhcoreClassDesign::baseurl('forum/reportlist')?>/(action)/deletemt/(id)/<?=$user->id?>">Delete topic and report</a><br />
        </td>
    </tr>
<? endforeach; ?>
</table>
<?php if (isset($pages)) : ?>
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>
<? endif;?>

<?php 
foreach ($items as $item) : ?>
<div class="forum-message float-break">

    <div class="author-info">
    <?php if ($item->user !== '') : ?>
    
    <span class="nick"><a href="<?=erLhcoreClassDesign::baseurl('user/manageprofile')?>/<?=$item->user->id?>"><?=$item->user->username?></a></span>
        
    <span class="msgcnt"><?=$item->user_message_count?></span>
    
    <?php endif;?>
    </div>
    
    <div class="msg-body">
        <div class="right"><?=$item->ip?> | <a title="Permalink" class="id-msg-lnk" href="#<?=$item->id?>">#<?=$item->id?></a></div>
        <div class="ctime msg-ctime"><?=$item->ctime_front_full?></div>    
        <?=$item->content_html?>
    </div>
    
    <div class="reply-block"> 
        <a onclick="return confirm('<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','Are you sure?');?>')" class="delete-link" href="<?=erLhcoreClassDesign::baseurl('forum/deletemessage')?>/<?=$item->id?>">Delete</a>        
    </div>

</div>
<?php endforeach;?>
<div class="forum-paginator">
<?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>
</div>
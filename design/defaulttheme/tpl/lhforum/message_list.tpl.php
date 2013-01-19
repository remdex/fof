<?php 
$canReply = (erLhcoreClassUser::instance()->isLogged() && erLhcoreClassUser::instance()->hasAccessTo('lhforum','use')  );

foreach ($items as $item) : ?>
<div class="forum-message float-break">

    <div class="author-info">
    <?php if ($item->user !== '') : ?>
    
    <span class="nick"><?=$item->user->username?></span>
        
    <span class="msgcnt"><?=$item->user_message_count?></span>
    
    <?php endif;?>
    </div>
    
    <div class="ctime msg-ctime">
        <div class="right"><a title="Permalink" class="id-msg-lnk" href="#<?=$item->id?>">#<?=$item->id?></a></div>
        <?=$item->ctime_front_full?>
    </div> 
        
    <div class="msg-body">
           
        <?=$item->content_html?>
    </div>
    
    <div class="reply-block">
    
        <a class="error-link" href="javascript:void(0)" rel="<?=$item->id?>" onclick="hw.reportMessage($(this))">Report abuse</a>
                
        <?php if ($canReply == true && $item->topic->topic_status != erLhcoreClassModelForumTopic::LOCKED) : ?>
        <a class="reply-link" href="javascript:void(0)" onclick="hw.quoteMessage(<?=$item->id?>)">Reply</a>
        <?php endif;?>        
               
        <?php if ($item->can_edit === true) : ?>
        <a class="edit-link" href="<?=erLhcoreClassDesign::baseurl('forum/editmessage')?>/<?=$item->id?>">Edit</a>
        <?php endif;?>
    </div>

</div>
<?php endforeach;?>
<div class="forum-paginator">
<?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>
</div>
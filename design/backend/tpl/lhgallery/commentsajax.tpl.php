<ul>
<?php foreach ($comments as $comment): ?>
<li id="comment_row_id_<?=$comment->msg_id?>">
    <div class="left">
        <a class="cursor" onclick="return hw.deleteComment(<?=$comment->msg_id?>)" ><img src="<?=erLhcoreClassDesign::design('images/icons/delete.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/grouplist','Delete comment');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Delete comment');?>" /></a>
        <a class="cursor" onclick="return hw.editComment(<?=$comment->msg_id?>)" ><img src="<?=erLhcoreClassDesign::design('images/icons/page_edit.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/grouplist','Edit comment');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Edit comment');?>" /></a>
    </div>
    
    <div id="comment_edit_body_<?=$comment->msg_id?>">
    <span class="author"><?=htmlspecialchars($comment->msg_author);?></span>            
    <div class="right ct"><?=$comment->msg_date;?> | <?=$comment->msg_hdr_ip?></div>                                   
    <p class="msg-body"><?=erLhcoreClassBBCode::make_clickable(htmlspecialchars($comment->msg_body))?>  
    </div>
<?php endforeach;?>
</ul>

<div class="comments-paginator">
<?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator_ajax.tpl.php')); ?>
</div>

<script>
hw.initCommentTranslations();
</script>
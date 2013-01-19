<?php if ($action == 'form') : ?>

<div class="comment-form" style="clear:both">
<div class="progressName"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Message author')?></div>				
<input type="text" id="MessageAuthor_<?=$comment->msg_id?>" value="<?=htmlspecialchars($comment->msg_author)?>" class="inputfield" />

<div class="progressName"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Message')?></div>			
<textarea rows="5" class="default-textarea" id="MessageBody_<?=$comment->msg_id?>"><?=htmlspecialchars($comment->msg_body)?></textarea>	

<br />
<input type="button" onclick="hw.updateComment(<?=$comment->msg_id?>)"class="default-button" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Update');?>" />
</div>

<?php else : ?>

<span class="author"><?=htmlspecialchars($comment->msg_author);?></span>            
<div class="right ct"><?=$comment->msg_date;?></div>
<p class="msg-body"><?=erLhcoreClassBBCode::make_clickable(htmlspecialchars($comment->msg_body))?>
            
<?php endif;?>
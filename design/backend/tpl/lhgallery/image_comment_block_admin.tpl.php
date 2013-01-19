
<div class="picture-comments" id="comment-container">
    <div class="sub-header">
    <h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Picture comments')?></h3>
    </div>
                
    <div id="comments-list">
        <?php 
        $imageCommentVersion = CSCacheAPC::getMem()->getCacheVersion('comments_'.$image->pid);         
        $commentCount = erLhcoreClassModelGalleryComment::getCount(array('cache_key' => 'comments_count_v_'.$image->pid.'_'.$imageCommentVersion,'filter' => array('pid' => $image->pid)));
                
        if ($commentCount > 0) :
        $comments = erLhcoreClassModelGalleryComment::getComments(array('cache_key' => 'comments_v_'.$image->pid.'_'.$imageCommentVersion,'filter' => array('pid' => $image->pid)));
        ?>
        
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
        
        <?php 
        $pages = new lhPaginator();
        $pages->items_total = $commentCount;
        $pages->serverURL = erLhcoreClassDesign::baseurl('gallery/commentsajax').'/'.$image->pid;
        $pages->setItemsPerPage(10);
        $pages->paginate();
        ?>
                
        <div class="comments-paginator">
        <?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator_ajax.tpl.php')); ?>
        </div>
                
        <script>
        hw.initCommentTranslations();
        </script>
        
        <?else:?>
        <p><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','No comments')?></p>
        <?endif;?>
    </div>
    
    
    <div class="comment-form">
        
        <? if (isset($commentErrArr)) : ?>
        <ul class="error-list">
            <?foreach ($commentErrArr as $error) :?>
                <li><?=$error?>
            <?php endforeach;?>
        </ul>
        <?endif;?>
        
        <? if (isset($commentStored)) : ?>
        <ul class="ok">
            <li><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Comment stored')?>
        </ul>
        <?endif;?>
                
        <h4><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Leave a reply')?></h4>
        <div class="in-blk">
        <label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Nick')?>:</label>
        <input type="text" id="IDName" value="" maxlength="25" class="inputfield"/><i> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Max 25 characters')?></i>
        </div>
    
        <div class="in-blk">
        <label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Message')?>:</label>
        <textarea id="IDCommentBody" rows="5" cols="10" class="default-textarea" ></textarea>
        </div>
                       
        <input type="button" class="default-button" id="CommentButtomStore" onclick="hw.addCheck(<?=time()?>,<?=$image->pid?>)" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Send')?>"/>
        
    </div>
</div>
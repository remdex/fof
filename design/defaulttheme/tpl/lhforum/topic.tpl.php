<div class="header-list">
<?php include(erLhcoreClassDesign::designtpl('lhforum/search_box.tpl.php'));?> 
<h1><?=htmlspecialchars($topic->topic_name)?></h1>
</div>

<? if ($pages->items_total > 0) { ?>         
  <?  
      $items = erLhcoreClassModelForumMessage::getList(array('filter' => array('topic_id' => $topic->id),'offset' => $pages->low, 'limit' => $pages->items_per_page));
  ?>      
  <?php include_once(erLhcoreClassDesign::designtpl('lhforum/message_list.tpl.php'));?>           
<? } else { ?>

<p><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album','No records')?>.</p>

<? } ?>
<?php if ( erLhcoreClassUser::instance()->isLogged() && $topic->topic_status != erLhcoreClassModelForumTopic::LOCKED) : ?>
<?php include(erLhcoreClassDesign::designtpl('lhforum/new_message.tpl.php'));?> 
<?php endif;?>
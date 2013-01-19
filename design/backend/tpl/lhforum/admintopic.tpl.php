<div class="header-list">
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
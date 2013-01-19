<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist','List');?> - <?=$object_trans['name']?></h1>
</div>

<?php if ( isset($filter) ) : ?>
 <?php switch ($filter) {
 	case 'filternewspaper': ?>
 	<?php include(erLhcoreClassDesign::designtpl('lhabstract/filter/filternewspaper.tpl.php')); ?> 
 	<?php ;
 	break;
 	
 	default:
 		;
 	break;
 }?>
<?php endif;?>

<?php if (!isset($hide_add)) : ?>
<div class="new-record-control">
<a href="<?=erLhcoreClassDesign::baseurl('abstract/new')?>/<?=$identifier?>"><img src="<?=erLhcoreClassDesign::design('images/icons/add.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist','New');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist','New');?>" /></a>
</div>
<br>
<?php endif;?>

<?php if ($pages->items_total > 0) : ?> 

<table class="lentele" cellpadding="0" cellspacing="0" width="100%">
<tr>
    <?php foreach ($fields as $field) : ?>
    	<?php if (!isset($field['hidden'])) : ?>
        <th nowrap <?=isset($field['width']) ? "width=\"{$field['width']}%\"" : ''?>><?=$field['trans']?></th>  
        <?php endif;?>  
    <?endforeach;?>
    <th width="1%">&nbsp;</th>
    <th width="1%">&nbsp;</th>
</tr>
<? 

if (!isset($items)){
    $paramsFilter = array('offset' => $pages->low, 'limit' => $pages->items_per_page);
    
    if ( isset($sort) && !empty($sort) ) {
        $paramsFilter['sort'] = $sort;
    }
    
    $paramsFilter = array_merge($paramsFilter,$filter_params);
    
    $items = call_user_func('erLhAbstractModel'.$identifier.'::getList',$paramsFilter);
    
}

foreach ($items as $item) : ?>
    <tr>       
        <?php foreach ($fields as $key => $field) : ?>
        
        <?php if (!isset($field['hidden'])) : ?>
        <td>
        <?php       
        if (isset($field['frontend']))
            echo $item->{$field['frontend']};       
        else
            echo $item->$key;
        ?></td>
        <?php endif;?>
        
        <?endforeach;?>        
        <td><a href="<?=erLhcoreClassDesign::baseurl('abstract/edit')?>/<?=$identifier.'/'.$item->id?>"><img src="<?=erLhcoreClassDesign::design('images/icons/page_edit.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist','Edit');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist','Edit');?>" /></a></td>
        <td><a onclick="return confirm('Are you sure?')" class="delitem" href="<?=erLhcoreClassDesign::baseurl('abstract/delete')?>/<?=$identifier.'/'.$item->id?>"><img src="<?=erLhcoreClassDesign::design('images/icons/delete.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist','Delete');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist','Delete');?>" /></a></td>
    </tr>
<? endforeach; ?>
</table>

<br>

<?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?> 

<?php else:?>
Empty...
<?endif;?>

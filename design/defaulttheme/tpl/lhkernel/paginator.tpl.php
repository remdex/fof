<?php if (isset($pages) && $pages->num_pages > 1) : ?>

<div class="right found-total"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('core/paginator','Page')?> <?=$pages->current_page?> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('core/paginator','of')?> <?=$pages->num_pages?>, <?=erTranslationClassLhTranslation::getInstance()->getTranslation('core/paginator','Found')?> - <?=$pages->items_total?></div>

<ul class="pagination">
            
    <?php if ($pages->current_page != 1) : ?>    
        <li class="arrow"><a class="previous" href="<?php echo $pages->serverURL,$pages->prev_page,$pages->querystring?>">&laquo;</a></li>    
    <?php endif;?>
              
    <?php if ($pages->num_pages > 10) : 
    $needNoBolder = false;

    if ($pages->range[0] > 1) :
    $i = 1;
    $pageURL = $i > 1 ? '/(page)/'.$i : '';
    $needNoBolder = true;
    	if ($i == $pages->current_page) : ?>
    	   <li class="current no-b"><a title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('core/paginator','Go to page')?> <?=$i?> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('core/paginator','of')?> <?=$pages->num_pages?>" href="#"><?=$i?></a></li>
    	<?php else : ?>  		
           <li><a class="no-b" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('core/paginator','Go to page')?> <?=$i?> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('core/paginator','of')?> <?=$pages->num_pages?>" href="<?php echo $pages->serverURL,$pageURL,$pages->querystring?>"><?=$i?></a></li>
        <?php endif;        
        ?> <li>...</li> <?php
        endif;

        for($i=$pages->range[0];$i<=$pages->lastArrayNumber;$i++) :
        if ($i > 0) :
        $pageURL = $i > 1 ? '/(page)/'.$i : '';
        $noBolderClass = ($i == 1 || $needNoBolder == true) ? ' no-b' : '';
        $needNoBolder = false;

				if ($i == $pages->current_page): ?>
    
				<li class="current<?=$noBolderClass?>"><a title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('core/paginator','Go to page')?> <?=$i?> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('core/paginator','of')?> <?=$pages->num_pages?>"  href="#"><?=$i?></a></li>
				
			    <?php else : ?>
    
			    <li><a class="<?=$noBolderClass?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('core/paginator','Go to page')?> <?=$i?> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('core/paginator','of')?> <?=$pages->num_pages?>" href="<?php echo $pages->serverURL,$pageURL,$pages->querystring?>"><?=$i?></a></li>
						
    <?php endif;endif;endfor;
    if ($pages->lastArrayNumber < $pages->num_pages) :
    $i = $pages->num_pages;
    $pageURL = $i > 1 ? '/(page)/'.$i : '';

    ?> <li>...</li> <?php if ($i == $pages->current_page) : ?>
          	          						
			<li class="current"><a title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('core/paginator','Go to page')?> <?=$i?> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('core/paginator','of')?> <?=$pages->num_pages?>" href="#"><?=$i?></a></li>
					    
    <?php  else : ?>
    
            <li><a class="no-b" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('core/paginator','Go to page')?> <?=$i?> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('core/paginator','of')?> <?=$pages->num_pages?>" href="<?php echo $pages->serverURL,$pageURL,$pages->querystring?>"><?=$i?></a></li>
                        
   <?php endif; endif;

   else : for ($i=1;$i<=$pages->num_pages;$i++) :
   $noBolderClass = ($i == 1) ? ' no-b' : '';
   $pageURL = $i > 1 ? '/(page)/'.$i : '';
		if ($i == $pages->current_page) :?>
            <li class="current<?=$noBolderClass?>"><a href="#"><?=$i?></a></li>
		<?php else : ?>
		    <li><a class="paginate" href="<?php echo $pages->serverURL,$pageURL,$pages->querystring;?>"><?=$i?></a></li>							
    <?php endif; endfor; endif;

    if ($pages->current_page != $pages->num_pages): ?>    
    
    <li class="arrow"><a class="next" href="<?php echo $pages->serverURL,'/(page)/',$pages->next_page,$pages->querystring?>">&raquo;</a></li>
    
    <?php endif;?>
        
    </ul>
<?php endif;?>
<?php if (isset($pages) && $pages->num_pages > 1) : ?>
<div class="nav-container">
    <div class="navigator">
    <div class="right found-total">
    <?=erTranslationClassLhTranslation::getInstance()->getTranslation('core/paginator','Page')?> <?=$pages->current_page?> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('core/paginator','of')?> <?=$pages->num_pages?>, <?=erTranslationClassLhTranslation::getInstance()->getTranslation('core/paginator','Found')?> - <?=$pages->items_total?></div>
        
    <?php if ($pages->current_page != 1) : ?>    
        <a class="previous" href="<?php echo $pages->serverURL,$pages->prev_page,$pages->querystring?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('core/paginator','Previous')?></a>    
    <?php endif;?>
              
    <?php if ($pages->num_pages > 10) : 
    $needNoBolder = false;

    if ($pages->range[0] > 1) :
    $i = 1;
    $pageURL = $i > 1 ? '/(page)/'.$i : '';
    $needNoBolder = true;
    	if ($i == $pages->current_page) : ?>
    	   <a title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('core/paginator','Go to page')?> <?=$i?> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('core/paginator','of')?> <?=$pages->num_pages?>" class="current no-b" href="#"><?=$i?></a>
    	<?php else : ?>  		
           <a class="no-b" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('core/paginator','Go to page')?> <?=$i?> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('core/paginator','of')?> <?=$pages->num_pages?>" href="<?php echo $pages->serverURL,$pageURL,$pages->querystring?>"><?=$i?></a>
        <?php endif;        
        ?> ... <?php
        endif;

        for($i=$pages->range[0];$i<=$pages->lastArrayNumber;$i++) :
        if ($i > 0) :
        $pageURL = $i > 1 ? '/(page)/'.$i : '';
        $noBolderClass = ($i == 1 || $needNoBolder == true) ? ' no-b' : '';
        $needNoBolder = false;

				if ($i == $pages->current_page): ?>
    
				<a title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('core/paginator','Go to page')?> <?=$i?> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('core/paginator','of')?> <?=$pages->num_pages?>" class="current<?=$noBolderClass?>" href="#"><?=$i?></a>
				
			    <?php else : ?>
    
			    <a class="<?=$noBolderClass?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('core/paginator','Go to page')?> <?=$i?> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('core/paginator','of')?> <?=$pages->num_pages?>" href="<?php echo $pages->serverURL,$pageURL,$pages->querystring?>"><?=$i?></a>
						
    <?php endif;endif;endfor;
    if ($pages->lastArrayNumber < $pages->num_pages) :
    $i = $pages->num_pages;
    $pageURL = $i > 1 ? '/(page)/'.$i : '';

    ?> ... <?php if ($i == $pages->current_page) : ?>
          	          						
			<a title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('core/paginator','Go to page')?> <?=$i?> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('core/paginator','of')?> <?=$pages->num_pages?>" class="current no-b" href="#"><?=$i?></a>
					    
    <?php  else : ?>
    
            <a class="no-b" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('core/paginator','Go to page')?> <?=$i?> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('core/paginator','of')?> <?=$pages->num_pages?>" href="<?php echo $pages->serverURL,$pageURL,$pages->querystring?>"><?=$i?></a>
                        
   <?php endif; endif;

   else : for ($i=1;$i<=$pages->num_pages;$i++) :
   $noBolderClass = ($i == 1) ? ' no-b' : '';
   $pageURL = $i > 1 ? '/(page)/'.$i : '';
		if ($i == $pages->current_page) :?>
            <a class="current<?=$noBolderClass?>" href="#"><?=$i?></a>
		<?php else : ?>
		    <a class="paginate" href="<?php echo $pages->serverURL,$pageURL,$pages->querystring;?>"><?=$i?></a>							
    <?php endif; endfor; endif;

    if ($pages->current_page != $pages->num_pages): ?>    
    
    <a class="next" href="<?php echo $pages->serverURL,'/(page)/',$pages->next_page,$pages->querystring?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('core/paginator','Next')?></a>
    
    <?php endif;?>
        
    </div>
</div>
<?php endif;?>
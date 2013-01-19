<div class="left-infobox">				
		<h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Refine your search')?></h3>
		<?php if (isset($Result['face_album_id'])) : ?>
	    <a class="reset-filter" href="<?=$Result['facet_url']?>">&laquo; Reset album filter &raquo;</a>
	    <?php endif;?>	
		    
		<ul class="facet-albums">		    
		    <?php foreach ($Result['facet_list'] as $album) : ?>	
		    									
		      <li><a <?=isset($Result['face_album_id']) && $album->aid == $Result['face_album_id'] ? 'class="selected"' : ''?> href="<?=$Result['facet_url']?>/(album)/<?=$album->aid?>"><span class="cnt">(<?=$Result['facet_data'][$album->aid]?>)</span><?=$album->title?></a>
		       
		    <?php endforeach;?>
		</ul>									
</div>
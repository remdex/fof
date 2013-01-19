<!DOCTYPE html>

<?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/page_head.tpl.php'));?>


<div id="container" class="no-left-column no-right-column">

	<div id="bodcont" class="float-break">			
		<div id="middcont">
			<div id="mainartcont">
			 <div style="padding:2px">
			<?					
			     echo $Result['content'];		
			?>			
			</div>
			</div>
		</div>		
	</div>
	
	
<div id="footer">
    <div class="right"><abbr title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','It\'s NOT fake!')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Rendered in')?>: <?=number_format(set_time($GLOBALS['star_microtile'], microtime()), 5);?> s.</abbr>, powered by <a href="http://code.google.com/p/hppg/" title="High performance photo gallery">HPPG</a>, Design <a href="http://pauliusc.lt">http://pauliusc.lt</a></div>
</div>


	
</div>


<script type="text/javascript" language="javascript" src="<?=erLhcoreClassDesign::designJS('js/app.js');?>"></script>

<?php if (erConfigClassLhConfig::getInstance()->getSetting( 'site', 'debug_output' ) == true) {
		$debug = ezcDebug::getInstance(); 
		echo $debug->generateOutput(); 
} ?>
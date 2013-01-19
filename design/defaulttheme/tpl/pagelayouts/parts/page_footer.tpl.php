</div>

<div id="footer" class="float-break"></div>




<?php 
if (erConfigClassLhConfig::getInstance()->getSetting( 'site', 'debug_output' ) == true) {
	$debug = ezcDebug::getInstance(); 
	echo $debug->generateOutput(); 
}
?>
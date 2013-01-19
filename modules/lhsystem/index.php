<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhsystem/index.tpl.php');

$Result['content'] = $tpl->fetch();
$Result['path'] = array(array('url' => erLhcoreClassDesign::baseurl('system/index'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('system/index','System administration panel')));

?>
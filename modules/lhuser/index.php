<?php
$tpl = erLhcoreClassTemplate::getInstance( 'lhuser/index.tpl.php' );

$Result['content'] = $tpl->fetch();


$Result['path'] = array(array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/index','My account')));

?>
<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhbbcode/preview.tpl.php');
$tpl->set('content',erLhcoreClassBBCode::make_clickable(htmlspecialchars($_POST['data'])));

echo $tpl->fetch();
exit;
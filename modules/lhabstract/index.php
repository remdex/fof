<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhabstract/index.tpl.php');

$Result['content'] = $tpl->fetch();

$Result['left_menu'] = 'abstract';
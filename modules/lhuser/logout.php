<?php

$lhUser = erLhcoreClassUser::instance();
$lhUser->logout();

erLhcoreClassModule::redirect();
exit;

?>
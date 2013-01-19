<?php

$user_id = (int)$Params['user_parameters']['user_id'];

$user = erLhcoreClassUser::instance();
$user->setLoggedUserInstantly($user_id);

header('Location: /');
exit;
?>
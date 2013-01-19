<?php

$departament = erLhcoreClassUser::getSession()->load( 'erLhcoreClassModelUser', $Params['user_parameters']['user_id']);
erLhcoreClassUser::getSession()->delete($departament);

$q = ezcDbInstance::get()->createDeleteQuery();

// User departaments
$q->deleteFrom( 'lh_groupuser' )->where( $q->expr->eq( 'user_id', $Params['user_parameters']['user_id'] ) );
$stmt = $q->prepare();
$stmt->execute();

erLhcoreClassModule::redirect('user/userlist');
exit;

?>
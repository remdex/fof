<?php

try {
    $group = erLhcoreClassModelGroup::fetch((int)$Params['user_parameters']['group_id']);
    $group->removeThis();
} catch (Exception $e){
    
}

erLhcoreClassModule::redirect('user/grouplist');
exit;

?>
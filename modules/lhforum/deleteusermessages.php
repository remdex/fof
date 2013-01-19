<?php

$messages = erLhcoreClassModelForumMessage::getList(array('filter' => array('user_id' => (int)$Params['user_parameters']['user_id'])));
foreach ($messages as $message) {
    $message->removeThis();
}

erLhcoreClassModule::redirect('user/manageprofile'.'/'.(int)$Params['user_parameters']['user_id']);
return ;
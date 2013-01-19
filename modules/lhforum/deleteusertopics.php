<?php

$topics = erLhcoreClassModelForumTopic::getList(array('filter' => array('user_id' => (int)$Params['user_parameters']['user_id'])));
foreach ($topics as $topic) {
    $topic->removeThis();
}

erLhcoreClassModule::redirect('user/manageprofile'.'/'.(int)$Params['user_parameters']['user_id']);
return ;
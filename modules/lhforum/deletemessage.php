<?php


$message = $Params['user_object'] ;
$message->removeThis();

erLhcoreClassModule::redirect('forum/admintopic/'.$message->topic_id);
exit;
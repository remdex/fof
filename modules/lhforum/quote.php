<?php

$msg = erLhcoreClassModelForumMessage::fetch($Params['user_parameters']['msg_id']);
echo json_encode(array('result' => '[quote]'.$msg->content.'[/quote]'."\n"));

exit;
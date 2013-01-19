<?php

$topic = $Params['user_object'] ;
$topic->removeThis();

erLhcoreClassModule::redirect('forum/admincategorys/'.$topic->category);
exit;
<?php

// Simple is it? :)
$categoryParent = $Params['user_object']->parent;
$Params['user_object']->removeThis();

erLhcoreClassModule::redirect('gallery/admincategorys/').$categoryParent;
exit;

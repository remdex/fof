<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhabstract/uploadzip.tpl.php');

if (isset($_FILES["FileZIP"]) && is_uploaded_file($_FILES["FileZIP"]["tmp_name"]) && $_FILES["FileZIP"]["error"] == 0 )
{
    move_uploaded_file($_FILES['FileZIP']['tmp_name'],'var/zip/zip1.csv');
    $tpl->set('file_uploaded',true);
}
    	
$Result['content'] = $tpl->fetch();

$Result['left_menu'] = 'abstract' ;
<?php
             
$tpl = erLhcoreClassTemplate::getInstance( 'lhforum/reportlist.tpl.php');

if ($Params['user_parameters_unordered']['action'] == 'deleter' && $Params['user_parameters_unordered']['id']) {
    try {
        $report = erLhcoreClassModelForumReport::fetch($Params['user_parameters_unordered']['id']);
        $report->removeThis();
    } catch (Exception $e){
        
    }
}

if ($Params['user_parameters_unordered']['action'] == 'deletemr' && $Params['user_parameters_unordered']['id']) {
    try {
        $report = erLhcoreClassModelForumReport::fetch($Params['user_parameters_unordered']['id']);
        $report->forum_message->removeThis();
        $report->removeThis();
    } catch (Exception $e){
        
    }
}

if ($Params['user_parameters_unordered']['action'] == 'deletemt' && $Params['user_parameters_unordered']['id']) {
    try {
        $report = erLhcoreClassModelForumReport::fetch($Params['user_parameters_unordered']['id']);
        $report->forum_message->topic->removeThis();
        $report->removeThis();
    } catch (Exception $e){
        
    }
}

$pages = new lhPaginator();
$pages->items_total = erLhcoreClassModelForumReport::getCount();
$pages->serverURL = erLhcoreClassDesign::baseurl('forum/reportlist'); 
$pages->paginate();

$tpl->set('pages',$pages);
    
$Result['content'] = $tpl->fetch();
$Result['left_menu'] = 'forum';
<div class="header-list"><h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','User prifle');?> - <? echo $user->username?></h1></div>

<ul>
    <li><a href="<?=erLhcoreClassDesign::baseurl('user/edit')?>/<?=$user->id?>">Edit user</a></li>
    <li><a href="<?=erLhcoreClassDesign::baseurl('forum/deleteusermessages')?>/<?=$user->id?>">Delete user messages</a></li>
    <li><a href="<?=erLhcoreClassDesign::baseurl('forum/deleteusertopics')?>/<?=$user->id?>">Delete user topics</a></li>
</ul>

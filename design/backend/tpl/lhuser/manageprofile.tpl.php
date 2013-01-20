<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','User prifle');?> - <? echo $user->username?></h1>

<ul>
    <li><a href="<?=erLhcoreClassDesign::baseurl('user/edit')?>/<?=$user->id?>">Edit user</a></li>
</ul>

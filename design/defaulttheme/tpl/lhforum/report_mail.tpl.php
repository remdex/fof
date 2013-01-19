Abuse post:<br />
<?=nl2br(htmlspecialchars($message_user));?><br />
<a href="http://<?=$_SERVER['HTTP_HOST']?><?=erLhcoreClassDesign::baseurldirect('/site_admin')?>/forum/viewmessage/<?=$message->id?>">View message</a>
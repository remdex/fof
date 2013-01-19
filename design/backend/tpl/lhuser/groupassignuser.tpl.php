<?php if (isset($assigned)) : ?>

<script>
window.parent.$('#assign-user-dialog').dialog('close');
window.parent.document.location = '<?=erLhcoreClassDesign::baseurl('user/editgroup')?>/<?=$group_id?>';
</script>

<?php else : ?>
<form action="<?=erLhcoreClassDesign::baseurl('user/groupassignuser')?>/<?=$group_id?>" method="post">
<table class="lentele" cellpadding="0" cellspacing="0" width="100%">
<tr>
    <th>ID</th>
    <th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/groupassignuser','User');?></th>
</tr>
<?php foreach ($users as $user) : ?>
    <tr>
        <td><input type="checkbox" name="UserID[]" value="<?=$user->id?>"></td>
        <td><?=$user?></td> 
    </tr>
<?php endforeach;?>
</table>
<br />

<?php if (isset($pages)) : ?>
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>
<? endif;?>

<br />
<div>
<input type="submit" class="default-button" name="AssignUsers" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/groupassignuser','Assign');?>" />
</div>
</form>
<?php endif;?>
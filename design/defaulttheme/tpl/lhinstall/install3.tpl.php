<form action="<?=erLhcoreClassDesign::baseurl('install/install/3')?>" method="POST">
<fieldset><legend>Installation step 3</legend> 
<? if (isset($errors)) : ?>
<div class="error-list">
<ul>
    <? foreach ($errors as $error) : ?>
        <li><?=$error?></li>
    <? endforeach; ?> 
</ul>
</div>
<? endif; ?>

        
<fieldset><legend>Initial application settings</legend> 
<table>
    <tr>
        <td>Admin username*</td>
        <td><input type="text" name="AdminUsername" value="<?=isset($admin_username) ? $admin_username : ''?>" /></td>
    </tr>
    <tr>
        <td>Admin password*</td>
        <td><input type="password" name="AdminPassword" value="" /></td>
    </tr>
    <tr>
        <td>Admin password repeat*</td>
        <td><input type="password" name="AdminPassword1" value="" /></td>
    </tr>
    <tr>
        <td>E-mail*</td>
        <td><input type="text" name="AdminEmail" value="<?=isset($admin_email) ? $admin_email : ''?>"></td>
    </tr>
</table>
</fieldset>
<br>

<div class="action-row">
<input type="submit" value="Finish installation" name="Install">
</div>

</fieldset>
</form>
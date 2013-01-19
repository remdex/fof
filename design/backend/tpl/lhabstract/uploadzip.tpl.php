<fieldset><legend><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist','Upload zip');?></legend>

<div class="form-block">
	<div class="form-content">	
	<?php if (isset($file_uploaded)) : ?>
	   <span class="ok">CSV file was uploaded</span>
	<?php endif;?>
		<form method="post" enctype="multipart/form-data" action="<?=erLhcoreClassDesign::baseurl('/abstract/uploadzip')?>">
		<table>
		  <tr>
		      <td>Zip</td>
		      <td><input type="file" name="FileZIP" /> </td>
		  </tr>
		  <tr>
		      <td></td>
		      <td><input type="submit" name="Upload" value="Upload" /></td>
		  </tr>
		</table>
		</form>	
	</div>	
</div>
</fieldset>
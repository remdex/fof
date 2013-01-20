<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('permission/newpolicy','New policy');?> - <?=$role->name?></h1>

<div class="articlebody">

<? if (isset($errors)) : ?>
		<?php include(erLhcoreClassDesign::designtpl('lhkernel/validation_error.tpl.php'));?>
<? endif; ?>

	<div>
		<form action="<?=erLhcoreClassDesign::baseurl('/permission/editrole')?>/<?=$role->id?>" method="post">
						
			<fieldset><legend><?=erTranslationClassLhTranslation::getInstance()->getTranslation('permission/newpolicy','Assigned functions');?></legend> 			
			<table class="lentele" cellpadding="0" cellspacing="0">
			<tr>
			     <td><?=erTranslationClassLhTranslation::getInstance()->getTranslation('permission/newpolicy','Choose module');?></td>
			     <td>			    
			     <select id="ModuleSelectedID" name="Module">			     
			         <option value="*">---<?=erTranslationClassLhTranslation::getInstance()->getTranslation('permission/newpolicy','All modules');?>---</option>
    			     <? foreach (erLhcoreClassModules::getModuleList() as $key => $Module) : ?>
    			         <option value="<?=$key?>"><?=$Module['name'];?></option>
    			     <? endforeach; ?>
			     </select>			     
			     </td>			  
			 </tr>
			 <tr>
			     <td><?=erTranslationClassLhTranslation::getInstance()->getTranslation('permission/newpolicy','Choose module function');?></td>
			     <td id="ModuleFunctionsID">
			        <select name="ModuleFunction" >
			         <option value="*"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('permission/newpolicy','All functions');?></option>
			        </select>
			     </td>
			 </tr>
			</table>			


			<input type="submit" class="small button" name="Store_policy" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('permission/newpolicy','Save');?>"/>
			<input type="submit" class="small button" name="Cancel_policy" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('permission/newpolicy','Cancel');?>"/><br /><br />


			</fieldset>
					
		</form>
	</div>
</div>



<script type="text/javascript">
$( "#ModuleSelectedID" ).change( function () { 
	var module_val = $( "#ModuleSelectedID" ).val();
	if (module_val != '*'){
	    
	    $.getJSON('<?=erLhcoreClassDesign::baseurl('/permission/modulefunctions')?>/'+module_val ,{ }, function(data){ 
	        // If no error
	        if (data.error == 'false')
	        {	 
                $( "#ModuleFunctionsID" ).html(data.result);
	        }		
    	});
	} else {
	    $( "#ModuleFunctionsID" ).html( '<select name="ModuleFunction" ><option value="*"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('permission/newpolicy','All functions');?></option></select>');
	}
});
</script>
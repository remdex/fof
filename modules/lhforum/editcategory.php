<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhforum/editcategory.tpl.php');

$CategoryData = erLhcoreClassModelForumCategory::fetch($Params['user_parameters']['category_id']);

if (isset($_POST['Update_Category']))
{      
    $definition = array(
        'CategoryName' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::REQUIRED, 'unsafe_raw'
        ),        
        'DescriptionCategory' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::REQUIRED, 'unsafe_raw'
        ),       
        'UserID' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::REQUIRED, 'int'
        )
    );
    
    $form = new ezcInputForm( INPUT_POST, $definition );
    $Errors = array();
    
    if ( !$form->hasValidData( 'CategoryName' ) || $form->CategoryName == '' )
    {
        $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('forum/editcategory','Please enter category name!');
    } else {$CategoryData->name = $form->CategoryName;}
    
    
    if ( $form->hasValidData( 'DescriptionCategory' ) && $form->DescriptionCategory != '' )
    {
        $CategoryData->description = $form->DescriptionCategory;
    }
        
    if (count($Errors) == 0)
    {                                
    	$CategoryData->user_id = $form->UserID;    	
    	$CategoryData->saveThis();            

        erLhcoreClassModule::redirect('forum/admincategorys');
        exit;  
         
    }  else {         
        $tpl->set('errArr',$Errors);
    }        
}

$tpl->set('category',$CategoryData);

$Result['content'] = $tpl->fetch();

$pathObjects = array();
$pathCategorys = array();
erLhcoreClassModelForumCategory::calculatePathObjects($pathObjects,$CategoryData->id);        
foreach ($pathObjects as $pathItem)
{
   $path[] = array('url' => erLhcoreClassDesign::baseurl('forum/admincategorys').'/'.$pathItem->id,'title' => $pathItem->name); 
   $pathCategorys[] = $pathItem->cid;
}
 
$path[] = array('url' => erLhcoreClassDesign::baseurl('forum/admincategorys').'/'.$CategoryData->id,'title' => $pathItem->name); 
 
$Result['path'] = $path;
$Result['left_menu'] = 'forum';
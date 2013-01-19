<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhforum/createcategory.tpl.php');

$CategoryData = new erLhcoreClassModelForumCategory();

// Assig logged user by default
$currentUser = erLhcoreClassUser::instance();
$CategoryData->user_id = $currentUser->getUserID(); 
        
if ((int)$Params['user_parameters']['category_id'] > 0) {
    $CategoryDataParent = erLhcoreClassModelForumCategory::fetch($Params['user_parameters']['category_id']);
    $tpl->set('category_parent',$CategoryDataParent); 
}

if (isset($_POST['Update_Category']))
{      
    $definition = array (
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
        $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('forum/createcategory','Please enter category name!');
    } else {$CategoryData->name = $form->CategoryName;}
        
    if ( $form->hasValidData( 'DescriptionCategory' ) && $form->DescriptionCategory != '' )
    {
        $CategoryData->description = $form->DescriptionCategory;
    }
    
    if (count($Errors) == 0)
    {                  
        $CategoryData->user_id = $form->UserID;
        
        if (isset($CategoryDataParent))
        {
            $CategoryData->parent = $CategoryDataParent->id;
        }
        
        $CategoryData->saveThis();
        
        erLhcoreClassModule::redirect('forum/admincategorys/'.$CategoryData->parent);
        exit;  
         
    }  else {         
        $tpl->set('errArr',$Errors);
    }
        
}

$tpl->set('category',$CategoryData);

$Result['content'] = $tpl->fetch();

if (isset($CategoryDataParent)) {
    $pathObjects = array();
    erLhcoreClassModelForumCategory::calculatePathObjects($pathObjects,$CategoryDataParent->id);        
    foreach ($pathObjects as $pathItem)
    {
       $path[] = array('url' => erLhcoreClassDesign::baseurl('forum/admincategorys').'/'.$pathItem->id,'title' => $pathItem->name); 
    } 
} else {
    $path[] =  array('url' => erLhcoreClassDesign::baseurl('forum/admincategorys'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('forum/createcategory','Home')); 
    
}

$Result['path'] = $path;
$Result['left_menu'] = 'forum';
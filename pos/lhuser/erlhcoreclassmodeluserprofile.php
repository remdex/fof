<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_users_profile";
$def->class = "erLhcoreClassModelUserProfile";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

$def->properties['user_id'] = new ezcPersistentObjectProperty();
$def->properties['user_id']->columnName   = 'user_id';
$def->properties['user_id']->propertyName = 'user_id';
$def->properties['user_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['name'] = new ezcPersistentObjectProperty();
$def->properties['name']->columnName   = 'name';
$def->properties['name']->propertyName = 'name';
$def->properties['name']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['surname'] = new ezcPersistentObjectProperty();
$def->properties['surname']->columnName   = 'surname';
$def->properties['surname']->propertyName = 'surname';
$def->properties['surname']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;
 
$def->properties['intro'] = new ezcPersistentObjectProperty();
$def->properties['intro']->columnName   = 'intro';
$def->properties['intro']->propertyName = 'intro';
$def->properties['intro']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['photo'] = new ezcPersistentObjectProperty();
$def->properties['photo']->columnName   = 'photo';
$def->properties['photo']->propertyName = 'photo';
$def->properties['photo']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['variations'] = new ezcPersistentObjectProperty();
$def->properties['variations']->columnName   = 'variations';
$def->properties['variations']->propertyName = 'variations';
$def->properties['variations']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['filepath'] = new ezcPersistentObjectProperty();
$def->properties['filepath']->columnName   = 'filepath';
$def->properties['filepath']->propertyName = 'filepath';
$def->properties['filepath']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['website'] = new ezcPersistentObjectProperty();
$def->properties['website']->columnName   = 'website';
$def->properties['website']->propertyName = 'website';
$def->properties['website']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

return $def; 

?>
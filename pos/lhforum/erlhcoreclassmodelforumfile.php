<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_forum_file";
$def->class = "erLhcoreClassModelForumFile";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

$def->properties['name'] = new ezcPersistentObjectProperty();
$def->properties['name']->columnName   = 'name';
$def->properties['name']->propertyName = 'name';
$def->properties['name']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['file_path'] = new ezcPersistentObjectProperty();
$def->properties['file_path']->columnName   = 'file_path';
$def->properties['file_path']->propertyName = 'file_path';
$def->properties['file_path']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['file_size'] = new ezcPersistentObjectProperty();
$def->properties['file_size']->columnName   = 'file_size';
$def->properties['file_size']->propertyName = 'file_size';
$def->properties['file_size']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['message_id'] = new ezcPersistentObjectProperty();
$def->properties['message_id']->columnName   = 'message_id';
$def->properties['message_id']->propertyName = 'message_id';
$def->properties['message_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

return $def; 

?>
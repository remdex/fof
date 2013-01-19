<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_forum_report";
$def->class = "erLhcoreClassModelForumReport";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

$def->properties['msg_id'] = new ezcPersistentObjectProperty();
$def->properties['msg_id']->columnName   = 'msg_id';
$def->properties['msg_id']->propertyName = 'msg_id';
$def->properties['msg_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['message'] = new ezcPersistentObjectProperty();
$def->properties['message']->columnName   = 'message';
$def->properties['message']->propertyName = 'message';
$def->properties['message']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['ctime'] = new ezcPersistentObjectProperty();
$def->properties['ctime']->columnName   = 'ctime';
$def->properties['ctime']->propertyName = 'ctime';
$def->properties['ctime']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

return $def; 

?>
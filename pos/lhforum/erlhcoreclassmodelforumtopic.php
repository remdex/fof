<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_forum_topic";
$def->class = "erLhcoreClassModelForumTopic";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

$def->properties['topic_name'] = new ezcPersistentObjectProperty();
$def->properties['topic_name']->columnName   = 'topic_name';
$def->properties['topic_name']->propertyName = 'topic_name';
$def->properties['topic_name']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['path_1'] = new ezcPersistentObjectProperty();
$def->properties['path_1']->columnName   = 'path_1';
$def->properties['path_1']->propertyName = 'path_1';
$def->properties['path_1']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['path_2'] = new ezcPersistentObjectProperty();
$def->properties['path_2']->columnName   = 'path_2';
$def->properties['path_2']->propertyName = 'path_2';
$def->properties['path_2']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['path_3'] = new ezcPersistentObjectProperty();
$def->properties['path_3']->columnName   = 'path_3';
$def->properties['path_3']->propertyName = 'path_3';
$def->properties['path_3']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['path_0'] = new ezcPersistentObjectProperty();
$def->properties['path_0']->columnName   = 'path_0';
$def->properties['path_0']->propertyName = 'path_0';
$def->properties['path_0']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['ctime'] = new ezcPersistentObjectProperty();
$def->properties['ctime']->columnName   = 'ctime';
$def->properties['ctime']->propertyName = 'ctime';
$def->properties['ctime']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['user_id'] = new ezcPersistentObjectProperty();
$def->properties['user_id']->columnName   = 'user_id';
$def->properties['user_id']->propertyName = 'user_id';
$def->properties['user_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['message_count'] = new ezcPersistentObjectProperty();
$def->properties['message_count']->columnName   = 'message_count';
$def->properties['message_count']->propertyName = 'message_count';
$def->properties['message_count']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['last_message_ctime'] = new ezcPersistentObjectProperty();
$def->properties['last_message_ctime']->columnName   = 'last_message_ctime';
$def->properties['last_message_ctime']->propertyName = 'last_message_ctime';
$def->properties['last_message_ctime']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;
 
$def->properties['topic_status'] = new ezcPersistentObjectProperty();
$def->properties['topic_status']->columnName   = 'topic_status';
$def->properties['topic_status']->propertyName = 'topic_status';
$def->properties['topic_status']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;
  
$def->properties['main_category_id'] = new ezcPersistentObjectProperty();
$def->properties['main_category_id']->columnName   = 'main_category_id';
$def->properties['main_category_id']->propertyName = 'main_category_id';
$def->properties['main_category_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

return $def; 

?>
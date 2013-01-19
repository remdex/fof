<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_forum_category";
$def->class = "erLhcoreClassModelForumCategory";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

$def->properties['description'] = new ezcPersistentObjectProperty();
$def->properties['description']->columnName   = 'description';
$def->properties['description']->propertyName = 'description';
$def->properties['description']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['name'] = new ezcPersistentObjectProperty();
$def->properties['name']->columnName   = 'name';
$def->properties['name']->propertyName = 'name';
$def->properties['name']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['placement'] = new ezcPersistentObjectProperty();
$def->properties['placement']->columnName   = 'placement';
$def->properties['placement']->propertyName = 'placement';
$def->properties['placement']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['parent'] = new ezcPersistentObjectProperty();
$def->properties['parent']->columnName   = 'parent';
$def->properties['parent']->propertyName = 'parent';
$def->properties['parent']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['user_id'] = new ezcPersistentObjectProperty();
$def->properties['user_id']->columnName   = 'user_id';
$def->properties['user_id']->propertyName = 'user_id';
$def->properties['user_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['topic_count'] = new ezcPersistentObjectProperty();
$def->properties['topic_count']->columnName   = 'topic_count';
$def->properties['topic_count']->propertyName = 'topic_count';
$def->properties['topic_count']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;
 
$def->properties['message_count'] = new ezcPersistentObjectProperty();
$def->properties['message_count']->columnName   = 'message_count';
$def->properties['message_count']->propertyName = 'message_count';
$def->properties['message_count']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 
 
$def->properties['last_topic_id'] = new ezcPersistentObjectProperty();
$def->properties['last_topic_id']->columnName   = 'last_topic_id';
$def->properties['last_topic_id']->propertyName = 'last_topic_id';
$def->properties['last_topic_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

return $def; 

?>
<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_abstract_notice_alert";
$def->class = "erLhAbstractModelNoticeAlert";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

$def->properties['user_id'] = new ezcPersistentObjectProperty();
$def->properties['user_id']->columnName   = 'user_id';
$def->properties['user_id']->propertyName = 'user_id';
$def->properties['user_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['days_limit'] = new ezcPersistentObjectProperty();
$def->properties['days_limit']->columnName   = 'days_limit';
$def->properties['days_limit']->propertyName = 'days_limit';
$def->properties['days_limit']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['last_processed'] = new ezcPersistentObjectProperty();
$def->properties['last_processed']->columnName   = 'last_processed';
$def->properties['last_processed']->propertyName = 'last_processed';
$def->properties['last_processed']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['last_processed_ymd'] = new ezcPersistentObjectProperty();
$def->properties['last_processed_ymd']->columnName   = 'last_processed_ymd';
$def->properties['last_processed_ymd']->propertyName = 'last_processed_ymd';
$def->properties['last_processed_ymd']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['newspaper_group_id'] = new ezcPersistentObjectProperty();
$def->properties['newspaper_group_id']->columnName   = 'newspaper_group_id';
$def->properties['newspaper_group_id']->propertyName = 'newspaper_group_id';
$def->properties['newspaper_group_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['newspaper_id'] = new ezcPersistentObjectProperty();
$def->properties['newspaper_id']->columnName   = 'newspaper_id';
$def->properties['newspaper_id']->propertyName = 'newspaper_id';
$def->properties['newspaper_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['council_id'] = new ezcPersistentObjectProperty();
$def->properties['council_id']->columnName   = 'council_id';
$def->properties['council_id']->propertyName = 'council_id';
$def->properties['council_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['type_alert'] = new ezcPersistentObjectProperty();
$def->properties['type_alert']->columnName   = 'type_alert';
$def->properties['type_alert']->propertyName = 'type_alert';
$def->properties['type_alert']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['email'] = new ezcPersistentObjectProperty();
$def->properties['email']->columnName   = 'email';
$def->properties['email']->propertyName = 'email';
$def->properties['email']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['comments_size'] = new ezcPersistentObjectProperty();
$def->properties['comments_size']->columnName   = 'comments_size';
$def->properties['comments_size']->propertyName = 'comments_size';
$def->properties['comments_size']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['comment_old'] = new ezcPersistentObjectProperty();
$def->properties['comment_old']->columnName   = 'comment_old';
$def->properties['comment_old']->propertyName = 'comment_old';
$def->properties['comment_old']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;


return $def; 

?>
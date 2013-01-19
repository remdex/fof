<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_newspaper_group_user";
$def->class = "erLhcoreClassModelNewspaperGroupUser";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

$def->properties['newspaper_group_id'] = new ezcPersistentObjectProperty();
$def->properties['newspaper_group_id']->columnName   = 'newspaper_group_id';
$def->properties['newspaper_group_id']->propertyName = 'newspaper_group_id';
$def->properties['newspaper_group_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['user_id'] = new ezcPersistentObjectProperty();
$def->properties['user_id']->columnName   = 'user_id';
$def->properties['user_id']->propertyName = 'user_id';
$def->properties['user_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

return $def; 

?>
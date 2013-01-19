<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_article_static";
$def->class = "erLhcoreClassModelArticleStatic";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

$def->properties['name'] = new ezcPersistentObjectProperty();
$def->properties['name']->columnName   = 'name';
$def->properties['name']->propertyName = 'name';
$def->properties['name']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['content'] = new ezcPersistentObjectProperty();
$def->properties['content']->columnName   = 'content';
$def->properties['content']->propertyName = 'content';
$def->properties['content']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

return $def; 

?>
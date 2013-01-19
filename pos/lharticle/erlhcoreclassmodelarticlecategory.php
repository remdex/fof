<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_article_category";
$def->class = "erLhcoreClassModelArticleCategory";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

$def->properties['category_name'] = new ezcPersistentObjectProperty();
$def->properties['category_name']->columnName   = 'category_name';
$def->properties['category_name']->propertyName = 'category_name';
$def->properties['category_name']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['descriptionoveride'] = new ezcPersistentObjectProperty();
$def->properties['descriptionoveride']->columnName   = 'descriptionoveride';
$def->properties['descriptionoveride']->propertyName = 'descriptionoveride';
$def->properties['descriptionoveride']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['intro'] = new ezcPersistentObjectProperty();
$def->properties['intro']->columnName   = 'intro';
$def->properties['intro']->propertyName = 'intro';
$def->properties['intro']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['placement'] = new ezcPersistentObjectProperty();
$def->properties['placement']->columnName   = 'placement';
$def->properties['placement']->propertyName = 'placement';
$def->properties['placement']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['parent'] = new ezcPersistentObjectProperty();
$def->properties['parent']->columnName   = 'parent';
$def->properties['parent']->propertyName = 'parent';
$def->properties['parent']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 


return $def; 

?>
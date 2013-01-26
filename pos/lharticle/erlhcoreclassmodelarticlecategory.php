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

$def->properties['intro'] = new ezcPersistentObjectProperty();
$def->properties['intro']->columnName   = 'intro';
$def->properties['intro']->propertyName = 'intro';
$def->properties['intro']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['parent_id'] = new ezcPersistentObjectProperty();
$def->properties['parent_id']->columnName   = 'parent_id';
$def->properties['parent_id']->propertyName = 'parent_id';
$def->properties['parent_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['pos'] = new ezcPersistentObjectProperty();
$def->properties['pos']->columnName   = 'pos';
$def->properties['pos']->propertyName = 'pos';
$def->properties['pos']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['hide_articles'] = new ezcPersistentObjectProperty();
$def->properties['hide_articles']->columnName   = 'hide_articles';
$def->properties['hide_articles']->propertyName = 'hide_articles';
$def->properties['hide_articles']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 


return $def; 

?>
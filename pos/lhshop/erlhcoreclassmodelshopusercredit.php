<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_shop_user_credit";
$def->class = "erLhcoreClassModelShopUserCredit";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'user_id';
$def->idProperty->propertyName = 'user_id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentManualGenerator' );

$def->properties['credits'] = new ezcPersistentObjectProperty();
$def->properties['credits']->columnName   = 'credits';
$def->properties['credits']->propertyName = 'credits';
$def->properties['credits']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

return $def; 

?>
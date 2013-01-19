<?php
/**
 * @package PayPal
 */

/**
 * Make sure our parent class is defined.
 */
require_once 'lib/core/lhshop/paymenthandlers/paypal_handler/php-sdk/lib/PayPal/Type/XSDSimpleType.php';

/**
 * QuantityType
 *
 * @package PayPal
 */
class QuantityType extends XSDSimpleType
{
    function QuantityType()
    {
        parent::XSDSimpleType();
        $this->_namespace = 'urn:ebay:apis:CoreComponentTypes';
        $this->_attributes = array_merge($this->_attributes,
            array (
              'unit' => 
              array (
                'name' => 'unit',
                'type' => 'xs:token',
                'use' => 'optional',
              ),
            ));
    }

}

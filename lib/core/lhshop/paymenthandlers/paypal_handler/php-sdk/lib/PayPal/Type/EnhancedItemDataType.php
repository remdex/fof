<?php
/**
 * @package PayPal
 */

/**
 * Make sure our parent class is defined.
 */
require_once 'lib/core/lhshop/paymenthandlers/paypal_handler/php-sdk/lib/PayPal/Type/XSDSimpleType.php';

/**
 * EnhancedItemDataType
 *
 * @package PayPal
 */
class EnhancedItemDataType extends XSDSimpleType
{
    function EnhancedItemDataType()
    {
        parent::XSDSimpleType();
        $this->_namespace = 'urn:ebay:apis:EnhancedDataTypes';
    }

}

<?php
/**
 * @package PayPal
 */

/**
 * Make sure our parent class is defined.
 */
require_once 'lib/core/lhshop/paymenthandlers/paypal_handler/php-sdk/lib/PayPal/Type/XSDSimpleType.php';

/**
 * EnhancedPaymentDataType
 *
 * @package PayPal
 */
class EnhancedPaymentDataType extends XSDSimpleType
{
    function EnhancedPaymentDataType()
    {
        parent::XSDSimpleType();
        $this->_namespace = 'urn:ebay:apis:EnhancedDataTypes';
    }

}

<?php
/**
 * @package PayPal
 */

/**
 * Make sure our parent class is defined.
 */
require_once 'lib/core/lhshop/paymenthandlers/paypal_handler/php-sdk/lib/PayPal/Type/DoExpressCheckoutPaymentRequestType.php';

/**
 * DoUATPExpressCheckoutPaymentRequestType
 *
 * @package PayPal
 */
class DoUATPExpressCheckoutPaymentRequestType extends DoExpressCheckoutPaymentRequestType
{
    function DoUATPExpressCheckoutPaymentRequestType()
    {
        parent::DoExpressCheckoutPaymentRequestType();
        $this->_namespace = 'urn:ebay:api:PayPalAPI';
    }

}

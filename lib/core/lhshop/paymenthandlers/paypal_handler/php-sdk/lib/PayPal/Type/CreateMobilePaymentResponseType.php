<?php
/**
 * @package PayPal
 */

/**
 * Make sure our parent class is defined.
 */
require_once 'lib/core/lhshop/paymenthandlers/paypal_handler/php-sdk/lib/PayPal/Type/AbstractResponseType.php';

/**
 * CreateMobilePaymentResponseType
 *
 * @package PayPal
 */
class CreateMobilePaymentResponseType extends AbstractResponseType
{
    function CreateMobilePaymentResponseType()
    {
        parent::AbstractResponseType();
        $this->_namespace = 'urn:ebay:api:PayPalAPI';
    }

}

<?php
/**
 * @package PayPal
 */

/**
 * Make sure our parent class is defined.
 */
require_once 'lib/core/lhshop/paymenthandlers/paypal_handler/php-sdk/lib/PayPal/Type/AbstractResponseType.php';

/**
 * BMManageButtonStatusResponseType
 *
 * @package PayPal
 */
class BMManageButtonStatusResponseType extends AbstractResponseType
{
    function BMManageButtonStatusResponseType()
    {
        parent::AbstractResponseType();
        $this->_namespace = 'urn:ebay:api:PayPalAPI';
    }

}

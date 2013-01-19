<?php
/**
 * @package PayPal
 */

/**
 * Make sure our parent class is defined.
 */
require_once 'lib/core/lhshop/paymenthandlers/paypal_handler/php-sdk/lib/PayPal/Type/AbstractResponseType.php';

/**
 * MassPayResponseType
 *
 * @package PayPal
 */
class MassPayResponseType extends AbstractResponseType
{
    function MassPayResponseType()
    {
        parent::AbstractResponseType();
        $this->_namespace = 'urn:ebay:api:PayPalAPI';
    }

}

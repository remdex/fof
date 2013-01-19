<?php
/**
 * @package PayPal
 */

/**
 * Make sure our parent class is defined.
 */
require_once 'lib/core/lhshop/paymenthandlers/paypal_handler/php-sdk/lib/PayPal/Type/XSDSimpleType.php';

/**
 * AmountType
 *
 * @package PayPal
 */
class AmountType extends XSDSimpleType
{
    function AmountType()
    {
        parent::XSDSimpleType();
        $this->_namespace = 'urn:ebay:apis:CoreComponentTypes';
        $this->_attributes = array_merge($this->_attributes,
            array (
              'currencyID' => 
              array (
                'name' => 'currencyID',
                'type' => 'ebl:CurrencyCodeType',
                'use' => 'required',
              ),
            ));
    }

}

<?php 
class TIG_Buckaroo3Extended_Model_Sources_Availablemethods
{
    public function toOptionArray()
    {
        $array = array(
             array('value' => 'all', 'label' => Mage::helper('buckaroo3extended')->__('all')),
             array('value' => 'amex', 'label' => Mage::helper('buckaroo3extended')->__('American Express')),
             array('value' => 'directdebit', 'label' => Mage::helper('buckaroo3extended')->__('Eenmalige Machtiging')),
             array('value' => 'giropay', 'label' => Mage::helper('buckaroo3extended')->__('Giropay')),
             array('value' => 'ideal', 'label' => Mage::helper('buckaroo3extended')->__('iDeal')),
             array('value' => 'mastercard', 'label' => Mage::helper('buckaroo3extended')->__('Mastercard')),
             array('value' => 'onlinegiro', 'label' => Mage::helper('buckaroo3extended')->__('Online Giro')),
             array('value' => 'paypal', 'label' => Mage::helper('buckaroo3extended')->__('PayPal')),
             array('value' => 'paysafecard', 'label' => Mage::helper('buckaroo3extended')->__('Paysafecard')),
             array('value' => 'sofortueberweisung', 'label' => Mage::helper('buckaroo3extended')->__('Sofort Banking')),
             array('value' => 'transfer', 'label' => Mage::helper('buckaroo3extended')->__('Overboeking')),
             array('value' => 'visa', 'label' => Mage::helper('buckaroo3extended')->__('Visa')),
             array('value' => 'maestro', 'label' => Mage::helper('buckaroo3extended')->__('eMaestro')),
             array('value' => 'visaelectron', 'label' => Mage::helper('buckaroo3extended')->__('Visa Electron')),
             array('value' => 'vpay', 'label' => Mage::helper('buckaroo3extended')->__('V PAY')),
             array('value' => 'bancontactmrcash', 'label' => Mage::helper('buckaroo3extended')->__('Bancontact / Mr. Cash')),
        );
        return $array;
    }
}
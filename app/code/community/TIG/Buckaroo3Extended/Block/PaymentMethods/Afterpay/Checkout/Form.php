<?php
class TIG_Buckaroo3Extended_Block_PaymentMethods_Afterpay_Checkout_Form extends TIG_Buckaroo3Extended_Block_PaymentMethods_Checkout_Form_Abstract
{
    public function __construct()
    {
        $this->setTemplate('buckaroo3extended/afterpay/checkout/form.phtml');
        parent::_construct();
    }

    public function getPaymethod()
    {
        return Mage::getStoreConfig('buckaroo/' . $this->getMethodCode() . '/paymethod', Mage::app()->getStore()->getStoreId());
    }

    public function getBusiness()
    {
        return Mage::getStoreConfig('buckaroo/' . $this->getMethodCode() . '/business', Mage::app()->getStore()->getStoreId());
    }

    public function getCompanyCOCRegistration()
    {
        return $this->getSession()->getData($this->getMethodCode() . '_BPE_CompanyCOCRegistration');
    }

    public function getCompanyName()
    {
        return  $this->getSession()->getData($this->getMethodCode() . '_BPE_CompanyName');
    }

    public function getBusinessSelect()
    {
        return  $this->getSession()->getData($this->getMethodCode() . '_BPE_BusinessSelect');
    }
}
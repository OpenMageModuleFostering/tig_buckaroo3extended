<?php
class TIG_Buckaroo3Extended_Model_PaymentMethods_Afterpay_Observer extends TIG_Buckaroo3Extended_Model_Observer_Abstract
{
    protected $_code = 'buckaroo3extended_afterpay';
    protected $_method = false;
    /** @var  TIG_Buckaroo3Extended_Helper_Data $_helper */
    protected $_helper;

    protected function _construct()
    {
        $this->_method = Mage::getStoreConfig('buckaroo/' . $this->_code . '/paymethod', Mage::app()->getStore()->getStoreId());
        $this->_helper = Mage::helper('buckaroo3extended');
    }

    public function buckaroo3extended_request_addservices(Varien_Event_Observer $observer)
    {
        if($this->_isChosenMethod($observer) === false) {
            return $this;
        }

        $request = $observer->getRequest();

        $vars = $request->getVars();

        if($this->_method == false){
            $this->_method = Mage::getStoreConfig('buckaroo/' . $this->_code . '/paymethod', Mage::app()->getStore()->getStoreId());
        }

        $array = array(
            $this->_method => array(
                'action'   => 'Pay',
                'version'  => '1',
            ),
        );

        if (array_key_exists('services', $vars) && is_array($vars['services'])) {
            $vars['services'] = array_merge($vars['services'], $array);
        } else {
            $vars['services'] = $array;
        }

        $request->setVars($vars);

        return $this;
    }

    public function buckaroo3extended_request_addcustomvars(Varien_Event_Observer $observer)
    {
        if($this->_isChosenMethod($observer) === false) {
            return $this;
        }

        $request            = $observer->getRequest();
        $this->_billingInfo = $request->getBillingInfo();
        $this->_order       = $request->getOrder();

        $vars = $request->getVars();

        if (Mage::getStoreConfig('buckaroo/buckaroo3extended_' . $this->_method . '/use_creditmanagement', Mage::app()->getStore()->getStoreId())) {
            $this->_addCustomerVariables($vars);
            $this->_addCreditManagement($vars);
            $this->_addAdditionalCreditManagementVariables($vars);
        }

        $this->_addAfterpayVariables($vars, $this->_method);

        $request->setVars($vars);
        return $this;
    }

    /**
     * @param Varien_Event_Observer $observer
     * @return $this
     */
    public function buckaroo3extended_request_setmethod(Varien_Event_Observer $observer)
    {
        if($this->_isChosenMethod($observer) === false) {
            return $this;
        }

        $request = $observer->getRequest();

        $codeBits = explode('_', $this->_code);
        $code = end($codeBits);
        $request->setMethod($code);

        return $this;
    }

    /** refund methods */

    /**
     * @param Varien_Event_Observer $observer
     * @return $this
     */
    public function buckaroo3extended_refund_request_setmethod(Varien_Event_Observer $observer)
    {
        if($this->_isChosenMethod($observer) === false) {
            return $this;
        }

        $request = $observer->getRequest();

        $codeBits = explode('_', $this->_code);
        $code = end($codeBits);
        $request->setMethod($code);

        return $this;
    }

    /**
     * @param Varien_Event_Observer $observer
     * @return $this
     */
    public function buckaroo3extended_refund_request_addservices(Varien_Event_Observer $observer)
    {
        if($this->_isChosenMethod($observer) === false) {
            return $this;
        }

        $refundRequest = $observer->getRequest();

        $vars = $refundRequest->getVars();

        $array = array(
            'action'    => 'Refund',
            'version'   => 1,

        );

        if($this->_method == false){
            $this->_method = Mage::getStoreConfig('buckaroo/' . $this->_code . '/paymethod', Mage::app()->getStore()->getStoreId());
        }

        if (array_key_exists('services', $vars) && is_array($vars['services'][$this->_method])) {
            $vars['services'][$this->_method] = array_merge($vars['services'][$this->_method], $array);
        } else {
            $vars['services'][$this->_method] = $array;
        }

        $refundRequest->setVars($vars);

        return $this;
    }

    public function buckaroo3extended_refund_request_addcustomvars(Varien_Event_Observer $observer)
    {
        if($this->_isChosenMethod($observer) === false) {
            return $this;
        }

        return $this;
    }

    /** INTERNAL METHODS **/

    /**
     * Adds variables required for the SOAP XML for paymentguarantee to the variable array
     * Will merge with old array if it exists
     *
     * @param array $vars
     */
    protected function _addAfterpayVariables(&$vars)
    {
        $session            = Mage::getSingleton('checkout/session');
        $additionalFields   = $session->getData('additionalFields');

        $requestArray       = array();

        //add billing address
        $billingAddress     = $this->_order->getBillingAddress();
        $streetFull         = $this->_processAddress($billingAddress->getStreetFull());
        $rawPhoneNumber     = $billingAddress->getTelephone();
        $rawPhoneNumber     = (!empty($rawPhoneNumber))? $rawPhoneNumber : $additionalFields['BPE_PhoneNumber'];
        $billingPhonenumber = $this->_processPhoneNumber($rawPhoneNumber);

        $billingInfo = array(
            'BillingTitle'             => $billingAddress->getFirstname(),
            'BillingGender'            => $additionalFields['BPE_Customergender'],
            'BillingInitials'          => strtoupper(substr($billingAddress->getFirstname(),0,1)),
            'BillingLastName'          => $billingAddress->getLastname(),
            'BillingBirthDate'         => $additionalFields['BPE_customerbirthdate'],
            'BillingStreet'            => $streetFull['street'],
            'BillingHouseNumber'       => $streetFull['house_number'],
            'BillingHouseNumberSuffix' => $streetFull['number_addition'],
            'BillingPostalCode'        => $billingAddress->getPostcode(),
            'BillingCity'              => $billingAddress->getCity(),
            'BillingRegion'            => $billingAddress->getRegion(),
            'BillingCountry'           => $billingAddress->getCountryId(),
            'BillingEmail'             => $billingAddress->getEmail(),
            'BillingPhoneNumber'       => $billingPhonenumber['clean'],
            'BillingLanguage'          => $billingAddress->getCountryId(),
        );
        $requestArray = array_merge($requestArray,$billingInfo);

        //add shipping address (only when different from billing address)
        if($this->isShippingDifferent()){
            $shippingAddress     = $this->_order->getShippingAddress();
            $streetFull          = $this->_processAddress($shippingAddress->getStreetFull());
            $shippingPhonenumber = $this->_processPhoneNumber($shippingAddress->getTelephone());

            $shippingInfo = array(
                'AddressesDiffer'           => 'true',
                'ShippingTitle'             => $shippingAddress->getFirstname(),
                'ShippingGender'            => $additionalFields['BPE_Customergender'],
                'ShippingInitials'          => strtoupper(substr($shippingAddress->getFirstname(),0,1)),
                'ShippingLastName'          => $shippingAddress->getLastname(),
                'ShippingBirthDate'         => $additionalFields['BPE_customerbirthdate'],
                'ShippingStreet'            => $streetFull['street'],
                'ShippingHouseNumber'       => $streetFull['house_number'],
                'ShippingHouseNumberSuffix' => $streetFull['number_addition'],
                'ShippingPostalCode'        => $shippingAddress->getPostcode(),
                'ShippingCity'              => $shippingAddress->getCity(),
                'ShippingRegion'            => $shippingAddress->getRegion(),
                'ShippingCountryCode'       => $shippingAddress->getCountryId(),
                'ShippingEmail'             => $shippingAddress->getEmail(),
                'ShippingPhoneNumber'       => $shippingPhonenumber['clean'],
                'ShippingLanguage'          => $shippingAddress->getCountryId(),
            );
            $requestArray = array_merge($requestArray,$shippingInfo);
        }

        //customer info
        $customerInfo = array(
            'CustomerAccountNumber' => $additionalFields['BPE_AccountNumber'],
            'CustomerIPAddress'     => Mage::helper('core/http')->getRemoteAddr(),
            'Accept'                => $additionalFields['BPE_Accept'],
        );
        $shippingCosts = round($this->_order->getBaseShippingInclTax(), 2);

        $discount = null;

        if(Mage::helper('buckaroo3extended')->isEnterprise()){
            if((double)$this->_order->getGiftCardsAmount() > 0){
                $discount = (double)$this->_order->getGiftCardsAmount();
            }
        }

        if(abs((double)$this->_order->getDiscountAmount()) > 0){
            $discount += abs((double)$this->_order->getDiscountAmount());
        }


        //add order Info
        $orderInfo = array(
            'Discount'      => $discount,
            'ShippingCosts' => $shippingCosts,
        );

        $requestArray = array_merge($requestArray,$customerInfo);
        $requestArray = array_merge($requestArray,$orderInfo);
        //is B2B
        if($additionalFields['BPE_B2B'] == 2){
            $b2bInfo = array(
                'B2B'                    => 'true',
                'CompanyCOCRegistration' => $additionalFields['BPE_CompanyCOCRegistration'],
                'CompanyName'            => $additionalFields['BPE_CompanyName'],
                'CostCentre'             => $additionalFields['BPE_CostCentre'],
                'VatNumber'              => $additionalFields['BPE_VatNumber'],
            );
            $requestArray = array_merge($requestArray,$b2bInfo);
        }
        //add all products max 10
        $products = $this->_order->getAllItems();
        $max      = 99;
        $i        = 1;
        $group    = array();

        foreach($products as $item){
            /** @var $item Mage_Sales_Model_Order_Item */

            if (empty($item) || $item->hasParentItemId()) {
                continue;
            }

            // Changed calculation from unitPrice to orderLinePrice due to impossible to recalculate unitprice,
            // because of differences in outcome between TAX settings: Unit, OrderLine and Total.
            // Quantity will always be 1 and quantity ordered will be in the article description.
            $productPrice = ($item->getBasePrice() * $item->getQtyOrdered())
                          + $item->getBaseTaxAmount()
                          + $item->getBaseHiddenTaxAmount();
            $productPrice = round($productPrice,2);


            $article['ArticleDescription']['value'] = (int) $item->getQtyOrdered() . 'x ' . $item->getName();
            $article['ArticleId']['value']          = $item->getId();
            $article['ArticleQuantity']['value']    = 1;
            $article['ArticleUnitPrice']['value']   = $productPrice;
            $article['ArticleVatcategory']['value'] = $this->_getTaxCategory($this->_getTaxClassId($item));

            $group[$i] = $article;


            if($i <= $max){
                $i++;
                continue;
            }
            break;
        }

        end($group);// move the internal pointer to the end of the array
        $key             = (int)key($group);
        $feeGroupId      = $key+1;
        $paymentFeeArray = $this->_getPaymentFeeLine();
        if(false !== $paymentFeeArray && is_array($paymentFeeArray)){
            $group[$feeGroupId] = $paymentFeeArray;
        }

        $requestArray = array_merge($requestArray, array('Articles' => $group));

        if (array_key_exists('customVars', $vars) && is_array($vars['customVars'][$this->_method])) {
            $vars['customVars'][$this->_method] = array_merge($vars['customVars'][$this->_method], $requestArray);
        } else {
            $vars['customVars'][$this->_method] = $requestArray;
        }
    }

    protected function _getPaymentFeeLine()
    {
        $fee    = (double) $this->_order->getBuckarooFee();
        $feeTax = (double) $this->_order->getBuckarooFeeTax();

        if($fee > 0){
            $article['ArticleDescription']['value'] = 'Servicekosten';
            $article['ArticleId']['value']          = 1;
            $article['ArticleQuantity']['value']    = 1;
            $article['ArticleUnitPrice']['value']   = round($fee+$feeTax,2);
            $article['ArticleVatcategory']['value'] = $this->_getTaxCategory(Mage::getStoreConfig('tax/classes/buckaroo_fee', Mage::app()->getStore()->getId()));
            return $article;
        }
        return false;
    }

    /**
     * @param Mage_Sales_Model_Order_Item $item
     * @return array|bool|string
     */
    protected function _getTaxClassId(Mage_Sales_Model_Order_Item $item)
    {
        return Mage::getResourceModel('catalog/product')->getAttributeRawValue($item->getProductId(), 'tax_class_id', $item->getStoreId());
    }

    protected function _getTaxCategory($taxClassId)
    {
        if (!$taxClassId) {
            return 4;
        }

        $highTaxClasses   = explode(',', Mage::getStoreConfig('buckaroo/' . $this->_code . '/high', Mage::app()->getStore()->getStoreId()));
        $middleTaxClasses = explode(',', Mage::getStoreConfig('buckaroo/' . $this->_code . '/middle', Mage::app()->getStore()->getStoreId()));
        $lowTaxClasses    = explode(',', Mage::getStoreConfig('buckaroo/' . $this->_code . '/low', Mage::app()->getStore()->getStoreId()));
        $zeroTaxClasses   = explode(',', Mage::getStoreConfig('buckaroo/' . $this->_code . '/zero', Mage::app()->getStore()->getStoreId()));
        $noTaxClasses     = explode(',', Mage::getStoreConfig('buckaroo/' . $this->_code . '/no', Mage::app()->getStore()->getStoreId()));

        if (in_array($taxClassId, $highTaxClasses)) {
            return 1;
        }elseif (in_array($taxClassId, $middleTaxClasses)) {
            return 5;
        } elseif (in_array($taxClassId, $lowTaxClasses)) {
            return 2;
        } elseif (in_array($taxClassId, $zeroTaxClasses)) {
            return 3;
        } else {
            return 4;
        }
    }

    protected function _processAddress($fullStreet)
    {
        //get address from billingInfo
        $address = $fullStreet;

        $ret = array();
        $ret['house_number'] = '';
        $ret['number_addition'] = '';
        if (preg_match('#^(.*?)([0-9]+)(.*)#s', $address, $matches)) {
            if ('' == $matches[1]) {
                // Number at beginning
                $ret['house_number'] = trim($matches[2]);
                $ret['street']         = trim($matches[3]);
            } else {
                // Number at end
                $ret['street']            = trim($matches[1]);
                $ret['house_number']    = trim($matches[2]);
                $ret['number_addition'] = trim($matches[3]);
            }
        } else {
            // No number
            $ret['street'] = $address;
        }

        return $ret;
    }

    /**
     * @param $telephoneNumber
     * @return array
     */
    protected function _processPhoneNumber($telephoneNumber)
    {
        $number = $telephoneNumber;

        //the final output must like this: 0031123456789 for mobile: 0031612345678
        //so 13 characters max else number is not valid
        //but for some error correction we try to find if there is some faulty notation

        $return = array("orginal" => $number, "clean" => false, "mobile" => false, "valid" => false);
        //first strip out the non-numeric characters:
        $match = preg_replace('/[^0-9]/Uis', '', $number);
        if ($match) {
            $number = $match;
        }

        if (strlen((string)$number) == 13) {
            //if the length equal to 13 is, then we can check if its a mobile number or normal number
            $return['mobile'] = $this->_isMobileNumber($number);
            //now we can almost say that the number is valid
            $return['valid'] = true;
            $return['clean'] = $number;
        } elseif (strlen((string) $number) > 13) {
            //if the number is bigger then 13, it means that there are probably a zero to much
            $return['mobile'] = $this->_isMobileNumber($number);
            $return['clean'] = $this->_isValidNotation($number);
            if(strlen((string)$return['clean']) == 13) {
                $return['valid'] = true;
            }

        } elseif (strlen((string)$number) == 12 or strlen((string)$number) == 11) {
            //if the number is equal to 11 or 12, it means that they used a + in their number instead of 00
            $return['mobile'] = $this->_isMobileNumber($number);
            $return['clean'] = $this->_isValidNotation($number);
            if(strlen((string)$return['clean']) == 13) {
                $return['valid'] = true;
            }

        } elseif (strlen((string)$number) == 10) {
            //this means that the user has no trailing "0031" and therfore only
            $return['mobile'] = $this->_isMobileNumber($number);
            $return['clean'] = '0031'.substr($number,1);
            if (strlen((string) $return['clean']) == 13) {
                $return['valid'] = true;
            }
        } else {
            //if the length equal to 13 is, then we can check if its a mobile number or normal number
            $return['mobile'] = $this->_isMobileNumber($number);
            //now we can almost say that the number is valid
            $return['valid'] = true;
            $return['clean'] = $number;
        }

        return $return;
    }

    /**
     * Checks if shipping-address is different from billing-address
     *
     * @return bool
     */
    protected function isShippingDifferent()
    {
        // exclude certain keys that are always different
        $excludeKeys = array('entity_id', 'customer_address_id', 'quote_address_id', 'region_id', 'customer_id', 'address_type');

        //get both the order-addresses
        $oBillingAddress = $this->_order->getBillingAddress()->getData();
        $oShippingAddress = $this->_order->getShippingAddress()->getData();

        //remove the keys with corresponding values from both the addressess
        $oBillingAddressFiltered = array_diff_key($oBillingAddress, array_flip($excludeKeys));
        $oShippingAddressFiltered = array_diff_key($oShippingAddress, array_flip($excludeKeys));

        //differentiate the addressess, when some data is different an array with changes will be returned
        $addressDiff = array_diff($oBillingAddressFiltered, $oShippingAddressFiltered);

        //if
        if( !empty($addressDiff) ) { // billing and shipping addresses are different
            return true;
        }
        return false;
    }
}
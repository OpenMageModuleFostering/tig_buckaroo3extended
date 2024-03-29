<?php
/**
 *
 *          ..::..
 *     ..::::::::::::..
 *   ::'''''':''::'''''::
 *   ::..  ..:  :  ....::
 *   ::::  :::  :  :   ::
 *   ::::  :::  :  ''' ::
 *   ::::..:::..::.....::
 *     ''::::::::::::''
 *          ''::''
 *
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Creative Commons License.
 * It is available through the world-wide-web at this URL:
 * http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 * If you are unable to obtain it through the world-wide-web, please send an email
 * to servicedesk@tig.nl so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact servicedesk@tig.nl for more information.
 *
 * @copyright   Copyright (c) Total Internet Group B.V. https://tig.nl/copyright
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 */
class TIG_Buckaroo3Extended_Model_PaymentMethods_Klarna_Observer extends TIG_Buckaroo3Extended_Model_Observer_Abstract
{
    /** @var string $_code */
    protected $_code = 'buckaroo3extended_klarna';

    /** @var string $_method */
    protected $_method = 'klarna';

    /**
     * @param Varien_Event_Observer $observer
     *
     * @return $this
     */
    public function buckaroo3extended_request_setmethod(Varien_Event_Observer $observer)
    {
        if ($this->_isChosenMethod($observer) === false) {
            return $this;
        }

        /** @var TIG_Buckaroo3Extended_Model_Request_Abstract $request */
        $request = $observer->getRequest();

        $codeBits = explode('_', $this->_code);
        $code = end($codeBits);

        $request->setMethod($code);

        return $this;
    }

    /**
     * @param Varien_Event_Observer $observer
     *
     * @return $this
     */
    public function buckaroo3extended_request_addservices(Varien_Event_Observer $observer)
    {
        if ($this->_isChosenMethod($observer) === false) {
            return $this;
        }

        /** @var TIG_Buckaroo3Extended_Model_Request_Abstract $request */
        $request = $observer->getRequest();
        $vars = $request->getVars();

        $array = array(
            $this->_method => array(
                'action'   => 'Reserve', //Authorize
                'version'  => '1',
            ),
        );

        if (array_key_exists('services', $vars) && is_array($vars['services'])) {
            $vars['services'] = array_merge($vars['services'], $array);
        } else {
            $vars['services'] = $array;
        }

        $vars['request_type'] = 'DataRequest';

        $request->setVars($vars);

        return $this;
    }

    /**
     * @param Varien_Event_Observer $observer
     *
     * @return $this
     */
    public function buckaroo3extended_request_addcustomvars(Varien_Event_Observer $observer)
    {
        if ($this->_isChosenMethod($observer) === false) {
            return $this;
        }

        /** @var TIG_Buckaroo3Extended_Model_Request_Abstract $request */
        $request            = $observer->getRequest();
        $this->_billingInfo = $request->getBillingInfo();
        $this->_order       = $request->getOrder();

        $vars = $request->getVars();

        $this->addAddressesVariables($vars);
        $this->addAdditionalInfo($vars);
        $this->addArticlesVariables($vars);

        $request->setVars($vars);

        return $this;
    }

    /**
     * @param Varien_Event_Observer $observer
     *
     * @return $this
     */
    public function buckaroo3extended_refund_request_addservices(Varien_Event_Observer $observer)
    {
        if ($this->_isChosenMethod($observer) === false) {
            return $this;
        }

        /** @var TIG_Buckaroo3Extended_Model_Request_Abstract $request */
        $request = $observer->getRequest();
        $vars = $request->getVars();

        $array = array(
            'action'    => 'Refund',
            'version'   => 1,
        );

        if (array_key_exists('services', $vars) && is_array($vars['services'][$this->_method])) {
            $vars['services'][$this->_method] = array_merge($vars['services'][$this->_method], $array);
        } else {
            $vars['services'][$this->_method] = $array;
        }

        $request->setVars($vars);

        return $this;
    }

    /**
     * @param Varien_Event_Observer $observer
     *
     * @return $this
     */
    public function buckaroo3extended_refund_request_addcustomvars(Varien_Event_Observer $observer)
    {
        if ($this->_isChosenMethod($observer) === false) {
            return $this;
        }

        /** @var TIG_Buckaroo3Extended_Model_Request_Abstract $request */
        $request = $observer->getRequest();
        $vars = $request->getVars();

        $vars['channel'] = 'Web';

        if (!isset($vars['currency']) || strlen($vars['currency']) <= 0) {
            $vars['currency'] = $request->getOrder()->getBaseCurrency()->getCurrencyCode();
        }

        $request->setVars($vars);

        return $this;
    }

    /**
     * @param Varien_Event_Observer $observer
     *
     * @return $this
     */
    public function buckaroo3extended_capture_request_addservices(Varien_Event_Observer $observer)
    {
        if ($this->_isChosenMethod($observer) === false) {
            return $this;
        }

        /** @var TIG_Buckaroo3Extended_Model_Request_Abstract $request */
        $request = $observer->getRequest();
        $vars = $request->getVars();

        $array = array(
            $this->_method => array(
                'action'   => 'Pay', //Capture
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

    /**
     * @param Varien_Event_Observer $observer
     *
     * @return $this
     */
    public function buckaroo3extended_capture_request_addcustomvars(Varien_Event_Observer $observer)
    {
        if ($this->_isChosenMethod($observer) === false) {
            return $this;
        }

        /** @var TIG_Buckaroo3Extended_Model_Request_Abstract $request */
        $request            = $observer->getRequest();
        $this->_billingInfo = $request->getBillingInfo();
        $this->_order       = $request->getOrder();

        $vars = $request->getVars();

        $this->addCaptureAditionalInfo($vars);
        $this->addPartialArticlesVariables($vars);

        $request->setVars($vars);

        return $this;
    }

    /**
     * @param Varien_Event_Observer $observer
     *
     * @return $this
     */
    public function buckaroo3extended_cancelauthorize_request_addservices(Varien_Event_Observer $observer)
    {
        if ($this->_isChosenMethod($observer) === false) {
            return $this;
        }

        /** @var TIG_Buckaroo3Extended_Model_Request_Abstract $request */
        $request = $observer->getRequest();
        $vars = $request->getVars();

        $array = array(
            $this->_method => array(
                'action'   => 'CancelReservation', //CancelAuthorize
                'version'  => '1',
            ),
        );

        if (array_key_exists('services', $vars) && is_array($vars['services'])) {
            $vars['services'] = array_merge($vars['services'], $array);
        } else {
            $vars['services'] = $array;
        }

        $vars['request_type'] = 'DataRequest';

        $request->setVars($vars);

        return $this;
    }

    /**
     * @param Varien_Event_Observer $observer
     *
     * @return $this
     */
    public function buckaroo3extended_cancelauthorize_request_addcustomvars(Varien_Event_Observer $observer)
    {
        if ($this->_isChosenMethod($observer) === false) {
            return $this;
        }

        /** @var TIG_Buckaroo3Extended_Model_Request_Abstract $request */
        $request      = $observer->getRequest();
        $this->_order = $request->getOrder();
        $vars         = $request->getVars();

        $this->addCancelAditionalInfo($vars);

        $request->setVars($vars);

        return $this;
    }

    /**
     * @param Varien_Event_Observer $observer
     *
     * @return $this
     */
    public function buckaroo3extended_response_custom_processing(Varien_Event_Observer $observer)
    {
        if ($this->_isChosenMethod($observer) === false) {
            return $this;
        }

        /** @var Mage_Sales_Model_Order $order */
        $order = $observer->getOrder();
        $responseObject = $observer->getResponseobject();

        if (isset($responseObject->Services) && count($responseObject->Services->Service->ResponseParameter) > 0) {
            $reserVationNumber = $this->getReservationNumber($responseObject->Services->Service->ResponseParameter);

            if (null !== $reserVationNumber) {
                $order->setBuckarooReservationNumber($reserVationNumber);
                $order->save();
            }
        }

        if ($responseObject->Status->Code->Code == '791') {
            $helper = Mage::helper('buckaroo3extended');
            $method = $order->getPayment()->getMethod();
            $status = $helper->getNewStates(
                TIG_Buckaroo3Extended_Helper_Data::BUCKAROO_PENDING_PAYMENT,
                $order,
                $method
            );
            $message = $helper->__('Klarna is doing an additional check. The status will be known within 24 hours.');

            $order->addStatusHistoryComment($message, $status[1]);
            $order->save();
        }

        return $this;
    }

    /**
     * @param Varien_Event_Observer $observer
     *
     * @return $this
     * @throws Exception
     */
    public function buckaroo3extended_push_custom_processing(Varien_Event_Observer $observer)
    {
        if ($this->_isChosenMethod($observer) === false) {
            return $this;
        }

        $response = $observer->getResponse();

        if ($response['status'] == TIG_Buckaroo3Extended_Helper_Data::BUCKAROO_REJECTED) {
            /** @var Mage_Sales_Model_Order $order */
            $order = $observer->getOrder();
            $method = $order->getPayment()->getMethod();
            $helper = Mage::helper('buckaroo3extended');

            $status = $helper->getNewStates(TIG_Buckaroo3Extended_Helper_Data::BUCKAROO_FAILED, $order, $method);
            $message = $helper->__('Klarna has rejected the payment request. Please check the Buckaroo Payment Plaza for additional information.');

            // skipCancelAuthorize is a custom, temporary data.
            // This allows us to cancel an order without sending a call to Buckaroo.
            $order->getPayment()->setSkipCancelAuthorize(true);
            $order->cancel();
            $order->addStatusHistoryComment($message, $status[1]);
            $order->save();
        }

        return $this;
    }

    /**
     * @param array $vars
     */
    private function addAddressesVariables(&$vars)
    {
        $requestArray       = array();

        $billingAddress = $this->_order->getBillingAddress();
        $billingInfo = $this->getAddressInfo($billingAddress);
        $billingInfo['BillingCompanyName'] = $billingAddress->getCompany();
        $requestArray = array_merge($requestArray, $billingInfo);

        $shippingAddress = $this->_order->getShippingAddress();
        $shippingInfo = $this->getAddressInfo($shippingAddress);
        $shippingInfo['ShippingCompany'] = $shippingAddress->getCompany();
        $requestArray = array_merge($requestArray, $shippingInfo);

        if (array_key_exists('customVars', $vars) && is_array($vars['customVars'][$this->_method])) {
            $vars['customVars'][$this->_method] = array_merge($vars['customVars'][$this->_method], $requestArray);
        } else {
            $vars['customVars'][$this->_method] = $requestArray;
        }
    }

    /**
     * @param array $vars
     */
    private function addAdditionalInfo(&$vars)
    {
        $additionalFields = Mage::getSingleton('checkout/session')->getData('additionalFields');

        $requestArray = array(
            'Gender'                => $additionalFields['BPE_customer_gender'],
            'OperatingCountry'      => Mage::getStoreConfig('general/country/default', $this->_order->getStoreId()),
            'Pno'                   => $additionalFields['BPE_customer_dob'],
            'ShippingSameAsBilling' => $this->shippingSameAsBilling(),
        );

        if (array_key_exists('customVars', $vars) && is_array($vars['customVars'][$this->_method])) {
            $vars['customVars'][$this->_method] = array_merge($vars['customVars'][$this->_method], $requestArray);
        } else {
            $vars['customVars'][$this->_method] = $requestArray;
        }

        // Reserve Webservice doesn't support amount and orderId, but does require an invoiceId
        $vars['invoiceId'] = $vars['orderId'];
        unset($vars['amountCredit']);
        unset($vars['amountDebit']);
        unset($vars['orderId']);
    }

    /**
     * @param $vars
     */
    private function addCaptureAditionalInfo(&$vars)
    {
        $array = array(
            'ReservationNumber' => $this->_order->getBuckarooReservationNumber(),
            'PreserveReservation' => 'false',
            'SendByMail' => 'false',
            'SendByEmail' => 'false',
        );

        $sendInvoiceBy = Mage::getStoreConfig(
            'buckaroo/buckaroo3extended_' . $this->_method . '/send_invoice_by',
            Mage::app()->getStore()->getStoreId()
        );
        $sendInvoiceBy = ucfirst($sendInvoiceBy);
        $array['SendBy' . $sendInvoiceBy] = 'true';

        /** @var Mage_Sales_Model_Resource_Order_Invoice_Collection $invoiceCollection */
        $invoiceCollection = $this->_order->getInvoiceCollection();

        /** @var Mage_Sales_Model_Order_Invoice $lastInvoice */
        $lastInvoice = $invoiceCollection->getLastItem();

        if ($this->_order->getPayment()->canCapturePartial()
            && count($invoiceCollection) > 0
            && $lastInvoice->getBaseGrandTotal() < $this->_order->getBaseGrandTotal()) {
            $array['PreserveReservation'] = 'true';
        }

        if (array_key_exists('customVars', $vars) && is_array($vars['customVars'][$this->_method])) {
            $vars['customVars'][$this->_method] = array_merge($vars['customVars'][$this->_method], $array);
        } else {
            $vars['customVars'][$this->_method] = $array;
        }

        // Pay Webservice doesn't support OriginalTransactionKey
        unset($vars['OriginalTransactionKey']);
    }

    /**
     * @param $vars
     */
    private function addCancelAditionalInfo(&$vars)
    {
        $array = array(
            'ReservationNumber' => $this->_order->getBuckarooReservationNumber()
        );

        if (array_key_exists('customVars', $vars) && is_array($vars['customVars'][$this->_method])) {
            $vars['customVars'][$this->_method] = array_merge($vars['customVars'][$this->_method], $array);
        } else {
            $vars['customVars'][$this->_method] = $array;
        }

        // CancelReservation Webservice doesn't support amountCredit and originalTransactionKey
        unset($vars['amountCredit']);
        unset($vars['OriginalTransactionKey']);
    }

    /**
     * @param array $vars
     */
    private function addArticlesVariables(&$vars)
    {
        $products = $this->_order->getAllItems();
        $max      = 99;
        $i        = 1;
        $group    = array();

        /** @var $item Mage_Sales_Model_Order_Item */
        foreach ($products as $item) {
            if (empty($item) || $item->hasParentItemId()) {
                continue;
            }

            $article['ArticleNumber']['value']   = $item->getId();
            $article['ArticlePrice']['value']    = $item->getBasePriceInclTax();
            $article['ArticleQuantity']['value'] = round($item->getQtyOrdered(), 0);
            $article['ArticleTitle']['value']    = $item->getName();
            $article['ArticleVat']['value']      = $item->getTaxPercent();

            $group[$i] = $article;
            $i++;

            if ($i > $max) {
                break;
            }
        }

        if (Mage::helper('buckaroo3extended')->isEnterprise()) {
            $gwId = 1;

            if ($this->_order->getGwBasePrice() > 0) {
                $gwPrice = $this->_order->getGwBasePrice() + $this->_order->getGwBaseTaxAmount();

                $gwOrder = array();
                $gwOrder['ArticleNumber']['value']   = 'gwo_' . $this->_order->getGwId();
                $gwOrder['ArticlePrice']['value']    = $gwPrice;
                $gwOrder['ArticleQuantity']['value'] = 1;
                $gwOrder['ArticleTitle']['value']    = Mage::helper('buckaroo3extended')->__('Gift Wrapping for Order');
                $gwOrder['ArticleVat']['value']      = 0.00;

                $group[] = $gwOrder;

                $gwId += $this->_order->getGwId();
            }

            if ($this->_order->getGwItemsBasePrice() > 0) {
                $gwiPrice = $this->_order->getGwItemsBasePrice() + $this->_order->getGwItemsBaseTaxAmount();

                $gwiOrder = array();
                $gwiOrder['ArticleNumber']['value']   = 'gwi_' . $gwId;
                $gwiOrder['ArticlePrice']['value']    = $gwiPrice;
                $gwiOrder['ArticleQuantity']['value'] = 1;
                $gwiOrder['ArticleTitle']['value']   = Mage::helper('buckaroo3extended')->__('Gift Wrapping for Items');
                $gwiOrder['ArticleVat']['value']      = 0.00;

                $group[] = $gwiOrder;
            }
        }

        end($group);
        $key             = (int)key($group);
        $feeGroupId      = $key + 1;
        $paymentFeeArray = $this->getPaymentFeeLine();

        if (false !== $paymentFeeArray && is_array($paymentFeeArray)) {
            $group[$feeGroupId] = $paymentFeeArray;
        }

        $shipmentCostsGroupId = $feeGroupId + 1;
        $shipmentCostsArray = $this->getShipmentCostsLine();

        if (false !== $shipmentCostsArray && is_array($shipmentCostsArray)) {
            $group[$shipmentCostsGroupId] = $shipmentCostsArray;
        }

        $requestArray = array('Articles' => $group);

        if (array_key_exists('customVars', $vars) && is_array($vars['customVars'][$this->_method])) {
            $vars['customVars'][$this->_method] = array_merge($vars['customVars'][$this->_method], $requestArray);
        } else {
            $vars['customVars'][$this->_method] = $requestArray;
        }
    }

    /**
     * @param $vars
     */
    private function addPartialArticlesVariables(&$vars)
    {
        /** @var Mage_Sales_Model_Resource_Order_Invoice_Collection $invoiceCollection */
        $invoiceCollection = $this->_order->getInvoiceCollection();

        $products = $invoiceCollection->getLastItem()->getAllItems();
        $max      = 99;
        $i        = 1;
        $group    = array();

        /** @var Mage_Sales_Model_Order_Invoice_Item $item */
        foreach ($products as $item) {
            if (empty($item) || ($item->getOrderItem() && $item->getOrderItem()->getParentItem())) {
                continue;
            }

            $article['ArticleNumber']['value']   = $item->getOrderItemId();
            $article['ArticleQuantity']['value'] = round($item->getQty(), 0);

            $group[$i] = $article;
            $i++;

            if ($i > $max) {
                break;
            }
        }

        if (Mage::helper('buckaroo3extended')->isEnterprise() && count($invoiceCollection) == 1) {
            $gwId = 1;

            if ($this->_order->getGwBasePrice() > 0) {
                $gwOrder = array();
                $gwOrder['ArticleNumber']['value']   = 'gwo_' . $this->_order->getGwId();
                $gwOrder['ArticleQuantity']['value'] = 1;

                $group[] = $gwOrder;

                $gwId += $this->_order->getGwId();
            }

            if ($this->_order->getGwItemsBasePrice() > 0) {
                $gwiOrder = array();
                $gwiOrder['ArticleNumber']['value']   = 'gwi_' . $gwId;
                $gwiOrder['ArticleQuantity']['value'] = 1;

                $group[] = $gwiOrder;
            }
        }

        end($group);
        $key             = (int)key($group);
        $feeGroupId      = $key + 1;
        $paymentFeeArray = $this->getPaymentFeeLine();

        if (false !== $paymentFeeArray && is_array($paymentFeeArray) && count($invoiceCollection) == 1) {
            unset($paymentFeeArray['ArticlePrice']);
            unset($paymentFeeArray['ArticleTitle']);
            unset($paymentFeeArray['ArticleVat']);
            $group[$feeGroupId] = $paymentFeeArray;
        }

        $shipmentCostsGroupId = $feeGroupId + 1;
        $shipmentCostsArray = $this->getShipmentCostsLine();

        if (false !== $shipmentCostsArray && is_array($shipmentCostsArray) && count($invoiceCollection) == 1) {
            unset($shipmentCostsArray['ArticlePrice']);
            unset($shipmentCostsArray['ArticleTitle']);
            unset($shipmentCostsArray['ArticleVat']);
            $group[$shipmentCostsGroupId] = $shipmentCostsArray;
        }

        $requestArray = array('Articles' => $group);

        if (array_key_exists('customVars', $vars) && is_array($vars['customVars'][$this->_method])) {
            $vars['customVars'][$this->_method] = array_merge($vars['customVars'][$this->_method], $requestArray);
        } else {
            $vars['customVars'][$this->_method] = $requestArray;
        }
    }

    /**
     * @param Mage_Sales_Model_Order_Address $address
     *
     * @return array
     */
    private function getAddressInfo(Mage_Sales_Model_Order_Address $address)
    {
        $session            = Mage::getSingleton('checkout/session');
        $additionalFields   = $session->getData('additionalFields');

        $addressType    = ucfirst($address->getAddressType());
        $streetFull     = $this->processAddress($address->getStreetFull());

        $rawPhoneNumber = $address->getTelephone();
        if (!is_numeric($rawPhoneNumber) || $rawPhoneNumber == '-') {
            $rawPhoneNumber = $additionalFields['BPE_customer_phonenumber'];
        }

        $phoneNumber = $this->processPhoneNumber($rawPhoneNumber);
        if ($address->getCountryId() == 'BE') {
            $phoneNumber = $this->processPhoneNumberBe($rawPhoneNumber);
        }

        // First-, lastname and country always should be the same, so use the billing address to achieve this
        $billingAddress = $this->_order->getBillingAddress();

        $addressInfo = array(
            $addressType . 'FirstName'         => $billingAddress->getFirstname(),
            $addressType . 'LastName'          => $billingAddress->getLastname(),
            $addressType . 'Street'            => $streetFull['street'],
            $addressType . 'HouseNumber'       => $streetFull['house_number'],
            $addressType . 'HouseNumberSuffix' => $streetFull['number_addition'],
            $addressType . 'PostalCode'        => $address->getPostcode(),
            $addressType . 'City'              => $address->getCity(),
            $addressType . 'Country'           => $billingAddress->getCountryId(),
            $addressType . 'Email'             => $address->getEmail(),
            $addressType . 'CellPhoneNumber'   => $phoneNumber['clean'],
        );

        return $addressInfo;
    }

    /**
     * Checks if shipping-address is different from billing-address.
     * Buckaroo needs the bool value as a string, therefore the bool is returned as text.
     *
     * @return string
     */
    private function shippingSameAsBilling()
    {
        // exclude certain keys that are always different
        $excludeKeys = array(
            'entity_id',
            'customer_address_id',
            'quote_address_id',
            'region_id',
            'customer_id',
            'address_type'
        );

        $oBillingAddress = $this->_order->getBillingAddress()->getData();
        $oShippingAddress = $this->_order->getShippingAddress()->getData();

        $oBillingAddressFiltered = array_diff_key($oBillingAddress, array_flip($excludeKeys));
        $oShippingAddressFiltered = array_diff_key($oShippingAddress, array_flip($excludeKeys));

        //differentiate the addressess, when some data is different an array with changes will be returned
        $addressDiff = array_diff($oBillingAddressFiltered, $oShippingAddressFiltered);

        if (empty($addressDiff)) {
            return "true";
        }

        return "false";
    }

    /**
     * @param $fullStreet
     *
     * @return array
     */
    private function processAddress($fullStreet)
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
     * The final output should look like 0031123456789 or 0031612345678
     * So 13 characters max else number is not valid
     *
     * @param $telephoneNumber
     *
     * @return array
     */
    private function processPhoneNumber($telephoneNumber)
    {
        $number = $telephoneNumber;

        //strip out the non-numeric characters:
        $match = preg_replace('/[^0-9]/Uis', '', $number);
        if ($match) {
            $number = $match;
        }

        $return = array(
            "orginal" => $number,
            "clean" => false,
            "mobile" => $this->_isMobileNumber($number),
            "valid" => false
        );
        $numberLength = strlen((string)$number);

        if ($numberLength == 13) {
            $return['valid'] = true;
            $return['clean'] = $number;
        } elseif ($numberLength > 13 || $numberLength == 12 || $numberLength == 11) {
            $return['clean'] = $this->_isValidNotation($number);

            if (strlen((string)$return['clean']) == 13) {
                $return['valid'] = true;
            }
        } elseif ($numberLength == 10) {
            $return['clean'] = '0031' . substr($number, 1);

            if (strlen((string) $return['clean']) == 13) {
                $return['valid'] = true;
            }
        } else {
            $return['valid'] = true;
            $return['clean'] = $number;
        }

        return $return;
    }

    /**
     * The final output should look like: 003212345678 or 0032461234567
     *
     * @param $telephoneNumber
     *
     * @return array
     */
    private function processPhoneNumberBe($telephoneNumber)
    {
        $number = $telephoneNumber;

        //strip out the non-numeric characters:
        $match = preg_replace('/[^0-9]/Uis', '', $number);
        if ($match) {
            $number = $match;
        }

        $return = array(
            "orginal" => $number,
            "clean" => false,
            "mobile" => $this->_isMobileNumberBe($number),
            "valid" => false
        );
        $numberLength = strlen((string)$number);

        if (($return['mobile'] && $numberLength == 13) || (!$return['mobile'] && $numberLength == 12)) {
            $return['valid'] = true;
            $return['clean'] = $number;
        } elseif ($numberLength > 13
            || (!$return['mobile'] && $numberLength > 12)
            || ($return['mobile'] && ($numberLength == 11 || $numberLength == 12))
            || (!$return['mobile'] && ($numberLength == 10 || $numberLength == 11))
        ) {
            $return['clean'] = $this->_isValidNotationBe($number);
            $cleanLength = strlen((string)$return['clean']);

            if (($return['mobile'] && $cleanLength == 13) || (!$return['mobile'] && $cleanLength == 12)) {
                $return['valid'] = true;
            }
        } elseif (($return['mobile'] && $numberLength == 10) || (!$return['mobile'] && $numberLength == 9)) {
            $return['clean'] = '0032'.substr($number, 1);
            $cleanLength = strlen((string)$return['clean']);

            if (($return['mobile'] && $cleanLength == 13) || (!$return['mobile'] && $cleanLength == 12)) {
                $return['valid'] = true;
            }
        } else {
            $return['valid'] = true;
            $return['clean'] = $number;
        }

        return $return;
    }

    /**
     * @return bool|array
     */
    private function getPaymentFeeLine()
    {
        $fee    = (double) $this->_order->getBuckarooFee();
        $feeTax = (double) $this->_order->getBuckarooFeeTax();

        $store = $this->_order->getStore();
        $taxCalculation = Mage::getModel('tax/calculation');
        $request = $taxCalculation->getRateRequest(null, null, null, $store);
        $taxClassId = Mage::getStoreConfig('tax/classes/buckaroo_fee', $store);
        $percent = $taxCalculation->getRate($request->setProductClassId($taxClassId));


        if ($fee > 0) {
            $article['ArticleNumber']['value']   = 1;
            $article['ArticlePrice']['value']    = round($fee + $feeTax, 2);
            $article['ArticleQuantity']['value'] = 1;
            $article['ArticleTitle']['value']    = 'Servicekosten';
            $article['ArticleVat']['value']      = (double) $percent;

            return $article;
        }

        return false;
    }

    /**
     * @return bool
     */
    private function getShipmentCostsLine()
    {
        $shippingCosts = round($this->_order->getBaseShippingInclTax(), 2);

        $store = $this->_order->getStore();
        $taxCalculation = Mage::getModel('tax/calculation');
        $request = $taxCalculation->getRateRequest(null, null, null, $store);
        $taxClassId = Mage::getStoreConfig('tax/classes/shipping_tax_class', $store);
        $percent = $taxCalculation->getRate($request->setProductClassId($taxClassId));

        if ($shippingCosts > 0) {
            $article['ArticleNumber']['value']   = 2;
            $article['ArticlePrice']['value']    = $shippingCosts;
            $article['ArticleQuantity']['value'] = 1;
            $article['ArticleTitle']['value']    = 'Verzendkosten';
            $article['ArticleVat']['value']      = (double) $percent;

            return $article;
        }

        return false;
    }

    /**
     * @param array $responseParameters
     *
     * @return null|string
     */
    private function getReservationNumber($responseParameters)
    {
        if (isset($responseParameters->Name) && $responseParameters->Name == 'ReservationNumber') {
            return $responseParameters->_;
        }

        $reservationNumber = null;

        array_walk(
            $responseParameters,
            function ($parameter, $index) use (&$reservationNumber) {
                if (isset($parameter->Name) && $parameter->Name == 'ReservationNumber') {
                    $reservationNumber = $parameter->_;
                }
            }
        );

        return $reservationNumber;
    }
}

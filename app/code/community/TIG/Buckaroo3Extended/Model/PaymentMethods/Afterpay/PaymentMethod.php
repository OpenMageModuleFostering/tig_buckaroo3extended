<?php
/**  ____________  _     _ _ ________  ___  _ _  _______   ___  ___  _  _ _ ___
 *   \_ _/ \_ _/ \| |   |_| \ \_ _/  \| _ || \ |/  \_ _/  / __\| _ |/ \| | | _ \
 *    | | | | | ' | |_  | |   || | '_/|   /|   | '_/| |  | |_ \|   / | | | | __/
 *    |_|\_/|_|_|_|___| |_|_\_||_|\__/|_\_\|_\_|\__/|_|   \___/|_\_\\_/|___|_|
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Creative Commons License.
 * If you are unable to obtain it through the world-wide-web, please send an email
 * to servicedesk@totalinternetgroup.nl so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact servicedesk@totalinternetgroup.nl for more information.
 *
 * @copyright   2014 Total Internet Group B.V. (http://www.totalinternetgroup.nl)
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 */

class TIG_Buckaroo3Extended_Model_PaymentMethods_Afterpay_PaymentMethod extends TIG_Buckaroo3Extended_Model_PaymentMethods_PaymentMethod
{
    public $allowedCurrencies = array(
        'EUR',
    );

    protected $_code = 'buckaroo3extended_afterpay';

    protected $_formBlockType = 'buckaroo3extended/paymentMethods_afterpay_checkout_form';

    protected $_canRefund               = true;
    protected $_canRefundInvoicePartial = false;

    public function getOrderPlaceRedirectUrl()
    {
        $session = Mage::getSingleton('checkout/session');

        $post = Mage::app()->getRequest()->getPost();

        $accountNumber = isset($post[$this->_code . '_bpe_customer_account_number']) ? $post[$this->_code . '_bpe_customer_account_number'] : '';

        $customerBirthDate = date(
            'Y-m-d', strtotime($post['payment'][$this->_code]['year']
                . '-' . $post['payment'][$this->_code]['month']
                . '-' . $post['payment'][$this->_code]['day'])
        );

        $array = array(
            'BPE_Customergender'    => $post[$this->_code . '_BPE_Customergender'],
            'BPE_AccountNumber'     => $this->filterAccount($accountNumber),
            'BPE_PhoneNumber'       => $post[$this->_code . '_bpe_customer_phone_number'],
            'BPE_customerbirthdate' => $customerBirthDate,
            'BPE_B2B'               => (int)$post[$this->_code . '_BPE_BusinessSelect'],
            'BPE_Accept'            => 'true',
        );

        if((int)$array['BPE_B2B'] == 2){
            $additionalArray = array(
                'BPE_CompanyCOCRegistration' => $post[$this->_code . '_BPE_CompanyCOCRegistration'],
                'BPE_CompanyName'            => $post[$this->_code . '_BPE_CompanyName'],
                'BPE_CostCentre'             => $post[$this->_code . '_BPE_CostCentre'],
                'BPE_VatNumber'              => $post[$this->_code . '_BPE_VatNumber'],
            );

            $array = array_merge($array,$additionalArray);
        }

        $session->setData('additionalFields',$array);

        return parent::getOrderPlaceRedirectUrl();
    }

    public function validate()
    {
        $postData = Mage::app()->getRequest()->getPost();
        if (
            !array_key_exists($this->_code . '_bpe_accept', $postData)
            || $postData[$this->_code . '_bpe_accept'] != 'checked'
        ) {
            Mage::throwException(
                Mage::helper('buckaroo3extended')->__('Please accept the terms and conditions.')
            );
        }

        $this->getInfoInstance()->setAdditionalInformation('checked_terms_and_conditions', true);

        return parent::validate();
    }

    /**
     * @param null | Mage_Sales_Model_Quote $quote
     * @return bool
     */
    public function isAvailable($quote = null)
    {

        if(is_null($quote) && Mage::helper('buckaroo3extended')->isAdmin()){
            // Uncomment this code to get all active Buckaroo payment methods in the backend. (3th party extensions)
            /*if(Mage::getStoreConfigFlag('buckaroo/' . $this->_code . '/active', Mage::app()->getStore()->getId())){
                return true;
            }*/

            return false;
        }

        $quoteItems = $quote->getAllVisibleItems();
        if(count($quoteItems) > 99){
            return false;
        }

        $session = Mage::getSingleton('checkout/session');
        if($session->getData('buckarooAfterpayRejected') == true){
            return false;
        }


        //check if the country specified in the billing address is allowed to use this payment method
        if (Mage::getStoreConfig('buckaroo/' . $this->_code . '/allowspecific', $quote->getStoreId()) == 1
            && $quote->getBillingAddress()->getCountry())
        {
            $allowedCountries = explode(',',Mage::getStoreConfig('buckaroo/' . $this->_code . '/specificcountry', $quote->getStoreId()));
            $country = $quote->getBillingAddress()->getCountry();

            if (!in_array($country,$allowedCountries)) {
                return false;
            }
        }

        $areaAllowed = null;
        if ($this->canUseInternal()) {
            $areaAllowed = Mage::getStoreConfig('buckaroo/' . $this->_code . '/area', $quote->getStoreId());
        }

        //check if the paymentmethod is available in the current shop area (frontend or backend)
        if ($areaAllowed == 'backend'
            && !Mage::helper('buckaroo3extended')->isAdmin()
        ) {
            return false;
        } elseif ($areaAllowed == 'frontend'
            && Mage::helper('buckaroo3extended')->isAdmin()
        ) {
            return false;
        }

        // check if max amount for the issued PaymentMethod is set and if the quote basegrandtotal exceeds that
        $maxAmount = Mage::getStoreConfig('buckaroo/' . $this->_code . '/max_amount', $quote->getStoreId());
        if (!empty($maxAmount)
            && !empty($quote)
            && $quote->getBaseGrandTotal() > $maxAmount)
        {
            return false;
        }

        // check if min amount for the issued PaymentMethod is set and if the quote basegrandtotal is less than that
        $minAmount = Mage::getStoreConfig('buckaroo/' . $this->_code . '/min_amount', $quote->getStoreId());
        if (!empty($minAmount)
            && !empty($quote)
            && $quote->getBaseGrandTotal() < $minAmount)
        {
            return false;
        }

        //check if the module is set to enabled
        if (!Mage::getStoreConfig('buckaroo/' . $this->_code . '/active', $quote->getStoreId())) {
            return false;
        }

        //limit by ip
        if (mage::getStoreConfig('dev/restrict/allow_ips') && Mage::getStoreConfig('buckaroo/' . $this->_code . '/limit_by_ip'))
        {
            $allowedIp = explode(',', mage::getStoreConfig('dev/restrict/allow_ips'));
            if (!in_array(Mage::helper('core/http')->getRemoteAddr(), $allowedIp))
            {
                return false;
            }
        }

        // get current currency code
        $currency = Mage::app()->getStore()->getBaseCurrencyCode();


        // currency is not available for this module
        if (!in_array($currency, $this->allowedCurrencies))
        {
            return false;
        }

        return TIG_Buckaroo3Extended_Model_Request_Availability::canUseBuckaroo($quote);
    }
}

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
class TIG_Buckaroo3Extended_Model_PaymentMethods_Klarna_PaymentMethod extends TIG_Buckaroo3Extended_Model_PaymentMethods_PaymentMethod
{
    public $allowedCurrencies = array(
            'EUR',
        );

    protected $_code = 'buckaroo3extended_klarna';

    protected $_formBlockType = 'buckaroo3extended/paymentMethods_klarna_checkout_form';

    /**
     * @return string
     */
    public function getOrderPlaceRedirectUrl()
    {
        $session = Mage::getSingleton('checkout/session');
        $post = Mage::app()->getRequest()->getPost();

        $postArray = $this->getBPEPostData($post);
        $session->setData('additionalFields', $postArray);

        return parent::getOrderPlaceRedirectUrl();
    }

    /**
     * @param array $post
     *
     * @return array
     */
    private function getBPEPostData($post)
    {
        $dobPost = $post['payment'][$this->_code];
        $customerDob = date(
            'dmY',
            strtotime($dobPost['year'] . '-' . $dobPost['month'] . '-' . $dobPost['day'])
        );

        $postArray = array(
            'BPE_customer_gender'      => $post[$this->_code . '_BPE_Customergender'],
            'BPE_customer_phonenumber' => $post[$this->_code . '_bpe_customer_phone_number'],
            'BPE_customer_dob'         => $customerDob,
        );

        return $postArray;
    }

    /**
     * Klarna is always in authorize mode, therefore return the authorize payment action when asked for it
     *
     * {@inheritdoc}
     */
    public function getConfigData($field, $storeId = null)
    {
        if ($field == 'payment_action') {
            return Mage_Payment_Model_Method_Abstract::ACTION_AUTHORIZE;
        }

        return parent::getConfigData($field, $storeId);
    }

    /**
     * {@inheritdoc}
     */
    public function capture(Varien_Object $payment, $amount)
    {
        if (!$this->canCapture()) {
            Mage::throwException(Mage::helper('payment')->__('Capture action is not available.'));
        }

        /** @var TIG_Buckaroo3Extended_Model_Request_Capture $captureRequest */
        $captureRequest = Mage::getModel(
            'buckaroo3extended/request_capture',
            array(
                'payment' => $payment
            )
        );

        try {
            $captureRequest->sendRequest();
        } catch (Exception $e) {
            Mage::helper('buckaroo3extended')->logException($e);
            Mage::throwException($e->getMessage());
        }

        return $this;
    }
}

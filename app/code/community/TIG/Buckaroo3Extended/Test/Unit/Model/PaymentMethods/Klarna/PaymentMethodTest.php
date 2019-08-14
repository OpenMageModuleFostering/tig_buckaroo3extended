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
class TIG_Buckaroo3Extended_Test_Unit_Model_PaymentMethods_Klarna_PaymentMethodTest
    extends TIG_Buckaroo3Extended_Test_Framework_TIG_Test_TestCase
{
    /** @var null|TIG_Buckaroo3Extended_Model_PaymentMethods_Klarna_PaymentMethod */
    protected $_instance = null;

    /**
     * @return null|TIG_Buckaroo3Extended_Model_PaymentMethods_Klarna_PaymentMethod
     */
    protected function _getInstance()
    {
        if ($this->_instance === null) {
            $this->_instance = new TIG_Buckaroo3Extended_Model_PaymentMethods_Klarna_PaymentMethod();
        }

        return $this->_instance;
    }

    /**
     * @return mixed
     */
    protected function _getMockPayment()
    {
        $mockOrderAddress = $this->getMockBuilder('Mage_Sales_Model_Order_Address')->getMock();

        $mockOrder = $this->getMockBuilder('Mage_Sales_Model_Order')
            ->setMethods(array('getPayment', 'getBillingAddress', 'getShippingAddress'))
            ->getMock();
        $mockOrder->expects($this->any())
            ->method('getBillingAddress')
            ->will($this->returnValue($mockOrderAddress));
        $mockOrder->expects($this->any())
            ->method('getShippingAddress')
            ->will($this->returnValue($mockOrderAddress));

        $mockPaymentInfo = $this->getMockBuilder('Mage_Sales_Model_Order_Payment')
            ->setMethods(array('getOrder'))
            ->getMock();
        $mockPaymentInfo->expects($this->any())
            ->method('getOrder')
            ->willReturn($mockOrder);

        $mockOrder->expects($this->any())
            ->method('getPayment')
            ->willReturn($mockPaymentInfo);

        return $mockPaymentInfo;
    }

    public function testGetAllowedCurrencies()
    {
        $instance = $this->_getInstance();
        $result = $instance->getAllowedCurrencies();

        $this->assertInternalType('array', $result);
        $this->assertContains('EUR', $result);
    }

    public function testGetCode()
    {
        $instance = $this->_getInstance();
        $result = $instance->getCode();

        $this->assertEquals('buckaroo3extended_klarna', $result);
    }

    public function testGetFormBlockType()
    {
        $instance = $this->_getInstance();
        $result = $instance->getFormBlockType();

        $this->assertEquals('buckaroo3extended/paymentMethods_klarna_checkout_form', $result);
    }

    public function testGetOrderPlaceRedirectUrl()
    {
        $postArray = array(
            'payment' => array(
                'buckaroo3extended_klarna' => array(
                    'year' => '1970',
                    'month' => '07',
                    'day' => '10'
                )
            ),
            'buckaroo3extended_klarna_BPE_Customergender' => 1,
            'buckaroo3extended_klarna_bpe_customer_phone_number' => '0612345678',
        );

        Mage::app()->getRequest()->setPost($postArray);

        $instance = $this->_getInstance();
        $functionResult = $instance->getOrderPlaceRedirectUrl();

        $this->assertInternalType('string', $functionResult);
    }

    /**
     * @return array
     */
    public function getConfigDataProvider()
    {
        return array(
            'payment_action field' => array(
                'payment_action',
                Mage_Payment_Model_Method_Abstract::ACTION_AUTHORIZE
            ),
            'any other field' => array(
                'some_config_field',
                null
            )
        );
    }

    /**
     * @param $field
     * @param $expected
     *
     * @dataProvider getConfigDataProvider
     */
    public function testGetConfigData($field, $expected)
    {
        $instance = $this->_getInstance();
        $result = $instance->getConfigData($field);

        $this->assertEquals($expected, $result);
    }

    public function testCapture()
    {
        $mockPaymentInfo = $this->_getMockPayment();
        $instance = $this->_getInstance();

        $result = $instance->capture($mockPaymentInfo, 0);

        $this->assertInstanceOf('TIG_Buckaroo3Extended_Model_PaymentMethods_Klarna_PaymentMethod', $result);
    }
}

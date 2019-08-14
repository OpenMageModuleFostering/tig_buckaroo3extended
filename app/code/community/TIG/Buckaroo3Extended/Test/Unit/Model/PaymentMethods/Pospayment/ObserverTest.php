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
class TIG_Buckaroo3Extended_Test_Unit_Model_PaymentMethods_Pospayment_ObserverTest
    extends TIG_Buckaroo3Extended_Test_Framework_TIG_Test_TestCase
{
    /** @var null|TIG_Buckaroo3Extended_Model_PaymentMethods_Pospayment_Observer */
    protected $_instance = null;

    /**
     * @return TIG_Buckaroo3Extended_Model_PaymentMethods_Pospayment_Observer
     */
    protected function _getInstance()
    {
        if ($this->_instance === null) {
            $this->_instance = new TIG_Buckaroo3Extended_Model_PaymentMethods_Pospayment_Observer();
        }

        return $this->_instance;
    }

    /**
     * @return PHPUnit_Framework_MockObject_MockObject|Mage_Sales_Model_Order
     */
    private function getMockOrder()
    {
        $mockPayment = $this->getMockBuilder('Mage_Sales_Model_Order_Payment')
            ->setMethods(array('getMethod'))
            ->getMock();
        $mockPayment->expects($this->any())->method('getMethod')->willReturn('buckaroo3extended_pospayment');

        $mockOrder = $this->getMockBuilder('Mage_Sales_Model_Order')
            ->setMethods(array(
                'getPayment',
                'getPaymentMethodUsedForTransaction'
            ))
            ->getMock();

        $mockOrder->expects($this->any())->method('getPayment')->will($this->returnValue($mockPayment));
        $mockOrder->expects($this->any())->method('getPaymentMethodUsedForTransaction')->willReturn(false);

        return $mockOrder;
    }

    /**
     * @return PHPUnit_Framework_MockObject_MockObject|Varien_Event_Observer
     */
    private function getMockObserver()
    {
        $mockOrder = $this->getMockOrder();

        $mockRequest = $this->getMockBuilder('TIG_Buckaroo3Extended_Model_Request_Abstract')
            ->setMethods(array('getOrder'))
            ->getMock();
        $mockRequest->expects($this->any())->method('getOrder')->willReturn($mockOrder);

        $mockObserver = $this->getMockBuilder('Varien_Event_Observer')
            ->setMethods(array('getRequest', 'getOrder'))
            ->getMock();
        $mockObserver->expects($this->any())->method('getOrder')->willReturn($mockOrder);
        $mockObserver->expects($this->any())->method('getRequest')->willReturn($mockRequest);

        return $mockObserver;
    }

    public function testBuckaroo3extended_request_setmethod()
    {
        $mockObserver = $this->getMockObserver();

        $instance = $this->_getInstance();
        $result = $instance->buckaroo3extended_request_setmethod($mockObserver);
        $requestMethodResult = $mockObserver->getRequest()->getMethod();

        $this->assertInstanceOf('TIG_Buckaroo3Extended_Model_PaymentMethods_Pospayment_Observer', $result);
        $this->assertEquals('pospayment', $requestMethodResult);
    }

    public function testBuckaroo3extended_request_addservices()
    {
        $mockObserver = $this->getMockObserver();

        $instance = $this->_getInstance();
        $result = $instance->buckaroo3extended_request_addservices($mockObserver);
        $requestVarsResult = $mockObserver->getRequest()->getVars();

        $this->assertInstanceOf('TIG_Buckaroo3Extended_Model_PaymentMethods_Pospayment_Observer', $result);
        $this->assertEquals('Pay', $requestVarsResult['services']['pospayment']['action']);
    }

    /**
     * @return array
     */
    public function buckaroo3extended_request_addcustomvarsProvider()
    {
        return array(
            array(
                'abcd1234',
                array(
                    'customVars' => array(
                        'pospayment' => array(
                            'TerminalID' => 'abcd1234'
                        )
                    )
                )
            ),
            array(
                'ef56gh78',
                array(
                    'customVars' => array(
                        'pospayment' => array(
                            'TerminalID' => 'ef56gh78'
                        )
                    )
                )
            ),
            array(
                '9021ijkl',
                array(
                    'customVars' => array(
                        'pospayment' => array(
                            'TerminalID' => '9021ijkl'
                        )
                    )
                )
            ),
        );
    }

    /**
     * @param $terminalid
     * @param $expected
     *
     * @dataProvider buckaroo3extended_request_addcustomvarsProvider
     */
    public function testBuckaroo3extended_request_addcustomvars($terminalid, $expected)
    {
        $_SERVER['HTTP_POS_TERMINAL_ID'] = $terminalid;
        $mockObserver = $this->getMockObserver();

        $instance = $this->_getInstance();
        $result = $instance->buckaroo3extended_request_addcustomvars($mockObserver);
        $requestVarsResult = $mockObserver->getRequest()->getVars();

        $this->assertInstanceOf('TIG_Buckaroo3Extended_Model_PaymentMethods_Pospayment_Observer', $result);
        $this->assertEquals($expected, $requestVarsResult);
    }
}

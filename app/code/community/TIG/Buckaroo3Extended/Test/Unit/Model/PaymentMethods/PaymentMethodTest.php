<?php
/**
 *                  ___________       __            __
 *                  \__    ___/____ _/  |_ _____   |  |
 *                    |    |  /  _ \\   __\\__  \  |  |
 *                    |    | |  |_| ||  |   / __ \_|  |__
 *                    |____|  \____/ |__|  (____  /|____/
 *                                              \/
 *          ___          __                                   __
 *         |   |  ____ _/  |_   ____ _______   ____    ____ _/  |_
 *         |   | /    \\   __\_/ __ \\_  __ \ /    \ _/ __ \\   __\
 *         |   ||   |  \|  |  \  ___/ |  | \/|   |  \\  ___/ |  |
 *         |___||___|  /|__|   \_____>|__|   |___|  / \_____>|__|
 *                  \/                           \/
 *                  ________
 *                 /  _____/_______   ____   __ __ ______
 *                /   \  ___\_  __ \ /  _ \ |  |  \\____ \
 *                \    \_\  \|  | \/|  |_| ||  |  /|  |_| |
 *                 \______  /|__|    \____/ |____/ |   __/
 *                        \/                       |__|
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Creative Commons License.
 * It is available through the world-wide-web at this URL:
 * http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 * If you are unable to obtain it through the world-wide-web, please send an email
 * to servicedesk@totalinternetgroup.nl so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact servicedesk@totalinternetgroup.nl for more information.
 *
 * @copyright   Copyright (c) 2016 Total Internet Group B.V. (http://www.totalinternetgroup.nl)
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 */
class TIG_Buckaroo3Extended_Test_Unit_Model_PaymentMethods_PaymentMethodTest extends TIG_Buckaroo3Extended_Test_Framework_TIG_Test_TestCase
{
    protected $_code = 'unittest_payment';

    /** @var null|TIG_Buckaroo3Extended_Model_PaymentMethods_PaymentMethod */
    protected $_instance = null;

    public function setUp()
    {
        $configData = $this->testGetConfigDataProvider();

        foreach ($configData as $config) {
            $pathstart = 'payment';

            if ($config[0] == 'payment_action') {
                $pathstart = 'buckaroo';
            }

            Mage::app()->getStore()->setConfig($pathstart . '/' . $this->_code . '/' . $config[0], $config[1]);
        }
    }

    protected function _getInstance()
    {
        if ($this->_instance === null) {
            $this->_instance = new TIG_Buckaroo3Extended_Model_PaymentMethods_PaymentMethod();
            $this->setProperty('_code', $this->_code, $this->_instance);
        }

        return $this->_instance;
    }

    public function testGetConfigDataProvider()
    {
        return array(
            array(
                'active',
                '1'
            ),
            array(
                'payment_action',
                'order'
            ),
            array(
                'title',
                'Payment Title'
            ),
            array(
                'sort_order',
                '10'
            )
        );
    }

    /**
     * @param $field
     * @param $expected
     *
     * @dataProvider testGetConfigDataProvider
     */
    public function testGetConfigData($field, $expected)
    {
        $instance = $this->_getInstance();
        $result = $instance->getConfigData($field);

        $this->assertEquals($expected, $result);
    }
}

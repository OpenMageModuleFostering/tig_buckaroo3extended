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
 * to servicedesk@tig.nl so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact servicedesk@tig.nl for more information.
 *
 * @copyright   Copyright (c) 2014 Total Internet Group B.V. (http://www.tig.nl)
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 *
 * @method boolean hasPaymentMethod()
 * @method TIG_Buckaroo3Extended_Block_Adminhtml_Sales_Order_Creditmemo_Create_RefundFields setPaymentMethod(string $value)
 */
class TIG_Buckaroo3Extended_Block_Adminhtml_Sales_Order_Creditmemo_Create_RefundFields
    extends Mage_Adminhtml_Block_Abstract
{
    /**
     * Get the current creditmemo's payment method. Will return false if no payment or creditmemo instance can be found.
     *
     * @return string|false
     */
    public function getPaymentMethod()
    {
        if ($this->hasPaymentMethod()) {
            return $this->_getData('payment_method');
        }

        /**
         * @var Mage_Sales_Model_Order_Creditmemo $creditmemo
         */
        $creditmemo = Mage::registry('current_creditmemo');
        if (!$creditmemo) {
            return false;
        }

        /**
         * @var Mage_Sales_Model_Order_Payment $payment
         */
        $payment = Mage::getModel('sales/order_payment')->load($creditmemo->getOrderId(), 'parent_id');
        if (!$payment || !$payment->getId()) {
            return false;
        }

        $paymentMethod = $payment->getMethod();

        $this->setPaymentMethod($paymentMethod);
        return $paymentMethod;
    }

    /**
     * Check if a payment method can be found before rendering the template.
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (!$this->getPaymentMethod()) {
            return '';
        }

        return parent::_toHtml();
    }
}
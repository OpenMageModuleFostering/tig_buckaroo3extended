<?php
class TIG_Buckaroo3Extended_CheckoutController extends Mage_Core_Controller_Front_Action
{
    public function checkoutAction()
    {
        /**
         * @var TIG_Buckaroo3Extended_Model_Request_Abstract $request
         */
        $request = Mage::getModel('buckaroo3extended/request_abstract');
        $request->sendRequest();
    }

    public function saveDataAction()
    {
        $data = $this->getRequest()->getPost();

        if (!is_array($data) || !isset($data['name']) || !isset($data['value'])) {
            return;
        }

        $name = $data['name'];
        $value = $data['value'];

        $session = Mage::getSingleton('checkout/session');
        $session->setData($name, $value);

        return;
    }
}
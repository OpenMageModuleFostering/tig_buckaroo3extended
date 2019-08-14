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

    public function pospaymentPendingAction()
    {
        $this->loadLayout();
        $this->getLayout();
        $this->renderLayout();
    }

    public function pospaymentCheckStateAction()
    {
        $response = array(
            'status' => 'new',
            'returnUrl' => null
        );

        /** @var TIG_Buckaroo3Extended_Model_Response_Abstract $responseHandler */
        $responseHandler = Mage::getModel('buckaroo3extended/response_abstract');

        /** @var Mage_Sales_Model_Order $order */
        $order = $responseHandler->getOrder();
        $response['status'] = $order->getState();

        switch ($response['status']) {
            case 'processing':
                $responseHandler->emptyCart();
                Mage::getSingleton('core/session')->addSuccess(
                    Mage::helper('buckaroo3extended')->__('Your order has been placed succesfully.')
                );
                $response['returnUrl'] = $this->getSuccessUrl($order->getStoreId());
                break;
            case 'canceled':
                $responseHandler->restoreQuote();

                Mage::getSingleton('core/session')->addError(
                    Mage::helper('buckaroo3extended')->__(Mage::getStoreConfig(
                        $responseHandler::BUCK_RESPONSE_DEFAUL_MESSAGE,
                        $order->getStoreId()
                    ))
                );

                $response['returnUrl'] = $this->getFailedUrl($order->getStoreId());
                break;
        }

        echo json_encode($response);
        exit;
    }

    /**
     * @param $storeId
     *
     * @return string
     */
    protected function getSuccessUrl($storeId)
    {
        $returnLocation = Mage::getStoreConfig('buckaroo/buckaroo3extended_advanced/success_redirect', $storeId);
        $succesUrl = Mage::getUrl($returnLocation, array('_secure' => true));

        return $succesUrl;
    }

    /**
     * @param $storeId
     *
     * @return string
     */
    protected function getFailedUrl($storeId)
    {
        $returnLocation = Mage::getStoreConfig('buckaroo/buckaroo3extended_advanced/failure_redirect', $storeId);
        $failedUrl = Mage::getUrl($returnLocation, array('_secure' => true));

        return $failedUrl;
    }
}

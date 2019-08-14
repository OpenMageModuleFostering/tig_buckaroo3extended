<?php
class TIG_Buckaroo3Extended_Model_PaymentMethods_Directdebit_PaymentMethod extends TIG_Buckaroo3Extended_Model_PaymentMethods_PaymentMethod
{
    public $allowedCurrencies = array(
        'EUR',
    );

    protected $_code = 'buckaroo3extended_directdebit';

    protected $_formBlockType = 'buckaroo3extended/paymentMethods_directdebit_checkout_form';
    protected $_infoBlockType = 'buckaroo3extended/paymentMethods_directdebit_info';

    public function getOrderPlaceRedirectUrl()
    {
        $session = Mage::getSingleton('checkout/session');

        if(isset($_POST['payment']))
        {
            $accountNumber = $_POST['payment']['account_number'];
            $session->setData('additionalFields', array(
                    'accountOwner'  => $_POST['payment']['account_owner'],
                    'accountNumber' => $this->filterAccount($accountNumber),
                    'bankNumber'    => $_POST['payment']['bank_number'],
                )
            );
        }

        return parent::getOrderPlaceRedirectUrl();
    }

    public function saveAdditionalData($response)
    {
        $data = array();
        try
        {
            foreach($response->Services->Service->ResponseParameter as $responseParameter)
            {
                if($responseParameter->Name == 'MandateReference')
                {
                    $data['mandate_reference'] = $responseParameter->_;
                }
            }
        }
        catch(Exception $e)
        {
            Mage::log('No response parameters found in response:');
            Mage::log($response);
        }

        if(!empty($data))
        {
            $this->getInfoInstance()
                 ->setAdditionalData(serialize($data))
                 ->save();
        }

        return $this;
    }
}

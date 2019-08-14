<?php
class TIG_Buckaroo3Extended_Model_Sources_AcceptgiroDirectdebit
{
    public function toOptionArray()
    {
    	$array = array(
    		 array('value' => 'afterpayacceptgiro', 'label' => Mage::helper('buckaroo3extended')->__('Acceptgiro')),
    		 array('value' => 'afterpaydigiaccept', 'label' => Mage::helper('buckaroo3extended')->__('Digiaccept')),
    	);
    	return $array;
    }
}
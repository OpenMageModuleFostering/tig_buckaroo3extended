<?php

class TIG_Buckaroo3Extended_Model_Sources_BusinessToBusiness
{
    public function toOptionArray()
    {
        $array = array(
            array('value' => '1', 'label' => Mage::helper('buckaroo3extended')->__('B2C')),
            array('value' => '2', 'label' => Mage::helper('buckaroo3extended')->__('B2B')),
            array('value' => '3', 'label' => Mage::helper('buckaroo3extended')->__('Both')),
        );
        return $array;
    }
}
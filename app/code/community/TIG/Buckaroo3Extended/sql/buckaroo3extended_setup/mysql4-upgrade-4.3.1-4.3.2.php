<?php
$installer = $this;

$installer->startSetup();
$status = array(
		'status' => 'buckaroo_giftcard',
		'label' => 'Buckaroo (giftcard)', 
		'is_new' => 1, 
		'form_key' => '', 
		'store_labels' => array(), 
		'state' => 'new'
);

$_stat = Mage::getModel('sales/order_status')->load('buckaroo_giftcard');
	
/* Add Status */
if ($_stat->getStatus()) {
    return;
}

$_stat->setData($status)->setStatus('buckaroo_giftcard');

try {
    $_stat->save();
} catch (Mage_Core_Exception $e) {  } 

/* Assign Status to State */
if ($_stat && $_stat->getStatus()) {
    try {
        $_stat->assignState($status['state'], false);
    } catch (Mage_Core_Exception $e) {  } 
    catch (Exception $e) {  }
}

$installer->endSetup();

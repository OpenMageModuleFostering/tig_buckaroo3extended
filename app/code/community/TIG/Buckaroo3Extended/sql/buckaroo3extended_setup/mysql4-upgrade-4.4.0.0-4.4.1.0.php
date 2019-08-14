<?php
$installer = $this;

$installer->startSetup();
$conn = $installer->getConnection();
    
$conn->addColumn($installer->getTable('sales/order'), 'buckaroo_service_version_used', 'smallint(5) null');
    
$installer->endSetup();

<?php 
$installer = $this;

$installer->startSetup();

$salesFlatOrderTableName = $installer->getTable('sales_flat_order');
$salesOrderTableName = $installer->getTable('sales_order');

$sql = <<<SQL
ALTER TABLE `{$salesFlatOrderTableName}`
ADD `transaction_key` varchar(50) NULL
SQL;

$sql2 = <<<SQL2
ALTER TABLE `{$salesOrderTableName}`
ADD `transaction_key` varchar(50) NULL
SQL2;

try {
    $installer->run($sql);
} catch (Exception $e) {
    try {
        $installer->run($sql2);
    } catch (Exception $e) {
        
    }
}

$installer->endSetup();
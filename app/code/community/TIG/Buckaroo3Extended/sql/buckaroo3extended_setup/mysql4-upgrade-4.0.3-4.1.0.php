<?php
$installer = $this;

$installer->startSetup();

$salesFlatCreditMemoTableName = $installer->getTable('sales_flat_creditmemo');
$salesFlatOrderTableName = $installer->getTable('sales_flat_order');
$certificatesTable = $installer->getTable('buckaroo_certificates');

$sql = <<<SQL
ALTER TABLE `{$salesFlatCreditMemoTableName}`
ADD `transaction_key` varchar(50) NULL
SQL;

$sql2 = <<<SQL2
ALTER TABLE `{$salesFlatOrderTableName}`
ADD `payment_method_used_for_transaction` varchar(50) NULL
SQL2;

$sql3 = <<<SQL3
ALTER TABLE `{$salesFlatOrderTableName}`
ADD `currency_code_used_for_transaction` varchar(3) NULL
SQL3;

$sql4 = <<<SQL4
CREATE TABLE `{$certificatesTable}`
(
    `certificate_id` INT(7) NOT NULL auto_increment,
    `certificate` TEXT NOT NULL,
    `certificate_name` VARCHAR(15) NOT NULL,
    `upload_date` DATETIME NOT NULL,
    PRIMARY KEY  (`certificate_id`),
    UNIQUE (`certificate_name`)
)

SQL4;

try {
    $installer->run($sql);
    $installer->run($sql2);
    $installer->run($sql3);
    $installer->run($sql4);
} catch (Exception $e) {

}

$installer->endSetup();
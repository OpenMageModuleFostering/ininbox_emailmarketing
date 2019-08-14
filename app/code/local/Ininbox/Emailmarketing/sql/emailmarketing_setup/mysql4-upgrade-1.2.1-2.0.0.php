<?php
/**
 * INinbox Email Marketing
 * @category    INinbox
 * @package     INinbox_Emailmarketing
**/
$installer = $this;
$installer->startSetup();

$installer->run("
	ALTER TABLE  `" . $this->getTable('sales/quote') . "` ADD `ininbox_is_abandoned` BOOLEAN DEFAULT 0;	
");

$installer->endSetup();
?>

<?php
/**
 * INinbox Email Marketing
 * @category    INinbox
 * @package     INinbox_Emailmarketing
**/
$installer = $this;
$installer->startSetup();

$DefaultValues = array(array('customer_attributes' => 'email', 'ininbox_custom_fields' => 'Email'));
$DefaultValues = serialize($DefaultValues);
Mage::getConfig()->saveConfig('ininbox_emailmarketing_options/field_mapping/field', $DefaultValues);
Mage::getConfig()->reinit();
Mage::app()->reinitStores();

$installer->endSetup();
?>

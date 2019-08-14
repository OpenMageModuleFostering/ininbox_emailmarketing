<?php
/**
 * INinbox Email Marketing
 * @category    INinbox
 * @package     INinbox_Emailmarketing
**/
$installer = $this;
$installer->startSetup();

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');

$entityTypeId     = $setup->getEntityTypeId('customer');
$attributeSetId   = $setup->getDefaultAttributeSetId($entityTypeId);
$attributeGroupId = $setup->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);

$setup->addAttribute('customer', 'ininbox_contact_id', array(
    'input'         => 'text',
    'type'          => 'varchar',
    'label'         => 'INinbox Contact ID',
    'visible'       => 0,
    'required'      => 0,
    'user_defined' => 1,
));

$setup->addAttributeToGroup(
	$entityTypeId,
	$attributeSetId,
	$attributeGroupId,
	'ininbox_contact_id',
	'999'  //sort_order
);

$oAttribute = Mage::getSingleton('eav/config')->getAttribute('customer', 'ininbox_contact_id');
$oAttribute->setData('used_in_forms', array('customer_account_create','customer_account_edit'));
$oAttribute->save();

$installer->endSetup();
?>

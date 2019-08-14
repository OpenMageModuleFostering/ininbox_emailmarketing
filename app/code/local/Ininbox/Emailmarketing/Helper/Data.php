<?php
/**
 * INinbox Email Marketing
 * @category    INinbox
 * @package     INinbox_Emailmarketing
**/
class Ininbox_Emailmarketing_Helper_Data extends Mage_Core_Helper_Abstract
{
	/**
	 * Checkout weather extension is enabled or not
	 * 
	 * @return - true or false on extension is enabled or not.
	 */
	public function isEnabled()
	{
		return Mage::getStoreConfig('ininbox_emailmarketing_options/general/enabled', Mage::app()->getStore());
	}

	/**
	 * Use to get configuration values
	 * 
	 * @Params
	 * $group - define group of configuration
	 * $field - define variable for which you want to get the value.
	 * 
	 * @return - configuration value
	 */
	public function getConfig($group, $field)
	{
		return Mage::getStoreConfig('ininbox_emailmarketing_options/' . $group . '/' . $field, Mage::app()->getStore());
	}
}
?>

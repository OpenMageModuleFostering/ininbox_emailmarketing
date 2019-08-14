<?php
/**
 * INinbox Email Marketing
 * @category    INinbox
 * @package     INinbox_Emailmarketing
**/
class Ininbox_Emailmarketing_Model_Ininbox_Customfield extends Mage_Core_Model_Abstract {
	
	protected function _construct() {
		$this->_init('emailmarketing/ininbox_customfield');
	}
	
	/**
	 * use to get the list of custom fields from ininbox
	 * 
	 * @return list of custom fields in json format
	 */
	public function getList()
	{
		return Mage::getModel('emailmarketing/ininbox_base')->makeCall('customfields/list', 'PageSize=500');
	}
	
	/**
	 * use to get the list of custom fields from ininbox
	 * 
	 * @return list of custom fields in json format
	 */
	public function getSystemDefinedList()
	{
		return Mage::getModel('emailmarketing/ininbox_base')->makeCall('customfields/systemdefined', 'PageSize=500');
	}
}
?>

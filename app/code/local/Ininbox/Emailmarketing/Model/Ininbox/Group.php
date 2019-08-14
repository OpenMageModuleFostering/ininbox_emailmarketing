<?php
/**
 * INinbox Email Marketing
 * @category    INinbox
 * @package     INinbox_Emailmarketing
**/
class Ininbox_Emailmarketing_Model_Ininbox_Group extends Mage_Core_Model_Abstract {
	
	protected function _construct() {
		$this->_init('emailmarketing/ininbox_group');
	}
	
	/**
	 * use to get the list of lists from ininbox
	 * 
	 * @return list of lists in json format
	 */
	public function getList()
	{
		return Mage::getModel('emailmarketing/ininbox_base')->makeCall('lists/list', 'PageSize=500');	
	}
}
?>

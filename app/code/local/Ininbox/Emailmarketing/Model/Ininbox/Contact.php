<?php
/**
 * INinbox Email Marketing
 * @category    INinbox
 * @package     INinbox_Emailmarketing
**/
class Ininbox_Emailmarketing_Model_Ininbox_Contact extends Mage_Core_Model_Abstract {
	
	protected function _construct() {
		$this->_init('emailmarketing/ininbox_contact');
	}
	
	/**
	 * use to get the list of contacts from ininbox
	 * 
	 * @return list of contact in json format
	 */
	public function getList()
	{
		return Mage::getModel('emailmarketing/ininbox_base')
			->makeCall('contacts/list', 'PageSize=500');	
	}
	
	/**
	 * use to add or update contact in ininbox
	 * 
	 * @return response for add or edit details
	 */
	public function add($params)
	{
		return Mage::getModel('emailmarketing/ininbox_base')
			->makeCall('contacts/create', '', 'json', $params);
	}
}
?>

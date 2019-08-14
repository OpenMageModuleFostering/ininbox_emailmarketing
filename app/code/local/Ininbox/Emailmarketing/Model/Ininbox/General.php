<?php
/**
 * INinbox Email Marketing
 * @category    INinbox
 * @package     INinbox_Emailmarketing
**/
class Ininbox_Emailmarketing_Model_Ininbox_General extends Mage_Core_Model_Abstract {
	
	protected function _construct() {
		$this->_init('emailmarketing/ininbox_general');
	}
	
	/**
	 * use to get the list of lists from ininbox
	 * 
	 * @return list of lists in json format
	 */
	public function getSystemDateTime($api_url, $api_key)
	{		
        return Mage::getModel('emailmarketing/ininbox_base')->checkCall($api_url, $api_key, 'general/systemdate');	
	}
}
?>

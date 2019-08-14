<?php
/**
 * INinbox Email Marketing
 * @category    INinbox
 * @package     INinbox_Emailmarketing
**/
class Ininbox_Emailmarketing_Model_System_Config_Source_Customer_Attribute
{
	protected function _construct() {

		$this->_init('emailmarketing/system_config_source_customer_attribute');
	
	}
    
    /**
	 * use to get the list of attributes for customer
	 * 
	 * @return list of customer attributes
	 */
	public function toOptionArray()
    {
		/*
    	$attributes = Mage::getSingleton('customer/convert_parser_customer')->getExternalAttributes();
    	
    	$result = $attributes;
    	*/
    	
    	$attributes = Mage::getModel('customer/entity_attribute_collection');
        
        $result = array();
        
        foreach ($attributes as $attribute) {
            
            if (($label = $attribute->getFrontendLabel()))
                $result[$attribute->getAttributeCode()] = $label;
        
        }        
        
        return $result;
    }
}
?>

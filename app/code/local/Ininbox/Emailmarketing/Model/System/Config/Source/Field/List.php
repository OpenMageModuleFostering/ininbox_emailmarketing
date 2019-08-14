<?php
/**
 * INinbox Email Marketing
 * @category    INinbox
 * @package     INinbox_Emailmarketing
**/
class Ininbox_Emailmarketing_Model_System_Config_Source_Field_List
{
	/*
	protected $_predefinedCustomFields = array
		(
			'FirstName' => 'first_name',
			'LastName' => 'last_name',
			'Email' => 'email',
			'Gender' => 'gender',
			'Company' => 'company',
			'Address' => 'address'
			//~ 'CountryCode' => 'country',
			//~ 'StateCode' => 'state',
			//~ 'City' => 'city',
			//~ 'Zip' => 'zip_code',
			//~ 'HomePhone' => 'home_phone',
			//~ 'MobilePhone' => 'mobile_phone',
			//~ 'WorkPhone' => 'work_phone',
			//~ 'Fax' => 'fax'
		);
	*/	
		 
	/**
	 * use to get the list of custome fields
	 * 
	 * @return list of custom fields
	 */
    public function toOptionArray()
    {
		//$result = $this->_predefinedCustomFields;		
		
		$system_defined = Mage::getModel('emailmarketing/ininbox_customfield')->getSystemDefinedList();
		
		if(!is_null($system_defined))
        {
			foreach($system_defined['SystemFields'] as $row)			
				$result[$row['FieldName']] = Mage::helper('adminhtml')->__($row['FieldLabel']);				
		}
		
		$custom_data = Mage::getModel('emailmarketing/ininbox_customfield')->getList();
        
        if(!is_null($custom_data))
        {
			foreach($custom_data['Results'] as $row)
				$result[$row['FieldName']] = Mage::helper('adminhtml')->__($row['FieldName']);				
		}		
        
        return $result;
    }

	/**
	 * use to get the list of predefined custom fields
	 * 
	 * @return list of predefined custom fields
	 */
	public function getPredefinedCustomList()
	{
		$result = array();
		
		$system_defined = Mage::getModel('emailmarketing/ininbox_customfield')->getSystemDefinedList();
		
		if(!is_null($system_defined))
        {
			foreach($system_defined['SystemFields'] as $row)			
				$result[$row['FieldName']] = Mage::helper('adminhtml')->__($row['FieldLabel']);				
		}		
		
		return $result;
	}	
}

?>

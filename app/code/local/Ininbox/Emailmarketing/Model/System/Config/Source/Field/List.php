<?php
/**
 * INinbox Email Marketing
 * @category    INinbox
 * @package     INinbox_Emailmarketing
**/
class Ininbox_Emailmarketing_Model_System_Config_Source_Field_List
{
	protected $_predefinedCustomFields = array
		(
			'FirstName' => 'first_name',
			'LastName' => 'last_name',
			'Email' => 'email',
			'Gender' => 'gender',
			'Company' => 'company',
			'Address' => 'address'
			/*'CountryCode' => 'country',
			'StateCode' => 'state',
			'City' => 'city',
			'Zip' => 'zip_code',
			'HomePhone' => 'home_phone',
			'MobilePhone' => 'mobile_phone',
			'WorkPhone' => 'work_phone',
			'Fax' => 'fax'*/
		);
		
	/**
	 * use to get the list of custome fields
	 * 
	 * @return list of custom fields
	 */
    public function toOptionArray()
    {
		$result = $this->_predefinedCustomFields;
		
		$data = Mage::getModel('emailmarketing/ininbox_customfield')->getList();		
        
        if(!is_null($data))
        {
			foreach($data['Results'] as $row)
			{
				$result[$row['FieldName']]= $row['FieldName'];
			}
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
		return $this->_predefinedCustomFields;				
	}
}

?>

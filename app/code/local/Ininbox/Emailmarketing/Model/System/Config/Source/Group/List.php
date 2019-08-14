<?php
/**
 * INinbox Email Marketing
 * @category    INinbox
 * @package     INinbox_Emailmarketing
**/
class Ininbox_Emailmarketing_Model_System_Config_Source_Group_List
{
	/**
	 * use to get the list of lists from ininbox
	 * 
	 * @return list of contact
	 */
    public function toOptionArray()
    {
		$result = array();
		
		$data = Mage::getModel('emailmarketing/ininbox_group')->getList();		
        
        if(!is_null($data))
        {
			foreach($data['Results'] as $row)			
				$result[] = array('value' => $row['ListID'], 'label'=>Mage::helper('adminhtml')->__($row['Title']));			
		}
        
        return $result;
    }
    
	/**
	 * Get options in "key-value" format
	 *
	 * @return array
	 */
	public function toArray()
	{
		$result = array();
		
		$data = Mage::getModel('emailmarketing/ininbox_group')->getList();		
        
        if(!is_null($data))
        {
			foreach($data['Results'] as $row)			
				$result[$row['ListID']] = $row['Title'];			
		}
        
        return $result;
	}
}

?>

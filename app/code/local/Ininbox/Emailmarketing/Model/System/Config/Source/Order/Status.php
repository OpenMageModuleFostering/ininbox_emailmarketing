<?php
/**
 * INinbox Email Marketing
 * @category    INinbox
 * @package     INinbox_Emailmarketing
**/
class Ininbox_Emailmarketing_Model_System_Config_Source_Order_Status
{
	/**
	 * use to get the list of order status in magento
	 * 
	 * @return list of contact
	 */
    public function toOptionArray()
    {
		$result = array();
		
		$data = Mage::getModel('sales/order_status')->getResourceCollection()->getData();
               
        if(!is_null($data))
        {
			foreach($data as $row)			
				$result[] = array('value' => $row['status'], 'label'=>Mage::helper('adminhtml')->__($row['label']));			
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
		
		$data = Mage::getModel('sales/order_status')->getResourceCollection()->getData();
        
        if(!is_null($data))
        {
			foreach($data as $row)
				$result[$row['status']] = $row['label'];			
		}
        
        return $result;
	}
}

?>

<?php
/**
 * INinbox Email Marketing
 * @category    INinbox
 * @package     INinbox_Emailmarketing
**/
class Ininbox_Emailmarketing_Block_Adminhtml_Customer_Grid extends Mage_Adminhtml_Block_Customer_Grid
{
	protected function _prepareMassaction()
    {
		parent::_prepareMassaction();
         
        // Check weather extension is enabled or not.
        if(Mage::helper('emailmarketing')->isEnabled())
		{
			// Check weather feature is enabled or not and get the INinbox list id for edit.
			$isIninboxSendCustomerEnabled = Mage::helper('emailmarketing')->getConfig($group = 'customer_settings', $field = 'send_customer');
			$ininboxCustomerGroupList = Mage::helper('emailmarketing')->getConfig($group = 'customer_settings', $field = 'customer_group_list');
			
			if($isIninboxSendCustomerEnabled && !is_null($ininboxCustomerGroupList))
			{				
				$this->getMassactionBlock()->addItem(
					'emailmarketing',
					array('label' => $this->__('Add to INinbox List'),
						  'url'   => $this->getUrl('emailmarketing/adminhtml_massaction/customer')
					)
				);
			}
		}
    }
}
?>

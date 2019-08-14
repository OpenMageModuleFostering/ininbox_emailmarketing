<?php
/**
 * INinbox Email Marketing
 * @category    INinbox
 * @package     INinbox_Emailmarketing
**/
class Ininbox_Emailmarketing_Block_Adminhtml_Sales_Order_Grid extends Mage_Adminhtml_Block_Sales_Order_Grid
{
	protected function _prepareMassaction()
    {
		parent::_prepareMassaction();
         
        // Checkout extension is enabled or not.
        if(Mage::helper('emailmarketing')->isEnabled())
		{
			// Check weather feature is enabled or not and get the INinbox list id for edit.
			$isIninboxSendOrderEnabled = Mage::helper('emailmarketing')->getConfig($group = 'order_settings', $field = 'send_order');
			$ininboxOrderGroupList = Mage::helper('emailmarketing')->getConfig($group = 'order_settings', $field = 'order_group_list');
			
			if($isIninboxSendOrderEnabled && !is_null($ininboxOrderGroupList))
			{
				$this->getMassactionBlock()->addItem(
					'emailmarketing',
					array('label' => $this->__('Add to INinbox List'),
						  'url'   => $this->getUrl('emailmarketing/adminhtml_massaction/salesorder') //this should be the url where there will be mass operation
					)
				);
			}
		}
    }
}
?>

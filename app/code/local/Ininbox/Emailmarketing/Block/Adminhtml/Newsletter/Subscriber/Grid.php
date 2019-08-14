<?php
/**
 * INinbox Email Marketing
 * @category    INinbox
 * @package     INinbox_Emailmarketing
**/
class Ininbox_Emailmarketing_Block_Adminhtml_Newsletter_Subscriber_Grid extends Mage_Adminhtml_Block_Newsletter_Subscriber_Grid
{
	protected function _prepareMassaction()
    {
		parent::_prepareMassaction();
         
        // Checkout extension is enabled or not.
        if(Mage::helper('emailmarketing')->isEnabled())
		{
			// Check weather feature is enabled or not and get the INinbox list id for edit.
			$isIninboxSendSubscriberEnabled = Mage::helper('emailmarketing')->getConfig($group = 'newsletter_settings', $field = 'send_subscriber');
			$ininboxSubscriberGroupList = Mage::helper('emailmarketing')->getConfig($group = 'newsletter_settings', $field = 'subscriber_group_list');
						
			if($isIninboxSendSubscriberEnabled && !is_null($ininboxSubscriberGroupList))
			{			
				$this->getMassactionBlock()->addItem(
					'emailmarketing',
					array('label' => $this->__('Add to INinbox List'),
						  'url'   => $this->getUrl('emailmarketing/adminhtml_massaction/newsletter') //this should be the url where there will be mass operation
					)
				);
			}
		}
    }
}
?>

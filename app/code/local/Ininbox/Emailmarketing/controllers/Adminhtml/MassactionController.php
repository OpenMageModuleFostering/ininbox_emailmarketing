<?php
/**
 * INinbox Email Marketing
 * @category    INinbox
 * @package     INinbox_Emailmarketing
**/
class Ininbox_Emailmarketing_Adminhtml_MassactionController extends Mage_Adminhtml_Controller_Action {
	
	/** 
	 * Action on mass action for customer to Add in INinbox list
	 */
	public function customerAction()
	{		
		$customersIds = $this->getRequest()->getParam('customer');		
		// check weather customerids is array or not
        if(!is_array($customersIds)) {
             Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select customer(s).'));			
        } else {
            try {			
				// Check weather extension is enabled or not.	
				if(Mage::helper('emailmarketing')->isEnabled())
				{
					// Check weather feature is enabled or not and get the INinbox list id for edit.
					$isIninboxSendCustomerEnabled = Mage::helper('emailmarketing')->getConfig($group = 'customer_settings', $field = 'send_customer');
					$ininboxCustomerGroupList = Mage::helper('emailmarketing')->getConfig($group = 'customer_settings', $field = 'customer_group_list');					
					
					if($isIninboxSendCustomerEnabled && !is_null($ininboxCustomerGroupList))
					{
						$message = Mage::getModel('emailmarketing/observer')->importIninboxContact($customersIds, $ininboxCustomerGroupList);
						
						if(isset($message) && $message != '')						
							Mage::getSingleton('adminhtml/session')->addError($message);						
						else
						{						
							Mage::getSingleton('adminhtml/session')->addSuccess(
								Mage::helper('adminhtml')->__('Total of %d record(s) were added.', count($customersIds))
							);
						}
					}
				}                
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }            
        }
        $this->_redirect('adminhtml/customer/index/');
		return;  
	}
	
	/** 
	 * Action on mass action in newsletter group to add customer to INinbox list
	 */
	public function newsletterAction()
	{
		$subscribersIds = $this->getRequest()->getParam('subscriber');		
        if (!is_array($subscribersIds)) {
             Mage::getSingleton('adminhtml/session')->addError(Mage::helper('newsletter')->__('Please select subscriber(s)'));
        }
        else {
            try {
				if(Mage::helper('emailmarketing')->isEnabled())
				{
					$isIninboxSendSubscriberEnabled = Mage::helper('emailmarketing')->getConfig($group = 'newsletter_settings', $field = 'send_subscriber');
					$ininboxSubscriberGroupList = Mage::helper('emailmarketing')->getConfig($group = 'newsletter_settings', $field = 'subscriber_group_list');					
					
					if($isIninboxSendSubscriberEnabled && !is_null($ininboxSubscriberGroupList))
					{						
						$message = Mage::getModel('emailmarketing/observer')->importNewsletterContactGroup($subscribersIds, $ininboxSubscriberGroupList);
						
						if(isset($message) && $message != '')						
							Mage::getSingleton('adminhtml/session')->addError($message);						
						else
						{
							Mage::getSingleton('adminhtml/session')->addSuccess(
								Mage::helper('adminhtml')->__('Total of %d record(s) were added.', count($subscribersIds))
							);
						}
					}
                }                
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }		
		$this->_redirect('adminhtml/newsletter_subscriber/index/');
		return;
	}
	
	/** 
	 * Action on mass action in sales order to add customer to INinbox list
	 */
	public function salesorderAction()
	{
		$orderIds = $this->getRequest()->getPost('order_ids', array());
        if (!is_array($orderIds)) {
             Mage::getSingleton('adminhtml/session')->addError(Mage::helper('newsletter')->__('Please select order(s)'));
        }
        else {
			try {
				if(Mage::helper('emailmarketing')->isEnabled())
				{
					$isIninboxSendOrderEnabled = Mage::helper('emailmarketing')->getConfig($group = 'order_settings', $field = 'send_order');
					$ininboxOrderGroupList = Mage::helper('emailmarketing')->getConfig($group = 'order_settings', $field = 'order_group_list');					
					
					if($isIninboxSendOrderEnabled && !is_null($ininboxOrderGroupList))
					{						
						$message = Mage::getModel('emailmarketing/observer')->importIninboxContactForSales($orderIds, $ininboxOrderGroupList);
						
						if(isset($message) && $message != '')						
							Mage::getSingleton('adminhtml/session')->addError($message);						
						else
						{
							Mage::getSingleton('adminhtml/session')->addSuccess(
								Mage::helper('adminhtml')->__('Total of %d record(s) were added.', count($orderIds))
							);
						}
					}
				}
			} catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
		}
		
        $this->_redirect('adminhtml/sales_order/index/');
		return;
	}
}

?>

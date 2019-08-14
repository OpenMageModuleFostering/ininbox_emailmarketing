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
					
					// get the settings for mass action miscellaneous section to add customer on mass action
					$ininboxResubscriber = Mage::helper('emailmarketing')->getConfig($group = 'customer_massaction_settings', $field = 'update_subscriber') ? true : false;
					$ininboxSendConfirmationEmail = Mage::helper('emailmarketing')->getConfig($group = 'customer_massaction_settings', $field = 'confirm_email') ? true : false;
					$ininboxAddContactToAutoresponderCycle = Mage::helper('emailmarketing')->getConfig($group = 'customer_massaction_settings', $field = 'send_autoresponder') ? true : false;
					
					if($isIninboxSendCustomerEnabled && !is_null($ininboxCustomerGroupList))
					{
						foreach ($customersIds as $customerId) {
							$currentCustomer = Mage::getModel('customer/customer')->load($customerId);
							Mage::getModel('emailmarketing/observer')->createIninboxContact($currentCustomer, $ininboxCustomerGroupList, $ininboxResubscriber, $ininboxSendConfirmationEmail, $ininboxAddContactToAutoresponderCycle);
						}
						Mage::getSingleton('adminhtml/session')->addSuccess(
							Mage::helper('adminhtml')->__('Total of %d record(s) were added.', count($customersIds))
						);
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
					
					$ininboxResubscriber = Mage::helper('emailmarketing')->getConfig($group = 'newsletter_massaction_settings', $field = 'update_subscriber') ? true : false;
					$ininboxSendConfirmationEmail = Mage::helper('emailmarketing')->getConfig($group = 'newsletter_massaction_settings', $field = 'confirm_email') ? true : false;
					$ininboxAddContactToAutoresponderCycle = Mage::helper('emailmarketing')->getConfig($group = 'newsletter_massaction_settings', $field = 'send_autoresponder') ? true : false;
					
					if($isIninboxSendSubscriberEnabled && !is_null($ininboxSubscriberGroupList))
					{
						foreach ($subscribersIds as $subscriberId) {
							$subscriber = Mage::getModel('newsletter/subscriber')->load($subscriberId);
							$subscribeEmail = $subscriber->getData('subscriber_email');
							Mage::getModel('emailmarketing/observer')->updateIninboxContactGroup($subscribeEmail, $ininboxSubscriberGroupList, $ininboxResubscriber, $ininboxSendConfirmationEmail, $ininboxAddContactToAutoresponderCycle);
						}
						Mage::getSingleton('adminhtml/session')->addSuccess(
							Mage::helper('adminhtml')->__('Total of %d record(s) were added.', count($subscribersIds))
						);
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
					
					$ininboxResubscriber = Mage::helper('emailmarketing')->getConfig($group = 'order_massaction_settings', $field = 'update_subscriber') ? true : false;
					$ininboxSendConfirmationEmail = Mage::helper('emailmarketing')->getConfig($group = 'order_massaction_settings', $field = 'confirm_email') ? true : false;
					$ininboxAddContactToAutoresponderCycle = Mage::helper('emailmarketing')->getConfig($group = 'order_massaction_settings', $field = 'send_autoresponder') ? true : false;
					
					if($isIninboxSendOrderEnabled && !is_null($ininboxOrderGroupList))
					{
						foreach ($orderIds as $orderId) {
							$order = Mage::getModel('sales/order')->load($orderId);
							Mage::getModel('emailmarketing/observer')->createIninboxContactForSales($order, $ininboxOrderGroupList, $ininboxResubscriber, $ininboxSendConfirmationEmail, $ininboxAddContactToAutoresponderCycle);
						}						
						Mage::getSingleton('adminhtml/session')->addSuccess(
							Mage::helper('adminhtml')->__('Total of %d record(s) were added.', count($orderIds))
						);
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

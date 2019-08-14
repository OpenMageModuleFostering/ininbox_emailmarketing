<?php 
class Ininbox_Emailmarketing_Adminhtml_IndexController extends Mage_Adminhtml_Controller_Action
{
	function indexAction()
	{		
		$ininboxAbandonedCartsEnabled = Mage::helper('emailmarketing')->getConfig($group = 'abandoned_carts_settings', $field = 'add_to_list_enabled');
		$ininboxAbandonedCartsList = Mage::helper('emailmarketing')->getConfig($group = 'abandoned_carts_settings', $field = 'abandoned_list');
		$ininboxAbandonedCartsTime = Mage::helper('emailmarketing')->getConfig($group = 'abandoned_carts_settings', $field = 'abandoned_time');
		$ininboxAbandonedCartsCaptureRegistered = Mage::helper('emailmarketing')->getConfig($group = 'abandoned_carts_settings', $field = 'register_capture');		
		
		if($ininboxAbandonedCartsEnabled)
		{
			$adapter = Mage::getSingleton('core/resource')->getConnection('sales_read');
			$minutes = $ininboxAbandonedCartsTime;
			$from = $adapter->getDateSubSql(
				$adapter->quote(now()), 
				$minutes, 
				Varien_Db_Adapter_Interface::INTERVAL_MINUTE
			);
			$quotes = Mage::getResourceModel('sales/quote_collection')
				->addFieldToFilter('reserved_order_id', array('null' => true))
				->addFieldToFilter('customer_email', array('neq' => null))
				->addFieldToFilter('updated_at', array('to' => $from));
			
			if(!$ininboxAbandonedCartsCaptureRegistered)
				$quotes->addFieldToFilter('customer_is_guest', array('eq' => true));
							
			if($quotes->count() > 0)
			{
				foreach($quotes as $quote)
					Mage::getModel('emailmarketing/observer')->addIninboxContactForAbandonedCarts($quote, $ininboxAbandonedCartsList);				
			}
		}
	}
}
?>

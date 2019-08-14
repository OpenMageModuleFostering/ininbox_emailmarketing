<?php
/**
 * INinbox Email Marketing
 * @category    INinbox
 * @package     INinbox_Emailmarketing
**/
 
require_once Mage::getModuleDir('controllers', 'Mage_Adminhtml').DS.'CustomerController.php';

class Ininbox_Emailmarketing_Adminhtml_CustomerController extends Mage_Adminhtml_CustomerController
{
	/**
	 * Use to raise event on mass action subscribe.
	 */
	public function massSubscribeAction()
    {
		$customersIds = $this->getRequest()->getParam('customer');
        if(!is_array($customersIds)) {
             Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select customer(s).'));

        } else {
            try {
                foreach ($customersIds as $customerId) {
                    $customer = Mage::getModel('customer/customer')->load($customerId);
                    $customer->setIsSubscribed(true);
                    $customer->save();
                }
                
                // add event dispatcher to raise event after newsletter subscribe
                // this change is made to manage INinbox.com Contacts/List
                $data = array('customers_ids' => $customersIds);
				Mage::dispatchEvent('customer_newsletter_subscribe_after', $data);
				
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('Total of %d record(s) were updated.', count($customersIds))
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
	}
}

?>

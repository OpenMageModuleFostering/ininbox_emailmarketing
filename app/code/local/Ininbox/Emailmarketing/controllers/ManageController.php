<?php
/**
 * INinbox Email Marketing
 * @category    INinbox
 * @package     INinbox_Emailmarketing
**/
require_once Mage::getModuleDir('controllers', 'Mage_Newsletter').DS.'ManageController.php';

class Ininbox_Emailmarketing_ManageController extends Mage_Newsletter_ManageController
{    
    public function saveAction()
    {
        if (!$this->_validateFormKey()) {
            return $this->_redirect('customer/account/');
        }
        try {
            Mage::getSingleton('customer/session')->getCustomer()
            ->setStoreId(Mage::app()->getStore()->getId())
            ->setIsSubscribed((boolean)$this->getRequest()->getParam('is_subscribed', false))
            ->save();
            if ((boolean)$this->getRequest()->getParam('is_subscribed', false)) {
                Mage::getSingleton('customer/session')->addSuccess($this->__('The subscription has been saved.'));
                
                // add event dispatcher to raise event after news letter subscribe
                // this change is made to manage INinbox.com Contacts/List
                $data = array('email' => Mage::getSingleton('customer/session')->getCustomer()->getEmail());
				Mage::dispatchEvent('newsletter_subscribe_after', $data);
				
            } else {
                Mage::getSingleton('customer/session')->addSuccess($this->__('The subscription has been removed.'));
            }
        }
        catch (Exception $e) {
            Mage::getSingleton('customer/session')->addError($this->__('An error occurred while saving your subscription.'));
        }
        $this->_redirect('customer/account/');
    }
}

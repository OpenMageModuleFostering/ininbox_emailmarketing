<?php
/**
 * INinbox Email Marketing
 * @category    INinbox
 * @package     INinbox_Emailmarketing
**/
class Ininbox_Emailmarketing_Model_Observer 
{
	protected $_logFile = 'ininbox.log';
	
	/**
	 * event listener on customer detail save
	 *
	 * @Params
	 * $observer - contains customer details 
	 */	
	public function customerSaveObserver(Varien_Event_Observer $observer) 
	{
		if(Mage::helper('emailmarketing')->isEnabled())
		{
			$isIninboxSendCustomerEnabled = Mage::helper('emailmarketing')->getConfig($group = 'customer_settings', $field = 'send_customer');
			$ininboxCustomerGroupList = Mage::helper('emailmarketing')->getConfig($group = 'customer_settings', $field = 'customer_group_list');
			
			$ininboxResubscriber = Mage::helper('emailmarketing')->getConfig($group = 'customer_misc_settings', $field = 'update_subscriber') ? true : false;
			$ininboxSendConfirmationEmail = Mage::helper('emailmarketing')->getConfig($group = 'customer_misc_settings', $field = 'confirm_email') ? true : false;
			$ininboxAddContactToAutoresponderCycle = Mage::helper('emailmarketing')->getConfig($group = 'customer_misc_settings', $field = 'send_autoresponder') ? true : false;
			
			if($isIninboxSendCustomerEnabled && !is_null($ininboxCustomerGroupList))
			{
				$currentCustomer = $observer->getCustomer();
								
				$ininboxContactId = $currentCustomer->getIninboxContactId();
				
				if(isset($ininboxContactId) && !is_null($ininboxContactId))
					$this->updateIninboxContactGroup($currentCustomer->getEmail(), $ininboxCustomerGroupList, $ininboxResubscriber, $ininboxSendConfirmationEmail, $ininboxAddContactToAutoresponderCycle);
				else
					$this->createIninboxContact($currentCustomer, $ininboxCustomerGroupList, $ininboxResubscriber, $ininboxSendConfirmationEmail, $ininboxAddContactToAutoresponderCycle);
			}
		}
		
		$this->_customerRegistrationNewsletterSubscribe($observer);
	}
	
	/**
	 * protected function called to check new newsletter subscribed on customer registration
	 *
	 * @Params
	 * $observer - contains customer details on registration
	 */	
	protected function _customerRegistrationNewsletterSubscribe(Varien_Event_Observer $observer)
	{
		if(Mage::helper('emailmarketing')->isEnabled())
		{
			$isIninboxSendSubscriberEnabled = Mage::helper('emailmarketing')->getConfig($group = 'newsletter_settings', $field = 'send_subscriber');
			$ininboxSubscriberGroupList = Mage::helper('emailmarketing')->getConfig($group = 'newsletter_settings', $field = 'subscriber_group_list');
			
			$ininboxResubscriber = Mage::helper('emailmarketing')->getConfig($group = 'newsletter_misc_settings', $field = 'update_subscriber') ? true : false;
			$ininboxSendConfirmationEmail = Mage::helper('emailmarketing')->getConfig($group = 'newsletter_misc_settings', $field = 'confirm_email') ? true : false;
			$ininboxAddContactToAutoresponderCycle = Mage::helper('emailmarketing')->getConfig($group = 'newsletter_misc_settings', $field = 'send_autoresponder') ? true : false;
						
			if($isIninboxSendSubscriberEnabled && !is_null($ininboxSubscriberGroupList))
			{
				$currentCustomer = $observer->getCustomer();
				
				$newsletterSubscriber = Mage::getModel('newsletter/subscriber')->loadByEmail($currentCustomer->getEmail());				
				if($newsletterSubscriber->getId())
				{
					$ininboxContactId = $currentCustomer->getIninboxContactId();
				
					if(isset($ininboxContactId) && !is_null($ininboxContactId))
						$this->updateIninboxContactGroup($currentCustomer->getEmail(), $ininboxSubscriberGroupList, $ininboxResubscriber, $ininboxSendConfirmationEmail, $ininboxAddContactToAutoresponderCycle);
					else
						$this->createIninboxContact($currentCustomer, $ininboxCustomerGroupList, $ininboxResubscriber, $ininboxSendConfirmationEmail, $ininboxAddContactToAutoresponderCycle);
				}
			}
		}
	}
	
	/**
	 * event listener on new newsletter subscribe
	 *
	 * @Params
	 * $observer - contains email subscribed for newsletter
	 */	
	public function newsletterSubscribeAfterObserver(Varien_Event_Observer $observer)
	{
		if(Mage::helper('emailmarketing')->isEnabled())
		{
			$isIninboxSendSubscriberEnabled = Mage::helper('emailmarketing')->getConfig($group = 'newsletter_settings', $field = 'send_subscriber');
			$ininboxSubscriberGroupList = Mage::helper('emailmarketing')->getConfig($group = 'newsletter_settings', $field = 'subscriber_group_list');
			
			$ininboxResubscriber = Mage::helper('emailmarketing')->getConfig($group = 'newsletter_misc_settings', $field = 'update_subscriber') ? true : false;
			$ininboxSendConfirmationEmail = Mage::helper('emailmarketing')->getConfig($group = 'newsletter_misc_settings', $field = 'confirm_email') ? true : false;
			$ininboxAddContactToAutoresponderCycle = Mage::helper('emailmarketing')->getConfig($group = 'newsletter_misc_settings', $field = 'send_autoresponder') ? true : false;
			
			if($isIninboxSendSubscriberEnabled && !is_null($ininboxSubscriberGroupList))
			{
				$subscribeEmail = $observer->getEmail();
				$this->updateIninboxContactGroup($subscribeEmail, $ininboxSubscriberGroupList, $ininboxResubscriber, $ininboxSendConfirmationEmail, $ininboxAddContactToAutoresponderCycle);
			}
		}
	}
	
	/**
	 * event listener on sales order place when customer place new order.
	 *
	 * @Params
	 * $observer - contains order details
	 */	
	public function salesOrderPlaceAfterObserver(Varien_Event_Observer $observer) 
	{		
		if(Mage::helper('emailmarketing')->isEnabled())
		{
			$isIninboxSendOrderEnabled = Mage::helper('emailmarketing')->getConfig($group = 'order_settings', $field = 'send_order');
			$ininboxOrderGroupList = Mage::helper('emailmarketing')->getConfig($group = 'order_settings', $field = 'order_group_list');
			
			$ininboxResubscriber = Mage::helper('emailmarketing')->getConfig($group = 'order_misc_settings', $field = 'update_subscriber') ? true : false;
			$ininboxSendConfirmationEmail = Mage::helper('emailmarketing')->getConfig($group = 'order_misc_settings', $field = 'confirm_email') ? true : false;
			$ininboxAddContactToAutoresponderCycle = Mage::helper('emailmarketing')->getConfig($group = 'order_misc_settings', $field = 'send_autoresponder') ? true : false;
			
			if($isIninboxSendOrderEnabled && !is_null($ininboxOrderGroupList))
			{
				$this->createIninboxContactForSales($observer->getEvent()->getOrder(), $ininboxOrderGroupList, $ininboxResubscriber, $ininboxSendConfirmationEmail, $ininboxAddContactToAutoresponderCycle);
			}
		}
		
		$quote_id = $observer->getEvent()->getOrder()->getQuoteId();
		$quote = Mage::getModel('sales/quote')->load($quote_id);			
		$method = $quote->getCheckoutMethod(true);		
		
		if($method == Mage_Checkout_Model_Type_Onepage::METHOD_REGISTER)
		{
			if(Mage::helper('emailmarketing')->isEnabled())
			{
				$isIninboxSendCustomerEnabled = Mage::helper('emailmarketing')->getConfig($group = 'customer_settings', $field = 'send_customer');
				$ininboxCustomerGroupList = Mage::helper('emailmarketing')->getConfig($group = 'customer_settings', $field = 'customer_group_list');
				
				$ininboxResubscriber = Mage::helper('emailmarketing')->getConfig($group = 'customer_misc_settings', $field = 'update_subscriber') ? true : false;
				$ininboxSendConfirmationEmail = Mage::helper('emailmarketing')->getConfig($group = 'customer_misc_settings', $field = 'confirm_email') ? true : false;
				$ininboxAddContactToAutoresponderCycle = Mage::helper('emailmarketing')->getConfig($group = 'customer_misc_settings', $field = 'send_autoresponder') ? true : false;
				
				if($isIninboxSendCustomerEnabled && !is_null($ininboxCustomerGroupList))
				{					
					/*$currentCustomer = Mage::getModel('customer/customer')->load($observer->getEvent()->getOrder()->getCustomerId());*/
					$currentCustomer = $observer->getEvent()->getOrder()->getBillingAddress();
					//die($observer->getEvent()->getOrder()->custome());
					//die(json_encode($currentCustomer));
					$this->createIninboxContact($currentCustomer, $ininboxCustomerGroupList, $ininboxResubscriber, $ininboxSendConfirmationEmail, $ininboxAddContactToAutoresponderCycle);
				}
			}
		}
	}
	
	/**
	 * event listener on customer account edit.
	 *
	 * @Params
	 * $observer - contains customer details
	 */	
	public function customerAccountEditAfterObserver(Varien_Event_Observer $observer)
	{
		if(Mage::helper('emailmarketing')->isEnabled())
		{
			$isIninboxSendCustomerEnabled = Mage::helper('emailmarketing')->getConfig($group = 'customer_settings', $field = 'send_customer');
			$ininboxCustomerGroupList = Mage::helper('emailmarketing')->getConfig($group = 'customer_settings', $field = 'customer_group_list');
			
			$ininboxResubscriber = Mage::helper('emailmarketing')->getConfig($group = 'customer_misc_settings', $field = 'update_subscriber') ? true : false;
			$ininboxSendConfirmationEmail = Mage::helper('emailmarketing')->getConfig($group = 'customer_misc_settings', $field = 'confirm_email') ? true : false;
			$ininboxAddContactToAutoresponderCycle = Mage::helper('emailmarketing')->getConfig($group = 'customer_misc_settings', $field = 'send_autoresponder') ? true : false;
			
			if($isIninboxSendCustomerEnabled && !is_null($ininboxCustomerGroupList))
			{
				$currentCustomer = $observer->getCustomer();				
				$this->createIninboxContact($currentCustomer, $ininboxCustomerGroupList, $ininboxResubscriber, $ininboxSendConfirmationEmail, $ininboxAddContactToAutoresponderCycle);
			}
		}
	}
	
	/**
	 * event listener for newsletter group list on newsletter subscriber mass action	 
	 *
	 * @Params
	 * $observer - contains customer ids which are subscribed for newsletters.
	 */
	public function customerNewsletterSubscribeAfterObserver(Varien_Event_Observer $observer)
	{		
		if(Mage::helper('emailmarketing')->isEnabled())
		{
			$isIninboxSendSubscriberEnabled = Mage::helper('emailmarketing')->getConfig($group = 'newsletter_settings', $field = 'send_subscriber');
			$ininboxSubscriberGroupList = Mage::helper('emailmarketing')->getConfig($group = 'newsletter_settings', $field = 'subscriber_group_list');
			
			$ininboxResubscriber = Mage::helper('emailmarketing')->getConfig($group = 'newsletter_massaction_settings', $field = 'update_subscriber') ? true : false;
			$ininboxSendConfirmationEmail = Mage::helper('emailmarketing')->getConfig($group = 'newsletter_massaction_settings', $field = 'confirm_email') ? true : false;
			$ininboxAddContactToAutoresponderCycle = Mage::helper('emailmarketing')->getConfig($group = 'newsletter_massaction_settings', $field = 'send_autoresponder') ? true : false;
						
			if($isIninboxSendSubscriberEnabled && !is_null($ininboxSubscriberGroupList))
			{
				$customersIds = $observer->getCustomersIds();
				$this->importIninboxContact($customersIds, $ininboxSubscriberGroupList);				
			}
		}
	}	
	
	/**
	 * Use to update list on new customer register
	 * Here boolean variables varies based on massaction or on single action.
	 *
	 * @Params
	 * $currentCustomer - customer object for customer details
	 * $ininboxGroupList - group id for which we need to subscribe
	 * $ininboxResubscriber - true or false on resubscribe or not
	 * $ininboxSendConfirmationEmail - true or false on send confirmation or not
	 * $ininboxAddContactToAutoresponderCycle - true or false on add contact to autorespnder cycle or not
	 */
	public function createIninboxContact($currentCustomer, $ininboxGroupList, $ininboxResubscriber, $ininboxSendConfirmationEmail, $ininboxAddContactToAutoresponderCycle)
	{
		try 
		{
			$ininboxPredefinedCustomFields	= Mage::getModel('emailmarketing/system_config_source_field_list')->getPredefinedCustomList();
			$ininboxMappedCustomFields = unserialize(Mage::helper('emailmarketing')->getConfig($group = 'field_mapping', $field = 'field'));			
			
			foreach($ininboxMappedCustomFields as $mappedField)
			{
				$customerAttribute = $mappedField['customer_attributes'];
				$ininboxCustomField = $mappedField['ininbox_custom_fields'];
				if(array_key_exists($ininboxCustomField, $ininboxPredefinedCustomFields))
					$params['params'][$ininboxCustomField] = $currentCustomer->getData($customerAttribute);
				else
					$params['params']['CustomFields'][] = array('Key' => $ininboxCustomField, 'Value' =>  $currentCustomer->getData($customerAttribute));
			}
			
			$params['params']['Resubscribe'] = $ininboxResubscriber;
			$params['params']['SendConfirmationEmail'] = $ininboxSendConfirmationEmail;
			$params['params']['AddContactToAutoresponderCycle'] = $ininboxAddContactToAutoresponderCycle;
			$params['params']['ListIDs'] = array(intval($ininboxGroupList));
				
			$result = Mage::getModel('emailmarketing/ininbox_contact')->add($params);
		
			if(isset($result['Code']) && isset($result['Message']))	
			{		
				$error_message = Mage::helper('adminhtml')->__('ERROR (' . $result['Code'] . '): ' . $result['Message']);
				Mage::log($error_message, null, $this->_logFile);
			}	
			else
			{
				$currentCustomer->setIninboxContactId($result['ContactID']);
				$currentCustomer->save();			
			}
		}
		catch (Exception $e) {
			$error_message = Mage::helper('adminhtml')->__('ERROR: Error in updating creating in INinbox. <br />' . $e->getMessage());
			Mage::log($error_message, null, $this->_logFile);
		}
		
		return $error_message;
	}
	
	/**
	 * Use to update list on new order place
	 * Here boolean variables varies based on massaction or on single action.
	 *
	 * @Params
	 * $currentOrder - Current order for which you need to subscribe on INinbox
	 * $ininboxGroupList - group id for which we need to subscribe
	 * $ininboxResubscriber - true or false on resubscribe or not
	 * $ininboxSendConfirmationEmail - true or false on send confirmation or not
	 * $ininboxAddContactToAutoresponderCycle - true or false on add contact to autorespnder cycle or not
	 */
	public function createIninboxContactForSales($currentOrder, $ininboxGroupList, $ininboxResubscriber, $ininboxSendConfirmationEmail, $ininboxAddContactToAutoresponderCycle)
	{
		try 
		{
			$ininboxPredefinedCustomFields	= Mage::getModel('emailmarketing/system_config_source_field_list')->getPredefinedCustomList();
			$ininboxMappedCustomFields = unserialize(Mage::helper('emailmarketing')->getConfig($group = 'field_mapping', $field = 'field'));
			
			$currentCustomer = $currentOrder->getBillingAddress();			
			
			foreach($ininboxMappedCustomFields as $mappedField)
			{
				$customerAttribute = $mappedField['customer_attributes'];
				if($customerAttribute == 'default_shipping')
				{
					$currentCustomer = $currentOrder->getShippingAddress();
					break;
				}
			}
			
			foreach($ininboxMappedCustomFields as $mappedField)
			{
				$customerAttribute = $mappedField['customer_attributes'];
				$ininboxCustomField = $mappedField['ininbox_custom_fields'];
				if($customerAttribute == 'default_billing' || $customerAttribute == 'default_shipping')
				{
					if($ininboxCustomField == 'Address')
					{
						$params['params']['Address'] = $currentCustomer->getStreet1() . ' ' . $currentCustomer->getStreet2();
						$params['params']['CountryCode'] = $currentCustomer->getCountryId();
						$params['params']['City'] = $currentCustomer->getCity();
						$params['params']['Zip'] = $currentCustomer->getPostcode();
						$params['params']['MobilePhone'] = $currentCustomer->getTelephone();
						$params['params']['Fax'] = $currentCustomer->getFax();
					}
					else
					{
						if(array_key_exists($ininboxCustomField, $ininboxPredefinedCustomFields))
							$params['params'][$ininboxCustomField] = $currentCustomer->getStreet1() . ' ' . $currentCustomer->getStreet2();
						else
							$params['params']['CustomFields'][] = array('Key' => $ininboxCustomField, 'Value' =>  ($currentCustomer->getStreet1() . ' ' . $currentCustomer->getStreet2()));
					}
				}
				else
				{
					if(array_key_exists($ininboxCustomField, $ininboxPredefinedCustomFields))
						$params['params'][$ininboxCustomField] = $currentCustomer->getData($customerAttribute);
					else
						$params['params']['CustomFields'][] = array('Key' => $ininboxCustomField, 'Value' =>  $currentCustomer->getData($customerAttribute));
				}
			}
			
			$params['params']['Resubscribe'] = $ininboxResubscriber;
			$params['params']['SendConfirmationEmail'] = $ininboxSendConfirmationEmail;
			$params['params']['AddContactToAutoresponderCycle'] = $ininboxAddContactToAutoresponderCycle;
			$params['params']['ListIDs'] = array(intval($ininboxGroupList));
			
			$result = Mage::getModel('emailmarketing/ininbox_contact')->add($params);	
			
			if(isset($result['Code']) && isset($result['Message']))	
			{		
				$error_message = Mage::helper('adminhtml')->__('ERROR (' . $result['Code'] . '): ' . $result['Message']);
				Mage::log($error_message, null, $this->_logFile);
			}	
			else
			{
				$currentCustomer->setIninboxContactId($result['ContactID']);
				$currentCustomer->save();			
			}
		}
		catch (Exception $e) {
			$error_message = Mage::helper('adminhtml')->__('ERROR: Error in creating contact in INinbox. <br />' . $e->getMessage());
			Mage::log($error_message, null, $this->_logFile);
		}
		
		return $error_message;
	}
		
	/**
	 * Use to update list on customer email
	 * Here boolean variables varies based on massaction or on single action.
	 *
	 * @Params
	 * $ininboxCustomerEmail - customer email for subscribtion
	 * $ininboxGroupList - group id for which we need to subscribe
	 * $ininboxResubscriber - true or false on resubscribe or not
	 * $ininboxSendConfirmationEmail - true or false on send confirmation or not
	 * $ininboxAddContactToAutoresponderCycle - true or false on add contact to autorespnder cycle or not
	 */
	public function updateIninboxContactGroup($ininboxCustomerEmail, $ininboxGroupList, $ininboxResubscriber, $ininboxSendConfirmationEmail, $ininboxAddContactToAutoresponderCycle)
	{
		try 
		{			
			$params['params']['Email'] = $ininboxCustomerEmail;
			$params['params']['Resubscribe'] = $ininboxResubscriber;
			$params['params']['SendConfirmationEmail'] = $ininboxSendConfirmationEmail;
			$params['params']['AddContactToAutoresponderCycle'] = $ininboxAddContactToAutoresponderCycle;
			$params['params']['ListIDs'] = array(intval($ininboxGroupList));
			$result = Mage::getModel('emailmarketing/ininbox_contact')->add($params);
			
			if(isset($result['Code']) && isset($result['Message']))
			{
				$error_message = Mage::helper('adminhtml')->__('ERROR (' . $result['Code'] . '): ' . $result['Message']);
				Mage::log($error_message, null, $this->_logFile);
			}
		}
		catch (Exception $e) {
			$error_message = Mage::helper('adminhtml')->__('ERROR: Error in updating contact in INinbox. <br />' . $e->getMessage());
			Mage::log($error_message, null, $this->_logFile);
		}
		
		return $error_message;
	}
	
	/**
	 * Use to import contacts on mass action contacts
	 *
	 * @Params
	 * $currentCustomer - customer object for customer details
	 * $ininboxGroupList - group id for which we need to subscribe
	 * $ininboxResubscriber - true or false on resubscribe or not
	 * $ininboxSendConfirmationEmail - true or false on send confirmation or not
	 * $ininboxAddContactToAutoresponderCycle - true or false on add contact to autorespnder cycle or not
	 */
	public function importIninboxContact($customersIds, $ininboxGroupList)
	{
		try 
		{
			$ininboxPredefinedCustomFields	= Mage::getModel('emailmarketing/system_config_source_field_list')->getPredefinedCustomList();
			$ininboxMappedCustomFields = unserialize(Mage::helper('emailmarketing')->getConfig($group = 'field_mapping', $field = 'field'));			
			
			// get the settings for mass action miscellaneous section to add customer on mass action
			$ininboxResubscriber = Mage::helper('emailmarketing')->getConfig($group = 'customer_massaction_settings', $field = 'update_subscriber') ? true : false;
			$ininboxSendConfirmationEmail = Mage::helper('emailmarketing')->getConfig($group = 'customer_massaction_settings', $field = 'confirm_email') ? true : false;
			$ininboxAddContactToAutoresponderCycle = Mage::helper('emailmarketing')->getConfig($group = 'customer_massaction_settings', $field = 'send_autoresponder') ? true : false;
			
			$count = 0;
			foreach ($customersIds as $customerId) 
			{
				$currentCustomer = Mage::getModel('customer/customer')->load($customerId);
				
				$array = array();
				foreach($ininboxMappedCustomFields as $mappedField)
				{
					$customerAttribute = $mappedField['customer_attributes'];
					$ininboxCustomField = $mappedField['ininbox_custom_fields'];
					if(array_key_exists($ininboxCustomField, $ininboxPredefinedCustomFields))
						$array[$ininboxCustomField] = $currentCustomer->getData($customerAttribute);
					else
						$array['CustomFields'][] = array('Key' => $ininboxCustomField, 'Value' =>  $currentCustomer->getData($customerAttribute));
				}
				
				$params['params']['Contacts'][$count++] = $array;
			}
			$params['params']['Resubscribe'] = $ininboxResubscriber;
			$params['params']['SendConfirmationEmail'] = $ininboxSendConfirmationEmail;
			$params['params']['AddContactToAutoresponderCycle'] = $ininboxAddContactToAutoresponderCycle;
			$params['params']['ListIDs'] = array(intval($ininboxGroupList));			
			
			$result = Mage::getModel('emailmarketing/ininbox_contact')->import($params);			
		
			if(isset($result['Code']) && isset($result['Message']))
			{
				$error_message = Mage::helper('adminhtml')->__('ERROR (' . $result['Code'] . '): ' . $result['Message']);
				Mage::log($error_message, null, $this->_logFile);
			}
		}
		catch (Exception $e) {
			$error_message = Mage::helper('adminhtml')->__('ERROR: Error in importing contacts to INinbox. <br />' . $e->getMessage());
			Mage::log($error_message, null, $this->_logFile);
		}
		return $error_message;
	}
	
	/**
	 * Use to import contacts on mass action newsletter	
	 *
	 * @Params
	 * $ininboxCustomerEmail - customer email for subscribtion
	 * $ininboxGroupList - group id for which we need to subscribe
	 * $ininboxResubscriber - true or false on resubscribe or not
	 * $ininboxSendConfirmationEmail - true or false on send confirmation or not
	 * $ininboxAddContactToAutoresponderCycle - true or false on add contact to autorespnder cycle or not
	 */
	public function importNewsletterContactGroup($subscribersIds, $ininboxGroupList)
	{
		try 
		{			
			$ininboxResubscriber = Mage::helper('emailmarketing')->getConfig($group = 'newsletter_massaction_settings', $field = 'update_subscriber') ? true : false;
			$ininboxSendConfirmationEmail = Mage::helper('emailmarketing')->getConfig($group = 'newsletter_massaction_settings', $field = 'confirm_email') ? true : false;
			$ininboxAddContactToAutoresponderCycle = Mage::helper('emailmarketing')->getConfig($group = 'newsletter_massaction_settings', $field = 'send_autoresponder') ? true : false;
			
			$count = 0;			
			foreach ($subscribersIds as $subscriberId) 
			{			
				$subscriber = Mage::getModel('newsletter/subscriber')->load($subscriberId);
				$subscribeEmail = $subscriber->getData('subscriber_email');				
				$array['Email'] = $subscribeEmail;
				$params['params']['Contacts'][$count++] = $array;
			}
			
			$params['params']['Resubscribe'] = $ininboxResubscriber;			
			$params['params']['SendConfirmationEmail'] = $ininboxSendConfirmationEmail;
			$params['params']['AddContactToAutoresponderCycle'] = $ininboxAddContactToAutoresponderCycle;
			$params['params']['ListIDs'] = array(intval($ininboxGroupList));			
			
			$result = Mage::getModel('emailmarketing/ininbox_contact')->import($params);
			
			if(isset($result['Code']) && isset($result['Message']))	
			{		
				$error_message = Mage::helper('adminhtml')->__('ERROR (' . $result['Code'] . '): ' . $result['Message']);			
				Mage::log($error_message, null, $this->_logFile);
			}
		}
		catch (Exception $e) {
			$error_message = Mage::helper('adminhtml')->__('ERROR: Error in importing contacts to INinbox. <br />' . $e->getMessage());
			Mage::log($error_message, null, $this->_logFile);
		}
		
		return $error_message;
	}
	
	/**
	 * Use to update list on new order place
	 * Here boolean variables varies based on massaction or on single action.
	 *
	 * @Params
	 * $currentOrder - Current order for which you need to subscribe on INinbox
	 * $ininboxGroupList - group id for which we need to subscribe
	 * $ininboxResubscriber - true or false on resubscribe or not
	 * $ininboxSendConfirmationEmail - true or false on send confirmation or not
	 * $ininboxAddContactToAutoresponderCycle - true or false on add contact to autorespnder cycle or not
	 */
	public function importIninboxContactForSales($orderIds, $ininboxGroupList)
	{
		try 
		{
			$ininboxPredefinedCustomFields	= Mage::getModel('emailmarketing/system_config_source_field_list')->getPredefinedCustomList();
			$ininboxMappedCustomFields = unserialize(Mage::helper('emailmarketing')->getConfig($group = 'field_mapping', $field = 'field'));
			
			$ininboxResubscriber = Mage::helper('emailmarketing')->getConfig($group = 'order_massaction_settings', $field = 'update_subscriber') ? true : false;
			$ininboxSendConfirmationEmail = Mage::helper('emailmarketing')->getConfig($group = 'order_massaction_settings', $field = 'confirm_email') ? true : false;
			$ininboxAddContactToAutoresponderCycle = Mage::helper('emailmarketing')->getConfig($group = 'order_massaction_settings', $field = 'send_autoresponder') ? true : false;			
			
			$count = 0;
			foreach ($orderIds as $orderId) 
			{
				$currentOrder = Mage::getModel('sales/order')->load($orderId);				
				$currentCustomer = $currentOrder->getBillingAddress();			
			
				foreach($ininboxMappedCustomFields as $mappedField)
				{
					$customerAttribute = $mappedField['customer_attributes'];
					if($customerAttribute == 'default_shipping')
					{
						$currentCustomer = $currentOrder->getShippingAddress();
						break;
					}
				}
				
				$array = array();
				foreach($ininboxMappedCustomFields as $mappedField)
				{
					$customerAttribute = $mappedField['customer_attributes'];
					$ininboxCustomField = $mappedField['ininbox_custom_fields'];
					if($customerAttribute == 'default_billing' || $customerAttribute == 'default_shipping')
					{
						if($ininboxCustomField == 'Address')
						{
							$array['Address'] = $currentCustomer->getStreet1() . ' ' . $currentCustomer->getStreet2();
							$array['CountryCode'] = $currentCustomer->getCountryId();
							$array['City'] = $currentCustomer->getCity();
							$array['Zip'] = $currentCustomer->getPostcode();
							$array['MobilePhone'] = $currentCustomer->getTelephone();
							$array['Fax'] = $currentCustomer->getFax();
						}
						else
						{
							if(array_key_exists($ininboxCustomField, $ininboxPredefinedCustomFields))
								$array[$ininboxCustomField] = $currentCustomer->getStreet1() . ' ' . $currentCustomer->getStreet2();
							else
								$array['CustomFields'][] = array('Key' => $ininboxCustomField, 'Value' =>  ($currentCustomer->getStreet1() . ' ' . $currentCustomer->getStreet2()));
						}
					}
					else
					{
						if(array_key_exists($ininboxCustomField, $ininboxPredefinedCustomFields))
							$array[$ininboxCustomField] = $currentCustomer->getData($customerAttribute);
						else
							$array['CustomFields'][] = array('Key' => $ininboxCustomField, 'Value' =>  $currentCustomer->getData($customerAttribute));
					}
				}
				
				$params['params']['Contacts'][$count++] = $array;
			}
			
			$params['params']['Resubscribe'] = $ininboxResubscriber;
			$params['params']['SendConfirmationEmail'] = $ininboxSendConfirmationEmail;
			$params['params']['AddContactToAutoresponderCycle'] = $ininboxAddContactToAutoresponderCycle;
			$params['params']['ListIDs'] = array(intval($ininboxGroupList));			
			
			$result = Mage::getModel('emailmarketing/ininbox_contact')->import($params);
			
			if(isset($result['Code']) && isset($result['Message']))	
			{		
				$error_message = Mage::helper('adminhtml')->__('ERROR (' . $result['Code'] . '): ' . $result['Message']);
				Mage::log($error_message, null, $this->_logFile);
			}
		}
		catch (Exception $e) {
			$error_message = Mage::helper('adminhtml')->__('ERROR: Error in importing contacts to INinbox. <br />' . $e->getMessage());
			Mage::log($error_message, null, $this->_logFile);
		}
		
		return $error_message;
	}
	
	public function validateIninboxConfig() 
	{
		if(Mage::helper('emailmarketing')->isEnabled())
		{
			$api_url = Mage::helper('emailmarketing')->getConfig($group = 'general', $field = 'url');
			$api_key = Mage::helper('emailmarketing')->getConfig($group = 'general', $field = 'key');		
			
			$system_date = Mage::getModel('emailmarketing/ininbox_general')->getSystemDateTime($api_url, $api_key);

			if(isset($system_date['Code']) && isset($system_date['Message']))        
			{
				$message = Mage::helper('adminhtml')->__('ERROR (' . $system_date['Code'] . '): ' . $system_date['Message']);
				Mage::getSingleton('adminhtml/session')->addError($message);
				
				Mage::getConfig()->saveConfig('ininbox_emailmarketing_options/general/key', '');
				Mage::getConfig()->reinit();
				Mage::app()->reinitStores();
				
				$this->message_search('The configuration has been saved.', true, 'adminhtml/session');
			}
			else if(is_null($system_date))
			{
				$message = Mage::helper('adminhtml')->__('ERROR: Invalid URL or API key.');
				Mage::getSingleton('adminhtml/session')->addError($message);
				
				Mage::getConfig()->saveConfig('ininbox_emailmarketing_options/general/key', '');
				Mage::getConfig()->reinit();
				Mage::app()->reinitStores();
				
				$this->message_search('The configuration has been saved.', true, 'adminhtml/session');
			}
		}		
		else
		{
			Mage::getConfig()->saveConfig('ininbox_emailmarketing_options/general/key', '');
			Mage::getConfig()->reinit();
			Mage::app()->reinitStores();
		}
	}
	
	public function message_search($string, $remove = false, $which = 'core/session' ) 
	{
		$found = false;

		$messages = Mage::getSingleton($which)->getMessages();		
		
		foreach($messages->getItems() as $message)
		{
			if(stristr($message->getText(), $string )) 
			{
				$found = true;
				if($remove) 
					$message->setIdentifier('this_message_will_be_removed');
			}
			if($remove) 
				$messages->deleteMessageByIdentifier('this_message_will_be_removed');
		}
		return $found;
	}
}
?>

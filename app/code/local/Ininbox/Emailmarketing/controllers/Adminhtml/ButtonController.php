<?php
/**
 * INinbox Email Marketing
 * @category    INinbox
 * @package     INinbox_Emailmarketing
**/ 
class Ininbox_Emailmarketing_Adminhtml_ButtonController extends Mage_Adminhtml_Controller_Action
{
	public function checkAction()
    {
		$data = $this->getRequest()->getParams();		
		
		$system_date = Mage::getModel('emailmarketing/ininbox_general')->getSystemDateTime($data['api_url'], $data['api_key']);
		/*
		echo '<pre>';
		print_r($system_date);
		echo '</pre>';
		die('DIED');
		*/
		$message = 1;
        if(isset($system_date['Code']) && isset($system_date['Message']))        
			$message = Mage::helper('adminhtml')->__('ERROR (' . $system_date['Code'] . '): ' . $system_date['Message']);
		else if(is_null($system_date))
			$message = Mage::helper('adminhtml')->__('ERROR: Invalid URL or API key.');
		else
		{
			Mage::getConfig()->saveConfig('ininbox_emailmarketing_options/general/enabled', 1);
			Mage::getConfig()->saveConfig('ininbox_emailmarketing_options/general/url', $data['api_url']);
			Mage::getConfig()->saveConfig('ininbox_emailmarketing_options/general/key', $data['api_key']);
			Mage::getConfig()->reinit();
			Mage::app()->reinitStores();
		}
        Mage::app()->getResponse()->setBody($message);
    }
}

?>

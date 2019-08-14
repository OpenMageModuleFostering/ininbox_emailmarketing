<?php 
class Ininbox_Emailmarketing_Adminhtml_AbandonedCartsController extends Mage_Adminhtml_Controller_Action
{
	protected function _initAction()
	{
		$this->loadLayout()->_setActiveMenu('ininbox/ininboxabandonedcarts');
		return $this;
	}
	
	function indexAction()
	{
		$this->_initAction();
		$this->_addContent($this->getLayout()->createBlock('emailmarketing/adminhtml_abandonedCarts'));
		$this->renderLayout();
	}
}
?>

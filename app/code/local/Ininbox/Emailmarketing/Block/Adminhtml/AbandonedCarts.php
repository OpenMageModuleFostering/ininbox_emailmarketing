<?php
class Ininbox_Emailmarketing_Block_Adminhtml_AbandonedCarts extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct()
	{	
		parent::__construct();	
		$this->_controller = 'adminhtml_abandonedCarts';
		$this->_blockGroup = 'emailmarketing';
		$this->_headerText = 'Abandoned Carts';
		$this->_removeButton('add');		
	}
}
?>

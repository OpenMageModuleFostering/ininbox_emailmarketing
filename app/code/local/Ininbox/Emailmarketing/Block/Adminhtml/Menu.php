<?php
/**
 * INinbox Email Marketing
 * @category    INinbox
 * @package     INinbox_Emailmarketing
**/
class Ininbox_Emailmarketing_Block_Adminhtml_Menu extends Mage_Adminhtml_Block_Page_Menu
{
	protected $_ininboxUrl = "https://www.ininbox.com/login.html";
	
	protected $_inspiredSupportUrl = "http://www.ininbox.com/support";
	
    public function getMenuArray()
    {
        //Load standard menu
        $parentArr = parent::getMenuArray();
       
		$parentArr['ininbox']['children']['ininboxlogin']['click'] = "window.open('" . $this->_ininboxUrl . "', '_blank')";
		$parentArr['ininbox']['children']['inspiredsupport']['click'] = "window.open('" . $this->_inspiredSupportUrl . "', '_blank')";

        return $parentArr;
    }
}
?>

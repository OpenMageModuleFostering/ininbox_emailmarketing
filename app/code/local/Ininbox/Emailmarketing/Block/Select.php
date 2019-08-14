<?php
/**
 * INinbox Email Marketing
 * @category    INinbox
 * @package     INinbox_Emailmarketing
**/
class Ininbox_Emailmarketing_Block_Select extends Mage_Core_Block_Html_Select
{
	public function _toHtml()
	{
		return trim(preg_replace('/\s+/', ' ',parent::_toHtml()));
	}
}
?>

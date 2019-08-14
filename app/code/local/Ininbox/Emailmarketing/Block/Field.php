<?php
/**
 * INinbox Email Marketing
 * @category    INinbox
 * @package     INinbox_Emailmarketing
**/

class Ininbox_Emailmarketing_Block_Field extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
	protected $_customerAttributeRenderer;
	
	protected $_ininboxCustomFieldRenderer;	

	protected $_customerAttribute;

	public function __construct()
	{
		parent::__construct();		
	}
	
	/**
	 * Use to get the customer attribute dropdown.
	 * 
	 * @Return: return dropdown layout for customer attribute
	 */
	protected function _getCustomerAttributeRenderer()
	{
		if (!$this->_customerAttributeRenderer) {
			$this->_customerAttributeRenderer = Mage::app()->getLayout()
			->createBlock('emailmarketing/select')
			->setIsRenderToJsTemplate(true);
		}
		return $this->_customerAttributeRenderer;
	}

	protected function _getIninboxCustomFieldRenderer()
	{
		if (!$this->_ininboxCustomFieldRenderer) {
			$this->_ininboxCustomFieldRenderer = Mage::app()->getLayout()
			->createBlock('emailmarketing/select')
			->setIsRenderToJsTemplate(true);
		}
		return $this->_ininboxCustomFieldRenderer;
	}	

	protected function _getCustomerAttribute()
	{
		if (!$this->_customerAttribute) {
			$this->_customerAttribute = Mage::getModel('emailmarketing/system_config_source_customer_attribute')
				->toOptionArray();
		}
		return $this->_customerAttribute;
	}

	protected function _prepareToRender()
	{
		$this->_ininboxCustomFieldRenderer = null;
		$this->_customerAttributeRenderer = null;
			
		$this->addColumn('customer_attributes', array(
        	'label' => Mage::helper('emailmarketing')->__('Customer Attributes'),
			'style' => 'width:150px',
		));
		$this->addColumn('ininbox_custom_fields', array(
        	'label' => Mage::helper('emailmarketing')->__('INinbox Custom Fields'),
			'style' => 'width:150px',
		));

		$this->_addAfter = false;
		$this->_addButtonLabel = Mage::helper('emailmarketing')->__('Add Field Mapping');
	}

	protected function _renderCellTemplate($columnName)
	{
		$inputName  = $this->getElement()->getName() . '[#{_id}][' . $columnName . ']';
		$column     = $this->_columns[$columnName];

		if ($columnName == 'customer_attributes') {
			return $this->_getCustomerAttributeRenderer()
			->setName($inputName)
			->setTitle($columnName)
			->setExtraParams((isset($column['style']) ? 'style="' . $column['style'] . '"' : ""))
			->setOptions($this->_getCustomerAttribute())
			->toHtml();
		}
		else if($columnName == 'ininbox_custom_fields') {
			return $this->_getIninboxCustomFieldRenderer()
			->setName($inputName)
			->setTitle($columnName)
			->setExtraParams((isset($column['style']) ? 'style="' . $column['style'] . '"' : ""))
			->setOptions($this->getElement()->getValues())
			->toHtml();
		}
		return parent::_renderCellTemplate($columnName);
	}

	protected function _prepareArrayRow(Varien_Object $row)
	{
		$row->setData('option_extra_attr_' . $this->_getCustomerAttributeRenderer()->calcOptionHash($row->getData('customer_attributes')), 'selected="selected"');
		$row->setData('option_extra_attr_' . $this->_getIninboxCustomFieldRenderer()->calcOptionHash($row->getData('ininbox_custom_fields')), 'selected="selected"');
	}
}
?>

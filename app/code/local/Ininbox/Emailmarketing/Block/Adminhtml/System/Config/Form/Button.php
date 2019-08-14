<?php
class Ininbox_Emailmarketing_Block_Adminhtml_System_Config_Form_Button extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /*
     * Set template
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('ininbox/system/config/button.phtml');
    }

    /**
     * Return element html
     *
     * @param  Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        return $this->_toHtml();
    }

    /**
     * Return ajax url for button
     *
     * @return string
     */
    public function getAjaxCheckUrl()
    {
        return Mage::helper('adminhtml')->getUrl('admin_ininbox_emailmarketing/adminhtml_button/check');
    }
    
    /**
     * Return config url to redirect
     *
     * @return string
     */
    public function getConfigUrl()
    {
        return Mage::helper('adminhtml')->getUrl('adminhtml/system_config/edit/section/ininbox_emailmarketing_options');
    }    

    /**
     * Generate button html
     *
     * @return string
     */
    public function getButtonHtml()
    {
        $button = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setData(array(
            'id'        => 'ininbox_connect_button',
            'label'     => $this->helper('adminhtml')->__('Connect'),
            'class'		=> 'scalable save',
            'onclick'   => 'javascript:check(); return false;'
        ));

        return $button->toHtml();
    }
}

?>

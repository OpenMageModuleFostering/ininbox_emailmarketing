<?php
/**
 * Post Grid Container
 * 
 * @package - Medma
 * @author - Medma Development Team
 */
class Ininbox_Emailmarketing_Block_Adminhtml_AbandonedCarts_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('abandonedCartsGrid');
		$this->setDefaultSort('entity_id');
		$this->setDefaultDir('DESC');
		$this->setSaveParametersInSession(true);
	}
	
	protected function _prepareMassaction()
    {
		$this->setMassactionIdField('entity_id');
		$this->getMassactionBlock()->setFormFieldName('quote_ids');

		$isIninboxAbandonedCartsEnabled = Mage::helper('emailmarketing')->getConfig($group = 'abandoned_carts_settings', $field = 'add_to_list_enabled');
		$ininboxAbandonedCartsList = Mage::helper('emailmarketing')->getConfig($group = 'abandoned_carts_settings', $field = 'abandoned_list');
		
		if($isIninboxAbandonedCartsEnabled && !is_null($ininboxAbandonedCartsList))
		{				
			$this->getMassactionBlock()->addItem(
				'emailmarketing',
				array('label' => $this->__('Add to INinbox List'),
					  'url'   => $this->getUrl('emailmarketing/adminhtml_massaction/abandonedCarts')
				)
			);
		}
    }
	
	protected function _prepareCollection()
	{
		$collection = Mage::getResourceModel('sales/quote_collection')
			->addFieldToFilter('reserved_order_id', array('null' => true))
			->addFieldToFilter('customer_email', array('neq' => null));
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}
	
	protected function _prepareColumns()
	{
		$this->addColumn('entity_id', 
			array(
				'header' => Mage::helper('adminhtml')->__('ID'),
				'align' =>'right',
				'width' => '50px',
				'index' => 'entity_id',
		   ));
		$this->addColumn('customer_firstname',
		   array(
				'header' => Mage::helper('adminhtml')->__('Customer First Name'),
				'align' =>'left',
				'index' => 'customer_firstname',
		   ));
		$this->addColumn('customer_lastname',
		   array(
				'header' => Mage::helper('adminhtml')->__('Customer Last Name'),
				'align' =>'left',
				'index' => 'customer_lastname',
		   ));
	   $this->addColumn('customer_email',
		   array(
				'header' => Mage::helper('adminhtml')->__('Customer Email'),
				'align' =>	'left',
				'index' => 	'customer_email',
		   ));
	   $this->addColumn('created_at', array(
            'header'    =>Mage::helper('adminhtml')->__('Created At'),
            'width'     =>'170px',
            'type'      =>'datetime',
            'index'     =>'created_at',
            'sortable'  =>false
        ));        
        $this->addColumn('updated_at', array(
            'header'    =>Mage::helper('adminhtml')->__('Updated At'),
            'width'     =>'170px',
            'type'      =>'datetime',
            'index'     =>'updated_at',
            'sortable'  =>false
        ));
        
        $this->addColumn('remote_ip', array(
            'header'    =>Mage::helper('adminhtml')->__('IP Address'),
            'width'     =>'80px',
            'index'     =>'remote_ip',
            'sortable'  =>false
        ));
        
        $this->addColumn('checkout_method',
		   array(
				'header' => Mage::helper('adminhtml')->__('Checkout Method'),
				'align' =>'left',
				'index' => 'checkout_method',
				'width' => '90px',				
		   ));
		   
		$this->addColumn('ininbox_is_abandoned',
		   array(
				'header' => Mage::helper('adminhtml')->__('INinbox Abandoned Carts?'),
				'align' =>'center',
				'index' => 'ininbox_is_abandoned',
				'width' => '90px',	
				'type'  => 'options',
				'options' => array(
					1 => 'Yes',
					0 => 'No',
				),			
		   ));
        
		$this->addExportType('*/*/exportAbandonedCsv', Mage::helper('reports')->__('CSV'));
        $this->addExportType('*/*/exportAbandonedExcel', Mage::helper('reports')->__('Excel XML'));

		return parent::_prepareColumns();
	}	
}
?>

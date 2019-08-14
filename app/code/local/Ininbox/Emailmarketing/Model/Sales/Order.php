<?php

class Ininbox_Emailmarketing_Model_Sales_Order extends Mage_Sales_Model_Order
{
	public function setState($state, $status = false, $comment = '', $isCustomerNotified = null)
    {
        $value = parent::setState($state, $status, $comment, $isCustomerNotified);
        Mage::dispatchEvent('sales_order_status_after', array('order' => $this, 'state' => $state, 'status' => $status, 'comment' => $comment, 'isCustomerNotified' => $isCustomerNotified, 'shouldProtectState' => $shouldProtectState));        
        return $value;
    }
}

?>

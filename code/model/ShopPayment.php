<?php

/**
 * Customisations to {@link Payment} specifically for the shop module.
 *
 * @package shop
 */
class ShopPayment extends DataExtension
{
    private static $has_one = array(
        'Order' => 'Order',
    );

	// completePayment() does among other things send the receipt email
	// this is not good when we do partial capturing
    public function onCaptured($response)
    {
        $order = $this->owner->Order();
        if ($order->exists() && $order->Status == 'Complete') {
            OrderProcessor::create($order)->completePayment();
        }
    }
    
    public function onAuthorized($response)
	{
	}

	public function onPendingAuthorize($response)
	{
		$order = $this->owner->Order();
		if ($order->exists()) {
			OrderProcessor::create($order)->placeOrder();
		}
	}
	
		public function onVoid($response)
	{
		$order = $this->owner->Order();
		if ($order->exists()) {
			$order->Status = 'AdminCancelled';
			$order->write();
		}
	}

	public function onRefund($response)
	{
		$order = $this->owner->Order();
		if ($order->exists()) {
			$order->Status = 'AdminCancelled';
			$order->write();
		}
	}
    
}

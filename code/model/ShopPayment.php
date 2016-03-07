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

    public function onCaptured($response)
    {
        $order = $this->owner->Order();
        if ($order->exists()) {
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

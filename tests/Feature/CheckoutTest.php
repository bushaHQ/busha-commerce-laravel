<?php

namespace Tests\Feature;

use Busha\Commerce\BushaCommerce;
use Busha\Commerce\Exceptions\InvalidPayloadException;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->commerce = new BushaCommerce();
    }

    public function testCreateCheckout()
    {
        $checkout = $this->commerce->createCheckout([
            "name" => "New Checkout",
            "description" => "Mastering the Transition to the Information Age",
            "checkout_type" => "donation",
            "requested_info" => ['name', 'email']
        ]);
        $this->assertEquals('success', $checkout->status);
        return $checkout->data->id;
    }

    #[Depends('testCreateCheckout')]
    public function testGetCheckout($checkoutID)
    {
        $checkout = $this->commerce->getCheckout($checkoutID);
        $this->assertEquals('success', $checkout->status);
    }

    public function testListCheckouts()
    {
        $checkouts = $this->commerce->listCheckouts(['limit' => 10,'page' => 1, 'sort' => 'asc']);
        $this->assertEquals('success', $checkouts->status);
    }

    #[Depends('testCreateCheckout')]
    public function testUpdateCheckout($checkoutID)
    {
        $checkout = $this->commerce->updateCheckout($checkoutID, [
            "name" => "The Sovereign Individual",
            "description" => "Mastering the Transition to the Information Age",
            "checkout_type" => "fixed_price",
            "local_amount" => 5000,
            "local_currency" => "NGN",
            "requested_info" => ['name','email','phone']
        ]);
        $this->assertEquals('success', $checkout->status);
    }

    #[Depends('testCreateCheckout')]
    public function testCreateCheckoutCharge($checkoutID)
    {
        $checkout = $this->commerce->createCheckoutCharge($checkoutID, [
            "local_amount" => 50000,
            "local_currency" => "NGN",
            "meta" => [
                "email" => "sarah.shaw@example.co",
                "name" => "Sarah Shaw",
                "phone" => "09011178899"
            ]
        ]);
        $this->assertEquals('success', $checkout->status);
    }

    #[Depends('testCreateCheckout')]
    public function testDeleteCheckout($checkoutID){
        $checkout = $this->commerce->deleteCheckout($checkoutID);
        $this->assertEquals('success', $checkout->status);
    }
}
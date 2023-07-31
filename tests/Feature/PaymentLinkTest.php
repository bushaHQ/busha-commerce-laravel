<?php

namespace Tests\Feature;

use Busha\Commerce\BushaCommerce;
use Busha\Commerce\Exceptions\InvalidPayloadException;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class PaymentLinkTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->commerce = new BushaCommerce();
    }

    public function testCreatePaymentLink()
    {
        $paymentLink = $this->commerce->createPaymentLink([
            "name" => "New Payment Link",
            "description" => "Mastering the Transition to the Information Age",
            "payment_link_type" => "donation",
            "requested_info" => ['name', 'email']
        ]);
        $this->assertEquals('success', $paymentLink->status);
        return $paymentLink->data->id;
    }

    #[Depends('testCreatePaymentLink')]
    public function testGetPaymentLink($paymentLinkID)
    {
        $paymentLink = $this->commerce->getPaymentLink($paymentLinkID);
        $this->assertEquals('success', $paymentLink->status);
    }

    public function testListPaymentLinks()
    {
        $paymentLinks = $this->commerce->listPaymentLinks(['limit' => 10,'page' => 1, 'sort' => 'asc']);
        $this->assertEquals('success', $paymentLinks->status);
    }

    #[Depends('testCreatePaymentLink')]
    public function testUpdatePaymentLink($paymentLinkID)
    {
        $paymentLink = $this->commerce->updatePaymentLink($paymentLinkID
            , [
            "name" => "The Sovereign Individual",
            "description" => "Mastering the Transition to the Information Age",
            "payment_link_type" => "fixed_price",
            "local_amount" => 5000,
            "local_currency" => "NGN",
            "requested_info" => ['name','email','phone']
        ]);
        $this->assertEquals('success', $paymentLink->status);
    }

    #[Depends('testCreatePaymentLink')]
    public function testCreatePaymentLinkCharge($paymentLinkID)
    {
        $paymentLink = $this->commerce->createPaymentLinkCharge($paymentLinkID
            , [
            "local_amount" => 50000,
            "local_currency" => "NGN",
            "meta" => [
                "email" => "sarah.shaw@example.co",
                "name" => "Sarah Shaw",
                "phone" => "09011178899"
            ]
        ]);
        $this->assertEquals('success', $paymentLink->status);
    }

    #[Depends('testCreatePaymentLink')]
    public function testDeletePaymentLink($paymentLinkID)
    {
        $paymentLink = $this->commerce->deletePaymentLink($paymentLinkID);
        $this->assertEquals('success', $paymentLink->status);
    }
}
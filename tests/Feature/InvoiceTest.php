<?php

namespace Tests\Feature;

use Busha\Commerce\BushaCommerce;
use Busha\Commerce\Exceptions\InvalidPayloadException;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->commerce = new BushaCommerce();
    }

    public function testCreateInvoice()
    {
        $invoice = $this->commerce->createInvoice([
            "name" => "The Sovereign Individual",
            "customer_email" => "sarah.shaw@example.co",
            "local_amount" => 100.00,
            "local_currency" => "NGN",
            "customer_name" => "Sarah Shaw",
            "description" =>  "Mastering the Transition to the Information Age"
        ]);
        $this->assertEquals('success', $invoice->status);
        return $invoice->data->id;
    }

    #[Depends('testCreateInvoice')]
    public function testGetInvoice($invoiceID){
        $invoice = $this->commerce->getInvoice($invoiceID);
        $this->assertEquals('success', $invoice->status);
    }

    public function testListInvoices()
    {
        $invoices = $this->commerce->listInvoices(['limit' => 10,'page' => 1, 'sort' => 'asc']);
        $this->assertEquals('success', $invoices->status);
    }

    #[Depends('testCreateInvoice')]
    public function testCreateInvoiceCharge($invoiceID)
    {
        $invoice = $this->commerce->createInvoiceCharge($invoiceID, [
            "meta"=> [
                "email" => "sarah.shaw@example.co",
                "name" => "Sarah Shaw"
            ]
        ]);
        $this->assertEquals('success', $invoice->status);
    }

    #[Depends('testCreateInvoice')]
    public function testVoidInvoice($invoiceID)
    {
        $invoice = $this->commerce->createInvoice([
            "name" => "The Sovereign Individual",
            "customer_email" => "sarah.shaw@example.co",
            "local_amount" => 100.00,
            "local_currency" => "NGN",
            "customer_name" => "Sarah Shaw",
            "description" =>  "Mastering the Transition to the Information Age"
        ]);
        $this->assertEquals('success', $invoice->status);
        $deleted = $this->commerce->voidInvoice($invoice->data->id);
        $this->assertEquals('success', $deleted->status);
    }
}

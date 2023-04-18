<?php

namespace Tests\Feature;

use Busha\Commerce\BushaCommerce;
use Busha\Commerce\Exceptions\InvalidPayloadException;
use Busha\Commerce\Exceptions\ServerErrorException;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class ChargeTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->commerce = new BushaCommerce();
    }

    public function testCreateCharge()
    {
        $charge = $this->commerce->createCharge([
            'name' => 'Name of charge',
            'description' => 'Description',
            'local_currency' => 'NGN',
            'local_amount' => 100,
            'fixed_price' => true,
            "meta" => [
                "email"=>"sarah.shaw@example.co",
                "name" =>  "Sarah Shaw"
            ]
        ]);
        $this->assertEquals('success', $charge->status);
        return $charge;
    }

    #[Depends('testCreateCharge')]
    public function testGetCharge($charge)
    {
        $charge = $this->commerce->getCharge($charge->data->id);
        $this->assertEquals('success', $charge->status);
    }

    public function testListCharges()
    {
        $charge = $this->commerce->listCharges(['limit' => 10,'page' => 1, 'sort' => 'asc']);
        $this->assertEquals('success', $charge->status);
    }

    /**
     * @throws ServerErrorException
     */
    #[Depends('testCreateCharge')]
    public function testResolveCharge($charge)
    {
        //only unresolved charges can be resolved
        $this->expectException(InvalidPayloadException::class);
        $this->commerce->resolveCharge($charge->data->id,'no reason');
    }

    #[Depends('testCreateCharge')]
    public function testCancelCharge($charge)
    {
        $charge = $this->commerce->cancelCharge($charge->data->id);
        $this->assertEquals('success', $charge->status);
    }
}
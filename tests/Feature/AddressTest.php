<?php

namespace Tests\Feature;

use Busha\Commerce\BushaCommerce;
use Busha\Commerce\Exceptions\InvalidPayloadException;
use Busha\Commerce\Exceptions\ServerErrorException;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class AddressTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->commerce = new BushaCommerce();
    }

    public function testCreateAddress()
    {
        $address = $this->commerce->createAddress([
            'currency_id' => 'USDT',
            'chains' => array('TRX'),
            'label' => 'Test Address',
        ]);
        $this->assertEquals('success', $address->status);
        return $address;
    }

    /**
     * @throws ServerErrorException
     */
    #[Depends('testCreateAddress')]
    public function testGetAddress($address)
    {
        $address = $this->commerce->getAddress($address->data->id);
        $this->assertEquals('success', $address->status);
    }

    public function testListAddresses()
    {
        $addresses = $this->commerce->listAddresses(['limit' => 10,'page' => 1, 'sort' => 'asc']);
        $this->assertEquals('success', $addresses->status);
    }
}
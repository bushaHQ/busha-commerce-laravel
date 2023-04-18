<?php

namespace Tests\Feature;

use Busha\Commerce\BushaCommerce;
use Busha\Commerce\Exceptions\InvalidPayloadException;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class EventTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->commerce = new BushaCommerce();
    }

    public function testListEvent()
    {
        $event = $this->commerce->listEvents(['limit' => 10,'page' => 1, 'sort' => 'asc']);
        $this->assertEquals('success', $event->status);
    }
}
<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\TWDOrderService;
use Illuminate\Validation\ValidationException;

class TWDOrderServiceTest extends TestCase
{
    public function testProcessOrder(): void
    {
        $orderService = new TWDOrderService();
        $orderData = [
            'id' => 'A0000001',
            'name' => 'Melody Holiday Inn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road',
            ],
            'price' => '2000',
            'currency' => 'USD',
        ];

        $response = $orderService->processOrder($orderData);

        $this->assertEquals([
            'id' => 'A0000001',
            'name' => 'Melody Holiday Inn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road',
            ],
            'price' => 62000, // 200 * 31
            'currency' => 'TWD',
        ], $response);
    }

    public function testValidateOrderData()
    {
        $orderService = new TWDOrderService();
        $orderData = [
            'id' => 'A0000001',
            'name' => 'melody Holiday Inn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road',
            ],
            'price' => '2000',
            'currency' => 'TWD',
        ];

        $this->expectException(ValidationException::class);
        $orderService->validateOrderData($orderData);
    }
}

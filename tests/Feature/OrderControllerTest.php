<?php

namespace Tests\Feature;

use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    public function testValidTWDOrder(): void
    {
        $orderData = [
            'id' => 'A0000001',
            'name' => 'Melody Holiday Inn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road',
            ],
            'price' => '2000',
            'currency' => 'TWD',
        ];

        $response = $this->postJson('/api/orders', $orderData);
        $response->assertStatus(200);
        $response->assertJson([
            'id' => 'A0000001',
            'name' => 'Melody Holiday Inn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road',
            ],
            'price' => 2000,
            'currency' => 'TWD',
        ]);
    }

    public function testValidUSDOrder(): void
    {
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

        $response = $this->postJson('/api/orders', $orderData);
        $response->assertStatus(200);
        $response->assertJson([
            'id' => 'A0000001',
            'name' => 'Melody Holiday Inn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road',
            ],
            'price' => 62000,
            'currency' => 'TWD',
        ]);
    }

    public function testInvalidOrderName(): void
    {
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

        $response = $this->postJson('/api/orders', $orderData);
        $response->assertStatus(400);
        $response->assertJson([
            'name' => ['Name must contain only English characters and be capitalized.'],
        ]);
    }

    public function testInvalidOrderPrice(): void
    {
        $orderData = [
            'id' => 'A0000001',
            'name' => 'Melody Holiday Inn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road',
            ],
            'price' => '3000',
            'currency' => 'TWD',
        ];

        $response = $this->postJson('/api/orders', $orderData);
        $response->assertStatus(400);
        $response->assertJson([
            'price' => ['Price must not exceed 2000.'],
        ]);
    }

    public function testInvalidOrderCurrency(): void
    {
        $orderData = [
            'id' => 'A0000001',
            'name' => 'Melody Holiday Inn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road',
            ],
            'price' => '2000',
            'currency' => 'JSP',
        ];

        $response = $this->postJson('/api/orders', $orderData);
        $response->assertStatus(400);
        $response->assertJson([
            'currency' => ['Currency must be either TWD or USD.'],
        ]);
    }
}

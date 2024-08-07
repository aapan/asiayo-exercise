<?php

namespace Tests\Feature;

use Tests\TestCase;

class OrderRequestTest extends TestCase
{
    public function testRequiredFields(): void
    {
        $orderData = [];
        $response = $this->postJson('/api/orders', $orderData);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'id',
            'name',
            'address.city',
            'address.district',
            'address.street',
            'price',
            'currency',
        ]);
    }

    public function testInvalidFields(): void
    {
        $orderData = [
            'id' => 1,
            'name' => 1,
            'address' => [
                'city' => 1,
                'district' => 1,
                'street' => 1,
            ],
            'price' => 'invalid',
            'currency' => 1,
        ];
        $response = $this->postJson('/api/orders', $orderData);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'id',
            'name',
            'address.city',
            'address.district',
            'address.street',
            'price',
            'currency',
        ]);
    }
}

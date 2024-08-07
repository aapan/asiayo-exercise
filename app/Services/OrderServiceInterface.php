<?php

namespace App\Services;

interface OrderServiceInterface
{
    public function validateOrderData(array $data): void;
    public function processOrder(array $data): array;
}

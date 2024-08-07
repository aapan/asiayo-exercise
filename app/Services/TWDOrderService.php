<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Config;

use App\Services\OrderServiceInterface;


class TWDOrderService implements OrderServiceInterface
{
    public function validateOrderData(array $data): void
    {
        $rules = [
            'name' => 'regex:/^[A-Za-z\s]+$/|regex:/^[A-Z]/',
            'price' => 'numeric|max:2000',
            'currency' => 'in:TWD,USD',
        ];

        $messages = [
            'name.regex' => 'Name must contain only English characters and be capitalized.',
            'price.max' => 'Price must not exceed 2000.',
            'currency.in' => 'Currency must be either TWD or USD.',
        ];

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    public function processOrder(array $data): array
    {
        $data['price'] = (int) $data['price'];

        $currencyRates = Config::get('currency.rates');
        $targetCurrency = 'TWD';

        if (isset($currencyRates[$data['currency']][$targetCurrency])) {
            $rate = $currencyRates[$data['currency']][$targetCurrency];
            $data['price'] = $data['price'] * $rate;
            $data['currency'] = $targetCurrency;
        }

        return $data;
    }
}

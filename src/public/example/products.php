<?php

require "../../../vendor/autoload.php";

use Illuminate\Support\Collection;

$products = new Collection([
    [
        'id' => 11,
        'price' => 140,
        'name' => 'Fun headphone',
        'baseCurrency' => 'USD',
        'paymentCurrencies' => ['ADA', 'HOSKY'],
        'hero' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=3270&q=80',
    ],

    [
        'id' => 12,
        'price' => 100,
        'name' => 'Fun headphone',
        'baseCurrency' => 'USD',
        'paymentCurrencies' => ['HOSKY', 'ADA'],
        'hero' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=3270&q=80',
    ],
    [
        'id' => 13,
        'price' => 35,
        'name' => 'Shoes!',
        'baseCurrency' => 'EUR',
        'paymentCurrencies' => ['ADA', 'HOSKY'],
        'hero' => 'https://images.unsplash.com/photo-1560343090-f0409e92791a?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8NXx8cHJvZHVjdHxlbnwwfHwwfHw%3D&auto=format&fit=crop&w=900&q=60',
    ],
    [
        'id' => 14,
        'price' => 69,
        'name' => 'Another Product',
        'baseCurrency' => 'ADA',
        'paymentCurrencies' => ['ADA', 'HOSKY'],
        'hero' => null,
    ],
    [
        'id' => 15,
        'price' => 19,
        'name' => 'One more thing',
        'baseCurrency' => 'HOSKY',
        'paymentCurrencies' => ['HOSKY', 'ADA'],
        'hero' => null,
    ],
    [
        'id' => 16,
        'price' => 19,
        'name' => 'One more thing',
        'baseCurrency' => 'phuffycoin',
        'paymentCurrencies' => ['HOSKY', 'ADA'],
        'hero' => null,
    ],
]);

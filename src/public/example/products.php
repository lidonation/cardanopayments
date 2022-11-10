<?php

require "../../../vendor/autoload.php";

use Illuminate\Support\Collection;

$products = new Collection([
    [
        'id' => 11,
        'price' => 140,
        'name' => 'Fun headphone',
        'baseCurrency' => null,
        'paymentCurrencies' => ['lovelace'],
        'hero' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=3270&q=80'
    ],

    [
        'id' => 11,
        'price' => 140,
        'name' => 'Fun headphone',
        'baseCurrency' => 'lovelace',
        'paymentCurrencies' => ['lovelace'],
        'hero' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=3270&q=80'
    ],
    [
        'id' => 14,
        'price' => 35,
        'name' => 'Shoes!',
        'currency' => 'lovelace',
        'paymentCurrencies' => ['lovelace', 'hosky'],
        'hero' => 'https://images.unsplash.com/photo-1560343090-f0409e92791a?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8NXx8cHJvZHVjdHxlbnwwfHwwfHw%3D&auto=format&fit=crop&w=900&q=60',
    ],
    [
        'id' => 14,
        'price' => 69,
        'name' => 'Another Product',
        'currency' => 'euro',
        'paymentCurrencies' => ['lovelace', 'hosky'],
        'hero' => null,
    ],
    [
        'id' => 14,
        'price' =>19,
        'name' => 'One more thing',
        'currency' => 'usd',
        'paymentCurrencies' => ['hosky'],
        'hero' => null,
    ],
    [
        'id' => 14,
        'price' =>19,
        'name' => 'One more thing',
        'currency' => 'hosky',
        'paymentCurrencies' => ['hosky'],
        'hero' => null,
    ]
]);

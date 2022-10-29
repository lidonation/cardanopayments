<?php

use Lidonation\CardanoPayments\Services\ExchangeRateService;

it('can verify currency support', function () {
    $service = new ExchangeRateService();
    $supported = $service->verifyCurrencySupport(['ADA', 'USD', 'EUR', 'HOSKY']);
    $this->assertTrue($supported);
});

it('can get exchange rates', function () {
    $service = new ExchangeRateService();
    $pairs = [['ADA', 'USD'], ['ADA', 'HOSKY'], ['ADA', 'EUR']];

    foreach ($pairs as $pair) {
       $excObj = $service->getExchangeRate($pair[0], $pair[1]);
    //    $this->assertIsFloat($rate);
        expect($excObj->rate)->toBeNumeric();
    }

});

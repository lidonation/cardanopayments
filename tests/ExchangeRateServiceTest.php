<?php

use Lidonation\CardanoPayments\Services\ExchangeRateService;

it('can verify currency exchange', function () {
    $service = new ExchangeRateService();
    expect($service->verifyCurrencySupport(['ADA', 'USD', 'EUR', 'HOSKY']))->toBeTrue();
    expect($service->verifyCurrencySupport(['ADA', 'TZ', 'KSH', 'HOSKY']))->toBeFalse();
});

it('can get exchange rates', function () {
    $mock = mock(ExchangeRateService::class)->makePartial();
    $mock->shouldReceive('fetchHttpRates')
        ->andReturn([0.42, 0.46, 0.44, null])
        ->getMock();

    expect($mock->getExchangeRate('ADA', 'USD')->rate)->toEqual(0.44);
    expect(50 * ($mock->getExchangeRate('ADA', 'HOSKY')->rate))->toEqual(22);
});

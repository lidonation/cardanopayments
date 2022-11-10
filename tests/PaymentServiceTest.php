<?php
use Lidonation\CardanoPayments\Services\PaymentService;
use Lidonation\CardanoPayments\Services\ExchangeRateService;

it('can process payments', function () {
    $exService = new ExchangeRateService();
    $mockPy = mock(PaymentService::class)->makePartial();
    $mockPy->shouldReceive("getExchangeRate")
        ->andReturn(0.5)
        ->getMock();
    

    expect($mockPy->processPayment("USD", "lovelace", 10))->toEqual(5000000);
    expect($mockPy->processPayment("ADA", "lovelace", 10))->toEqual(10000000);
    expect($mockPy->processPayment("ADA", "HOSKY", 10))->toEqual(5);

});
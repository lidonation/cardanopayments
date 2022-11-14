<?php

use Lidonation\CardanoPayments\Services\PaymentService;

it("can process payments to either 'ADA' or 'HOSKY'", function () {
    $mockPy = mock(PaymentService::class)->makePartial();
    $mockPy->shouldReceive("getExchangeRate")
        ->andReturn(0.5)
        ->getMock();

    expect($mockPy->processPayment("USD", "ADA", 19))->toEqual(9.5);
    expect($mockPy->processPayment("ADA", "HOSKY", 10))->toEqual(5);
});

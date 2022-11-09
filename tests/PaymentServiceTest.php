<?php
use Lidonation\CardanoPayments\Services\ExchangeRateService;
use Lidonation\CardanoPayments\Services\PaymentService;

it('can convert to ADA', function () {
    $service = new ExchangeRateService();
    $mockPy = mock(PaymentService::class)->makePartial();
    $mockPy->shouldReceive("getAdaRate")
        ->andReturn(2.8)
        ->getMock();
    

    expect($mockPy->convertToAda("USD", 4))->toEqual(11.2);

});

it("can convert to lovelace", function() {
    $service =  new PaymentService();
    expect($service->convertToLovelace(5))->toEqual(5000000);
});

//assign walletId, $receiverAddress and $passPhrase to test transactions on the testnet
it('can service payments', function () {
    $walletId = "1c6c7d5c39573df8ee6c50b35cbd510b8a0334f1";
    $receiverAddress = "addr_test1qpree0hx36cyh9x8fgjv3e3286h0dutkh2pluk37kjwu2uzxd5qvkr8hg48q96mavlpa37rw909v6ppah90ntz2d3rws6796ra";
    $passPhrase = "test123456";

    
    $pay = new PaymentService();
    $payMwas = $pay->transactionInstance($walletId, $receiverAddress, $passPhrase);
   
    expect($pay->transactionSubmit($pay->transactionSign($pay->transactionConstruct(1000000))))->toBeString();
    // expect($payMwas->processPayment("ADA", "ADA", 0.5))->toBeString();
});
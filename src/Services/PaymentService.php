<?php

namespace Lidonation\CardanoPayments\Services;

class PaymentService
{
    public string $baseCurrency;
    public string $baseAmount;
    public string $paymentCurrency;
    public string $paymentMount;
    public array $allowedPaymentCurrencies = ['HOSKY', 'lovelace'];

    public function __construct()
    {
    }

    // process payment and return the payment amount in either of $allowedpaymentCurrencies
    public function processPayment(string $baseCurrency, string $paymentCurrency, $baseAmount, $paymentMount = null): float
    {
        if (in_array($paymentCurrency, $this->allowedPaymentCurrencies)) {
            $this->baseCurrency = $baseCurrency;
            $this->baseAmount = $baseAmount;
            $this->paymentCurrency = $paymentCurrency;

            // making sure we have $paymentCurrency and $paymentMount set
            if ($baseCurrency == $paymentCurrency) {
                $this->paymentMount = $paymentMount ?? $baseAmount;
            } else {
                if ($paymentCurrency == "HOSKY") {
                    $exRate = $this->getExchangeRate($baseCurrency, $paymentCurrency);
                    $this->paymentMount = $exRate * $baseAmount;
                } elseif ($paymentCurrency == "lovelace") {
                    $exRate = ($baseCurrency != "ADA") ? $this->getExchangeRate($baseCurrency, "ADA") : 1.0;
                    $this->paymentMount = $exRate * $baseAmount * 1000000;
                }
            }
        }

        return $this->paymentMount;
    }

    // use exchange rate service to get the rate of currency pair
    public function getExchangeRate(string $baseCurrency, string $quoteCurrency)
    {
        $rateObj = new ExchangeRateService($baseCurrency, $quoteCurrency);

        return  $rateObj->rate;
    }
}

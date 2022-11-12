<?php

namespace Lidonation\CardanoPayments\Services;

class PaymentService
{
    public string $baseCurrency;
    public string $baseAmount;
    public string $paymentCurrency;
    public string $paymentAmount;
    public array $allowedPaymentCurrencies = ['HOSKY', 'ADA'];

    public function __construct()
    {
    }

    
    /**
     * Process payment amount to one of the supported payment currencies in $allowePaymentCurrencies array property
     *
     * @param string $baseCurrency
     * @param string $quoteCurrency 
     * @param int||float $baseAmount
     * @param null||int||float $paymentAmount
     * 
     * @return int||float $this->paymentAmount
     */
    public function processPayment(string $baseCurrency, string $paymentCurrency, $baseAmount, $paymentAmount = null): float
    {
        if (in_array($paymentCurrency, $this->allowedPaymentCurrencies)) {
            $this->baseCurrency = $baseCurrency;
            $this->baseAmount = $baseAmount;
            $this->paymentCurrency = $paymentCurrency;

            // making sure we have $paymentCurrency and $paymentAmount set
            if (strcasecmp($baseCurrency, $paymentCurrency) == 0) {
                $this->paymentAmount = $paymentAmount ?? $baseAmount;
                return $this->paymentAmount;
            } else {
                    $exRate = $this->getExchangeRate($baseCurrency, $paymentCurrency);
                    $this->paymentAmount = $exRate * $baseAmount;
                    return $this->paymentAmount;
            }
        }
    }

    /**
     * Get exchange rate of currency pair.
     *
     * @param string $baseCurrency
     * @param string $quoteCurrency
     * 
     * @return float
     */
    public function getExchangeRate(string $baseCurrency, string $quoteCurrency)
    {
        $rateObj = new ExchangeRateService($baseCurrency, $quoteCurrency);

        return  $rateObj->rate;
    }
}

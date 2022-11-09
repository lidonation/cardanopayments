<?php

namespace Lidonation\CardanoPayments\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
// use Symfony\Component\Process\Process;

class PaymentService
{
    public $client;
    public $walletId;
    public $receiverAddress;
    public $passPhrase;
    public $transaction = [
        'baseCurrency' => null,
        'paymentCurrency' => null,
        'baseAmount' => null,
        'paymentMount' => null,
        'paymentMountADA' => null,
        'id' => null,
    ];

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'http://127.0.0.1:1024',
            'verify' => false
        ]);
    }

    public function transactionInstance($walletId, $receiverAddress, $passPhrase)
    {
        $this->walletId = $walletId;
        $this->receiverAddress = $receiverAddress;
        $this->passPhrase = $passPhrase;

        return $this;
    }

    public function processPayment(string $baseCurrency, string $paymentCurrency, $baseAmount, $paymentMount=null)
    {
        $this->transaction["baseCurrency"] = $baseCurrency;
        $this->transaction["paymentCurrency"] = $paymentCurrency;
        $this->transaction["baseAmount"] = $baseAmount;

        // making sure we have $paymentCurrency and $paymentMount set
        if ($baseCurrency == $paymentCurrency) {
            $this->transaction["paymentMount"] = $paymentMount ?? $baseAmount;
        } else {
            $rateObj = new ExchangeRateService($baseCurrency, $paymentCurrency);
            $this->transaction["paymentMount"] = $rateObj->rate * $baseAmount;
        }

        // converting our $paymentMount to ADA then to lovelace
        $this->transaction["paymentMountADA"] =  $this->convertToAda($this->transaction["paymentCurrency"], $this->transaction["paymentMount"]);
        $lovelaceMount = $this->convertToLovelace($this->transaction["paymentMountADA"]);

        // excecute the transaction and return transaction id
        $this->transaction["id"] = $this->executeTransaction($lovelaceMount);
        return $this->transaction["id"];
    }

    public function convertToAda(string $currency, $amount)
    {
        $rate = $this->getAdaRate($currency);
        return $rate * $amount;
    }

    public function convertToLovelace(float $adaAmount): float
    {
        return $adaAmount * 1000000;
    }

    public function getAdaRate(string $currency)
    {
        $exchObj = ($currency != "ADA") ? new ExchangeRateService($currency, "ADA") : 1.0;
        return  $exchObj->rate ??  $exchObj;
    }

    public function executeTransaction($lovelaceAmount)
    {
        $constructString = $this->transactionConstruct($lovelaceAmount);
        $signString = $this->transactionSign($constructString);
        if (!is_null($signString)) {
            $submitString = $this->transactionSubmit($signString);
        }
        

        return $submitString;

    }

    //returns transaction hash for the signing process's payload
    public function transactionConstruct($lovelaceAmount)
    {
        try {

            $payload = [
                "payments" => [
                      [
                         "address" => $this->receiverAddress, 
                         "amount" => [
                            "quantity" => $lovelaceAmount, 
                            "unit" => "lovelace" 
                         ] 
                      ] 
                ],
                "withdrawal" => "self"

             ];

            $path = "/v2/wallets/{$this->walletId}/transactions-construct";
            $response = $this->client->request("POST", $path, ['json'=>$payload]);
            if ($response->getStatusCode() == 200 || $response->getStatusCode() == 202) {
                $responseBody = json_decode($response->getBody(), true);
                return $responseBody['transaction'];
            } else {
                return null;
            }

        } catch (BadResponseException $e) {
            return true;
        }

    }

    // returns transaction hash for submit process's payload
    public function transactionSign(string $transaction)
    {   
        try {
            $payload = [
                'passphrase' => $this->passPhrase,
                'transaction' => $transaction
            ];
    
            $path = "/v2/wallets/{$this->walletId}/transactions-sign";
            $response =  $this->client->request("POST", $path, ['json'=>$payload]);
            if ($response->getStatusCode() == 200 || $response->getStatusCode() == 202) {
                $responseBody = json_decode($response->getBody(), true);
                return $responseBody['transaction'];
            } else {
                return null;
            }
        } catch (BadResponseException $e) {
            return null;
        }

    }

    // returns transaction id 
    public function transactionSubmit(string $transaction)
    {
        try {
            $payload = [
                'transaction' => $transaction
            ];
    
            $path = "/v2/wallets/{$this->walletId}/transactions-submit";
            $response =  $this->client->request("POST", $path, ['json'=>$payload]);
            if ($response->getStatusCode() == 200 || $response->getStatusCode() == 202) {
                $responseBody = json_decode($response->getBody(), true);
                return $responseBody['id'];
            } else {
                return null;
            }
        } catch (BadResponseException $e) {
            return null;
        }
    }
}
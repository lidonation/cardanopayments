<?php

namespace Lidonation\CardanoPayments\Services;

use Dotenv\Dotenv;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

class ExchangeRateService
{
    public $baseCurrencyName;

    public $baseCurrencySymbol;

    public $quoteCurrencyName;

    public $quoteCurrencySymbol;

    protected $supportedCurrencies = [
        'ADA' => '₳',
        'USD' => '$',
        'EUR' => '€',
        'HOSKY' => "HOSKY TOKEN",
    ];

    public $rate;

    public function __construct($base = null, $quote = null)
    {
        if (! is_null($base) && ! is_null($quote)) {
            $this->getExchangeRate($base, $quote);
        }

        // load enviroment variables
        $dotenv = Dotenv::createImmutable(dirname(dirname(dirname(__FILE__))));
        $dotenv->load();
    }

    public function getExchangeRate($base, $quote)
    {
        $isSupported = $this->verifyCurrencySupport([$base, $quote]);

        if ($isSupported) {
            $this->baseCurrencyName = $base;
            $this->quoteCurrencyName = $quote;

            $this->setCurrencySymbols($base, $quote);
            $this->getHttpRate($base, $quote);

            return $this;
        } else {
            echo "currency pairs in question need to be supported by this module.";
        };
    }

    //verify currency rate exchange support
    public function verifyCurrencySupport($currencies)
    {
        foreach ($currencies as $currency) {
            if (! array_key_exists($currency, $this->supportedCurrencies)) {
                return false;
            }
        }

        return true;
    }

    // sets object's currency symbols based on the query.
    protected function setCurrencySymbols($base, $quote): self
    {
        $this->baseCurrencySymbol = $this->supportedCurrencies[$this->baseCurrencyName];
        $this->quoteCurrencySymbol = $this->supportedCurrencies[$this->quoteCurrencyName];

        return $this;
    }

    // gets the exchange rate from multiple apis then assigns the public property 'rate' of this class
    protected function getHttpRate($b, $q): self
    {
        $ratesArray = $this->fetchHttpRates($b, $q);
        $validRates = [];
        foreach ($ratesArray as $rate) {
            if ($rate > 0 && is_float($rate)) {
                array_push($validRates, $rate);
            }
        }

        $avgRate = $this->calAvg($validRates);
        $this->rate = $avgRate;

        return $this;
    }

    // call multiple apis and return results in array
    public function fetchHttpRates($b, $q): array
    {
        $rateApiProviders = ['coinapi', 'coinbase', 'cryptocompare', 'coinmarketcap'];
        $ratesArray = [];

        foreach ($rateApiProviders as $provider) {
            $rate = $this->{$provider}($b, $q) ?? $this->rateFromInverse($provider, $b, $q);
            array_push($ratesArray, $rate);
        }

        return $ratesArray;
    }

    //calculates average rate from an array.
    protected function calAvg($rateArray)
    {
        return array_sum($rateArray) / count($rateArray);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // The following are api endpoints functions

    // get's rates if inverse pairs are provided but not the actual pairs
    public function rateFromInverse($endpointMethod, $b, $q)
    {
        $inverseRate = $this->{$endpointMethod}($q, $b);

        return ($inverseRate > 0) ? (float) (1 / $inverseRate) : null;
    }

    // endpoint
    public function coinapi($base, $quote)
    {
        try {
            $client = new Client([
                'base_uri' => 'https://rest.coinapi.io',
            ]);
            $api_key = env('API_KEY_COINAPI');
            $path = "/v1/exchangerate/" . $base . "/" . $quote;
            $headers = [
                'X-CoinAPI-Key' => $api_key,
            ];
            $response = $client->request('GET', $path, ['headers' => $headers]) ?? null;
            if ($response->getStatusCode() == 200) {
                $responseBody = json_decode($response->getBody(), true);

                return (float) $responseBody['rate'] ?? null;
            } else {
                return null;
            }
        } catch (BadResponseException $e) {
            return null;
        }
    }

    // endpoint
    public function coinbase($base, $quote)
    {
        try {
            $client = new Client([
                'base_uri' => 'https://api.coinbase.com',
            ]);
            // $api_key = '';
            $path = "/v2/exchange-rates?currency=" . $base;
            $response = $client->request('GET', $path);
            if ($response->getStatusCode() == 200) {
                $responseBody = json_decode($response->getBody(), true);
                if (array_key_exists($quote, $responseBody["data"]["rates"])) {
                    $rate = (float) $responseBody["data"]["rates"][$quote] ?? null;

                    return $rate > 0 ? $rate : null;
                } else {
                    return null;
                }
            } else {
                return null;
            }
        } catch (BadResponseException $e) {
            return null;
        }
    }

    // endpoint
    public function cryptocompare($base, $quote)
    {
        try {
            $client = new Client([
                'base_uri' => 'https://min-api.cryptocompare.com',
            ]);

            $api_key = 'notAMust';
            $path = "/data/price?fsym=" . $base . "&tsyms=" . $quote;
            $headers = [
                'X-CoinAPI-Key' => $api_key,
            ];
            $response = $client->request('GET', $path);
            if ($response->getStatusCode() == 200) {
                $responseBody = json_decode($response->getBody(), true);
                if (array_key_exists($quote, $responseBody)) {
                    $rate = (float) $responseBody[$quote];

                    return $rate > 0 ? $rate : null;
                } else {
                    return null;
                }
            } else {
                return null;
            }
        } catch (BadResponseException $e) {
            return null;
        }
    }

    // endpoint
    public function coinmarketcap($base, $quote)
    {
        try {
            $client = new Client([
                'base_uri' => 'https://api-mainnet-prod.minswap.org',
            ]);

            $path = "/coinmarketcap/v2/pairs";
            $response = $client->request('GET', $path);

            if ($response->getStatusCode() == 200) {
                $responseBody = json_decode($response->getBody(), true);

                foreach ($responseBody as $key => $value) {
                    if ($value['base_symbol'] == $base && $value['quote_symbol'] == $quote) {
                        $rate = (float) $value['last_price'];

                        return $rate > 0 ? $rate : null;
                    }
                }

                return null;
            } else {
                return null;
            }
        } catch (BadResponseException $e) {
            return null;
        }
    }

    // endpoint
    // public function mueliswap($base, $quote)
    // {
    //     try {
    //         $client = new Client([
    //             'base_uri' => 'http://analyticsv2.muesliswap.com'
    //         ]);

    //         // $api_key = 'open api no key';
    //         $path = "/ticker";
    //         $response = $client->request('GET', $path);

    //         if ($response->getStatusCode() == 200) {
    //             $responseBody = json_decode($response->getBody(), true);

    //             foreach ($responseBody as $key => $value) {
    //                 $pairString = explode(".", $key)[1];
    //                 $pairArray = explode("_", $pairString);

    //                 if ( count($pairArray) == 2) {
    //                     [$pairBase, $pairQuote] = $pairArray;

    //                     if (($pairBase == $base) && ($pairQuote == $quote)) {
    //                         $rate = (float) $value["last_price"];
    //                         return $rate > 0 ? $rate : null;
    //                     }

    //                 } else {
    //                     return null;
    //                 }
    //             }

    //         } else {
    //             return null;
    //         }
    //     } catch (BadResponseException $e) {
    //         return null;
    //     }

    // }
}

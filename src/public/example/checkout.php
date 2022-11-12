<?php require "../../../vendor/autoload.php";
use Lidonation\CardanoPayments\Services\PaymentService;

?><!DOCTYPE html>
<html class="h-full">
    <head>
        <script src="https://cdn.tailwindcss.com"></script>

        <script src="js/app.js"></script>

        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="h-full">
        <?php

        // excample product db
        include_once('products.php');

// get product id from query parameters
$productId = $_GET["product"];

// $Get base price and base currency from db
$product = $products->firstWhere('id', $productId);
$basePrice = $product['price'];
$baseCurrency= $product["baseCurrency"];

// $payments amounts and currencies (in ada || native tokens)
$pyService = new PaymentService();
$exchangableCurrencies = ['ADA', 'USD', 'EUR'];
$paymentAmounts = [];

if ( in_array($baseCurrency, $exchangableCurrencies)) {
    foreach ($product["paymentCurrencies"] as $paymentCurrency) {
        if ($baseCurrency == $paymentCurrency && $baseCurrency == "ADA") {
            $payment['currency'] = $baseCurrency;
            $payment['amount'] = $basePrice;
            array_push($paymentAmounts, $payment);
        } else {
            $payment['currency'] = $paymentCurrency;
            $payment['amount'] = $paymentAmount = $pyService->processPayment($baseCurrency, $paymentCurrency, $basePrice);
            array_push($paymentAmounts, $payment);
        }
    }
} else {
    $payment['currency'] = $baseCurrency;
    $payment['amount'] = $basePrice;
    array_push($paymentAmounts, $payment);
}

// send price and asset [ada || hosky || any other native token] to browser

// checkout ui
?><main class="mx-auto max-w-7xl py-16"><?php
include_once('../../resources/views/checkout.php');
?></main><?php





// build transaction in browser
// present transation to user for signature
// submit transaction to backend
// confirms transaction
// Alert browser
?>
<div>
   <? $paymentAmounts ?>
</div>

</body>
</html>



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

// $basePrice = Get the base price from db
foreach ($products as $product) {
    if ($product['id'] == $productId) {
        $basePrice = $product['price'];
        $baseCurrency = $product['baseCurrency'] ?? "USD";

        // @todo this should be a loop

        $paymentCurrency = $product['paymentCurrencies'] ?? "lovelace";

        // @todo loop should generation a collection of 1 ore more $paymentAmounts
        $paymentAmounts = [
            $currency ?? '0' => null,
            $currency ?? 1 => null,
        ];
    }
}

// checkout ui
?><main class="mx-auto max-w-7xl py-16"><?php
    $product = $products->firstWhere('id', $productId);
include_once('../../resources/views/checkout.php');
?></main><?php


// $paymentPrice[InLovelaces|hosky] = covert if needed to


$pyService = new PaymentService();
$paymentPrice = $pyService->processPayment($baseCurrency, $paymentCurrency, $basePrice);


// send price and asset [ada || hosky] to browser

// build transaction in browser
// present transation to user for signature
// submit transaction to backend
// confirms transaction
// Alert browser
?>

</body>
</html>



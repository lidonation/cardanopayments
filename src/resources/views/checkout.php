<section class="border-8 border-indigo-600 w-full p-6 rounded-md" x-data="dapp">
    <div class="flex flex-row flex-wrap justity-start gap-8">
        <div class="w-96">
            <?php include('../../resources/views/product.php'); ?>
        </div>
        <div>
            <h2 class="font-medium text-2xl xl:text-4xl">Checkout</h2>
            <div class="pt-4">
                <p>(ii) Please connect your wallet to complete checkout.</p>
                <div class="bg-indigo-400">
                    <?php include_once('../../resources/views/connect-wallet.php'); ?>
                </div>
            </div>
            <div x-show="walletBalance">
                <p>(i) Please choose payment currency</p>
                <?php
                    foreach($paymentAmounts as $payment):
                        include('../../resources/views/price.php');
                    endforeach
            ?>
            </div>
        </div>
    </div>
</section>
d
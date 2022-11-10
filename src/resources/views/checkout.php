<section class="border-8 border-indigo-600 w-full p-6 rounded-md" x-data="cardanoWallet">
    <div class="flex flex-row flex-wrap justity-start gap-6">
        <div class="w-96">
            <?php include('../../resources/views/product.php'); ?>
        </div>
        <div>
            <h2 class="font-medium text-2xl xl:text-4xl">Checkout</h2>
            <p>Please connect your wallet to complete checkout.</p>
            <div>
                <?php include_once('../../resources/views/connect-wallet.php'); ?>
            </div>
        </div>
    </div>
</section>

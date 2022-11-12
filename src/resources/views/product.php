<div class="group relative">
    <div class="min-h-80 aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-md bg-gray-200 group-hover:opacity-75 lg:aspect-none lg:h-80">
        <img src="<?php echo $product['hero']; ?>"
        alt="<?php echo $product['name']; ?>" class="h-full w-full object-cover object-center lg:h-full lg:w-full">
    </div>
    <div class="mt-4 flex justify-between">
        <h3 class="text-sm text-gray-700">
            <a href="checkout.php?product=<?php echo $product['id']; ?>">
                <span aria-hidden="true" class="absolute inset-0"></span>
                <?php echo $product['name']; ?>
            </a>
        </h3>
        <p class="text-sm font-medium text-gray-900">
            <span><?php echo $product['baseCurrency']; ?></span>
            <span><?php echo $product['price']; ?></span>
        </p>
    </div>
</div>

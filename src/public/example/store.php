<!DOCTYPE html>
<html class="h-full">
    <head>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="h-full" x-data="lidoPartners"></body>
            <div class="bg-white">
                <div class="mx-auto max-w-2xl py-16 px-4 sm:py-24 sm:px-6 lg:max-w-7xl lg:px-8">
                    <h2 class="text-2xl font-bold tracking-tight text-gray-900">Products</h2>

                    <div class="mt-6 grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                        <?php
                            include_once('products.php');
                            foreach($products as $product):
                                include('../../resources/views/product.php');
                            endforeach
                        ?>
                    </div>
                </div>
            </div>

            <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
            <script src="js/app.js"></script>
    </body>
</html>

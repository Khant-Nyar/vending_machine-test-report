<?php
include_partial('head');
include_partial('nav');
?>

<main>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">
            <?= htmlspecialchars($product['name'] ?? 'Product Detail') ?>
        </h1>

        <div class="mt-4">
            <!-- <p><strong>Description:</strong>
                <?= htmlspecialchars($product['description'] ?? 'No description available') ?>
            </p> -->
            <p><strong>Price:</strong>
                $<?= htmlspecialchars(number_format($product['price'], 2)) ?> USD
            </p>
            <p><strong>Stock:</strong>
                <?= htmlspecialchars($product['quantity_available'] ?? 'N/A') ?> available
            </p>
        </div>

        <div class="mt-6">
            <?php if (isset($_SESSION['user']) && checkRole('user')): ?>
            <form action="/products/purchase/<?= htmlspecialchars($product['slug']) ?>" method="POST"
                class="inline-flex items-center ml-4">
                <input type="number" name="quantity" min="1"
                    max="<?= htmlspecialchars($product['quantity_available']) ?>" required
                    class="mt-1 block w-20 border border-gray-300 rounded-md shadow-sm p-1 mr-2" placeholder="Qty">
                <button type="submit"
                    class="bg-blue-500 text-white px-2 py-1 rounded-lg hover:bg-blue-600">Purchase</button>
            </form>
            <?php endif; ?>
        </div>

        <a href="<?= urlIs('/products') ? 'products' : '/products' ?>"
            class="mt-4 inline-block text-blue-600 hover:underline">Back to Products</a>
    </div>
</main>

<?php include_partial('footer'); ?>
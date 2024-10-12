<?php
include_partial('head');
include_partial('nav');
?>

<main>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold mb-4">Edit Product: <?= htmlspecialchars($product['name']) ?></h2>
        <form action="/products/<?= htmlspecialchars($product['slug']) ?>" method="POST">
            <input type="hidden" name="_method" value="PUT">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                <input type="text" name="name" id="name" value="<?= htmlspecialchars($product['name']) ?>" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
            </div>
            <div class="mb-4">
                <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                <input type="number" name="price" id="price" value="<?= htmlspecialchars($product['price']) ?>" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" step="0.01">
            </div>
            <div class="mb-4">
                <label for="quantity_available" class="block text-sm font-medium text-gray-700">Quantity
                    Available</label>
                <input type="number" name="quantity_available" id="quantity_available"
                    value="<?= htmlspecialchars($product['quantity_available']) ?>" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Update
                Product</button>
        </form>
    </div>
</main>

<?php include_partial('footer'); ?>
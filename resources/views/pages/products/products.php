<?php
include_partial('head', ['header' => $heading]);
include_partial('nav');
include_partial('banner', ['heading' => $heading]);
?>
<main>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>

    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h2 class="text-2xl font-bold mb-4">Product List</h2>
            <?php if (checkRole('admin')): ?>
            <form action="/products/create" method="POST" class="mb-8">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                    <input type="text" name="name" id="name" required
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                        placeholder="Enter product name">
                </div>
                <div class="mb-4">
                    <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                    <input type="number" name="price" id="price" required
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                        placeholder="Enter price" step="0.01">
                </div>
                <div class="mb-4">
                    <label for="quantity_available" class="block text-sm font-medium text-gray-700">Quantity
                        Available</label>
                    <input type="number" name="quantity_available" id="quantity_available" required
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                        placeholder="Enter quantity available">
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Create
                    Product</button>
            </form>
            <?php else: ?>
            <!-- <p class="text-red-500">You do not have permission to create a product.</p> -->
            <?php endif; ?>

            <?php
            if (session()->has('success')) {
                echo '<div class="alert alert-success">' . session()->get('success') . '</div>';
                session()->unflash();
            }
            ?>
            <table id="datatable" class="table-auto w-full">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Product Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Quantity Available</th>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="/products/<?= htmlspecialchars($product['slug']) ?>"
                                class="text-blue-500 hover:underline">
                                <?= htmlspecialchars($product['name'] ?? 'Unknown Product') ?>
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            $<?= htmlspecialchars(number_format($product['price'], 2)) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?= htmlspecialchars($product['quantity_available'] ?? 0) ?>
                        </td>

                        <!-- Show actions based on user roles -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="mt-2">

                                <a href="/products/<?= htmlspecialchars($product['slug']) ?>"
                                    class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Detail</a>

                                <?php if (isset($_SESSION['user']) && checkRole('user')): ?>
                                <form action="/products/purchase/<?= htmlspecialchars($product['slug']) ?>"
                                    method="POST" class="inline-flex items-center ml-4">
                                    <input type="number" name="quantity" min="1"
                                        max="<?= htmlspecialchars($product['quantity_available']) ?>" required
                                        class="mt-1 block w-20 border border-gray-300 rounded-md shadow-sm p-1 mr-2"
                                        placeholder="Qty">
                                    <button type="submit"
                                        class="bg-blue-500 text-white px-2 py-1 rounded-lg hover:bg-blue-600">Purchase</button>
                                </form>
                                <?php endif; ?>

                                <?php if (checkRole('admin')): ?>
                                <a href="/products/<?= htmlspecialchars($product['slug']) ?>/edit"
                                    class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600">Edit</a>
                                <form action="/products/<?= htmlspecialchars($product['slug']) ?>" method="POST"
                                    style="display:inline;"
                                    onsubmit="return confirm('Are you sure you want to delete this product?');">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit"
                                        class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">Delete</button>
                                </form>
                                <?php endif; ?>
                            </div>

                        </td>

                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>


        </div>
    </div>
</main>

<?php include_partial('footer'); ?>
<!-- Include jQuery and DataTables scripts -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    $('#datatable').DataTable({
        // Add any customization options here
    });
});

const alert = document.getElementById('success-alert');
if (alert) {
    setTimeout(() => {
        alert.style.transition = 'opacity 0.5s ease';
        alert.style.opacity = '0';

        setTimeout(() => {
            alert.remove();
        }, 500);
    }, 3000);
}
</script>
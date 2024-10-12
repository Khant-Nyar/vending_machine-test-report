<?php

use App\Services\ProductService;
use bootstrap\Framework\Database\Database;
use PHPUnit\Framework\TestCase;

class ProductServiceTest extends TestCase
{
    protected $productService;

    protected function setUp(): void
    {
        $this->productService = new ProductService();
    }

    public function testGetAllProducts()
    {
        $products = $this->productService->getAllProducts(5);
        $this->assertIsArray($products);
    }

    public function testCreateProductSuccess()
    {
        $data = [
            'name' => 'New Product',
            'price' => 10.99,
            'quantity_available' => 100,
        ];

        $this->productService->createProduct($data);
        $product = $this->productService->getProductBySlug('new-product'); // Assuming slug is generated from the name
        $this->assertEquals($data['name'], $product['name']);
    }

    public function testPurchaseProductSuccess()
    {
        // Assume a product exists with slug 'existing-product'
        $slug = 'existing-product';
        $userId = 1;

        $this->productService->purchaseProduct($slug, 1, $userId);
        $product = $this->productService->getProductBySlug($slug);
        $this->assertEquals(99, $product['quantity_available']); // Assuming initial quantity was 100
    }
}

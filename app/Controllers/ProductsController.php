<?php

namespace App\Controllers;

use App\Services\ProductService;
use bootstrap\Framework\Request;
use bootstrap\Framework\Session;
use Exception;

class ProductsController
{
    protected $productService;

    public function __construct()
    {
        $this->productService = new ProductService();
    }

    public function index()
    {
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 15;
        $offset = ($currentPage - 1) * $limit;

        try {
            $products = $this->productService->getAllProducts($limit, $offset);
            $totalProducts = $this->productService->getProductCount();
            $totalPages = ceil($totalProducts / $limit);

            $heading = 'Product List';
            return view('pages/products/products', [
                'products' => $products,
                'heading' => $heading,
                'totalPages' => $totalPages,
                'currentPage' => $currentPage
            ]);
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function create(Request $request)
    {
        if ($request->method() === 'POST') {
            $data = [
                'name' => $request->field('name'),
                'price' => $request->field('price'),
                'quantity_available' => $request->field('quantity_available'),
            ];

            try {
                $this->productService->createProduct($data);
                Session::flash('success', 'Product created successfully!');
                header('Location: /products');
                exit;
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }

    public function show(Request $request, $slug)
    {
        try {
            $product = $this->productService->getProductBySlug($slug);
            if (!$product) {
                abort(404);
            }
            return view('pages/products/detail', ['product' => $product]);
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function edit(Request $request, $slug)
    {
        try {
            $product = $this->productService->getProductBySlug($slug);
            if (!$product) {
                abort(404);
            }
            return view('pages/products/edit', ['product' => $product]);
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function update(Request $request, $slug)
    {
        $data = [
            'name' => $request->field('name'),
            'price' => $request->field('price'),
            'quantity_available' => $request->field('quantity_available'),
        ];

        try {
            $this->productService->updateProduct($slug, $data);
            Session::flash('success', 'Product updated successfully!');
            header('Location: /products');
            exit;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function delete(Request $request, $slug)
    {
        try {
            $this->productService->deleteProduct($slug);
            Session::flash('success', 'Product deleted successfully!');
            header('Location: /products');
            exit;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function purchase(Request $request, $slug)
    {
        if ($request->method() === 'POST') {
            $quantity = $request->field('quantity');
            $userId = $_SESSION['user_id'];

            try {
                $this->productService->purchaseProduct($slug, $quantity, $userId);
                Session::flash('success', 'Purchase successful!');
                header('Location: /products');
                exit;
            } catch (Exception $e) {
                Session::flash('error', $e->getMessage());
                header('Location: /products');
                exit;
            }
        }
    }
}

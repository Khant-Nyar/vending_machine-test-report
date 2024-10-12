<?php

namespace App\Services;

use bootstrap\Framework\Database\Query;
use Exception;

class ProductService
{
    public function getAllProducts($limit, $offset)
    {
        try {
            return Query::from('products')
                ->orderBy('id', 'DESC')
                ->limit($limit)
                ->get();
        } catch (Exception $e) {
            throw new Exception("Error retrieving products: " . $e->getMessage());
        }
    }

    public function getProductCount()
    {
        try {
            return Query::from('products')->count();
        } catch (Exception $e) {
            throw new Exception("Error counting products: " . $e->getMessage());
        }
    }

    public function createProduct(array $data)
    {
        try {
            Query::from('products')->insertWithSlug($data);
        } catch (Exception $e) {
            throw new Exception("Error creating product: " . $e->getMessage());
        }
    }

    public function getProductBySlug($slug)
    {
        try {
            return Query::from('products')->where('slug', '=', $slug)->first();
        } catch (Exception $e) {
            throw new Exception("Error fetching product: " . $e->getMessage());
        }
    }

    public function updateProduct($slug, array $data)
    {
        try {
            return Query::from('products')
                ->where('slug', '=', $slug)
                ->update($data);
        } catch (Exception $e) {
            throw new Exception("Error updating product: " . $e->getMessage());
        }
    }

    public function deleteProduct($slug)
    {
        try {
            return Query::from('products')->where('slug', '=', $slug)->delete();
        } catch (Exception $e) {
            throw new Exception("Error deleting product: " . $e->getMessage());
        }
    }

    public function purchaseProduct($slug, $quantity, $userId)
    {
        $product = $this->getProductBySlug($slug);

        if ($quantity > $product['quantity_available']) {
            throw new Exception("Insufficient quantity available.");
        }

        $total = $product['price'] * $quantity;

        // Update product quantity
        $this->updateProduct($slug, [
            'quantity_available' => $product['quantity_available'] - $quantity
        ]);

        // Insert transaction
        Query::from('transactions')->insert([
            'user_id' => $userId,
            'product_id' => $product['id'],
            'quantity' => $quantity,
            'total' => $total
        ]);

        return $total;
    }
}

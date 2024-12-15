<?php

namespace App\Services\v1\Service;

interface ProductService
{
    public function getAllProducts();
    public function addProduct($product);
    public function getProductById($productId);
    public function updateProduct($productId, $newProduct);
    public function deleteProduct($productId);
}

<?php

namespace App\Services\v1\Impl;

use App\Models\Product;
use App\Models\ProductImages;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mockery\Exception;

class ProductServiceImpl implements \App\Services\v1\Service\ProductService
{

    protected $product;
    public function __construct()
    {
        $this->product = new Product();
    }

    public function getAllProducts()
    {
        return $this->product->getAllProducts();
    }

    public function addProduct($product)
    {
        $product['product_name'] = Str::snake($product['product_name']);

        $path = 'product/thumbnails/';

        $thumbnailName = processImage($product['thumbnail_image'], $path);

        if (strlen($thumbnailName) > 0)
        {
            $fullPathOfImage = rtrim($path, '/') . '/' . $thumbnailName;
            $test = trim($path, '/') . '/' . $thumbnailName;
            Log::info("Full Path $fullPathOfImage");
            Log::info("Full Path test $test");

            if (checkImageExist($fullPathOfImage))
            {

                $product['thumbnail_image'] = $thumbnailName;

                $product['product_slug'] = slugify($product['product_name']);

                // Start database transaction
                DB::beginTransaction();

                try
                {
                    $result = $this->product->addProduct($product);

                    if (isset($result) && $result->id)
                    {
                        // associate the tags to product

                        Log::info($result);
                        Log::info($product['product_tags']);

                        $result->tags()->attach($product['product_tags']);

                        // save the product images
                        if (array_key_exists('product_image', $product))
                        {
                            $imagePath = 'product/images';
                            $savedImages = [];
                            $failedImages = [];
                            foreach ($product['product_image'] as $image)
                            {
                                $imageName = processImage($image, $imagePath);

                                if (strlen($imageName) > 0)
                                {
                                    $savedImages[] = $imageName;
                                }
                                else{
                                    $failedImages[] = $image;
                                }

                            }

                            Log::info("Images store in array ==> ");
                            Log::info($savedImages);

                            if (! (count($failedImages) > 0))
                            {
                                try
                                {
                                    foreach ($savedImages as $img)
                                    {
                                        $productImage = new ProductImages();
                                        $productImage->create([
                                           'product_id' => $result->id,
                                           'product_image' => $img
                                        ]);
                                    }

                                    // Commit the transaction
                                    DB::commit();
                                    return $result; // Return the created product
                                }
                                catch (Exception $e)
                                {
                                    // remove images from local

                                    Log::error("Error while storing the images" . $e->getMessage());
                                }

                            }
                        }

                    }
                }
                catch (\Exception $e)
                {

                    // Rollback the transaction if an error occurs
                    DB::rollBack();

                    // now if the error was occur from db end then need to delete store image from app

                    // Delete the file
                    Storage::disk('public')->delete($fullPathOfImage);

                    // Log the error for debugging
                    Log::error("Error occurred while adding product: " . $e->getMessage());
                }
            }
        }

        return false;

    }

    public function getProductById($productId)
    {
        // TODO: Implement getProductById() method.
    }

    public function updateProduct($productId, $newProduct)
    {
        // TODO: Implement updateProduct() method.
    }

    public function deleteProduct($productId)
    {
        // TODO: Implement deleteProduct() method.
    }
}

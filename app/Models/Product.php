<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    protected $fillable = [
        'product_name',
        'short_description',
        'description',
        'price',
        'thumbnail_image',
        'product_slug',
    ];

    /*Relations*/

    public function images()
    {
        return $this->hasMany(ProductImages::class);
    }

    public function tags() : BelongsToMany
    {
        return $this->belongsToMany(ProductLabel::class, 'product_tag', 'product_id', 'tag_id');
    }

    /* Methods */
    public function getAllProducts()
    {
        return $this->select('product_name',
            'short_description',
            'description',
            'price',
            'thumbnail_image',
            'product_slug')->get();
    }

    public function addProduct($product)
    {
        return $this->create($product);
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

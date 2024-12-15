<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductLabel extends Model
{

    protected $fillable = [
        'product_tag'
    ];

    /*Relations*/
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_tag', 'tag_id', 'product_id');
    }


    /* Methods */
    public function getAllTags()
    {
        return $this->select('id', 'product_tag')->get();
    }

    public function addTag($data)
    {
        return $this->create($data);
    }

    public function getTagById($tagId)
    {
        return $this->select('id', 'product_tag')->where('id',$tagId)->first();
    }

    public function updateTag($tagId, $newTag)
    {
        return $this->where('id', $tagId)->update($newTag);
    }

    public function deleteTag($tagId)
    {
        return $this->where('id', $tagId)->delete();
    }


}

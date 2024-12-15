<?php

namespace App\Services\v1\Impl;

use App\Models\ProductLabel;
use Illuminate\Support\Str;

class TagServiceImpl implements \App\Services\v1\Service\TagService
{

    protected $tag;

    public function __construct()
    {
        $this->tag = new ProductLabel();
    }

    public function getAllTags()
    {
        return $this->tag->getAllTags();
    }

    public function addTag($tag)
    {
        $tag['product_tag'] = Str::snake($tag['product_tag']);

        return $this->tag->addTag($tag);
    }

    public function getTagById($tagId)
    {
        return $this->tag->getTagById($tagId);
    }

    public function updateTag($tagId, $newTag)
    {
        $tag = $this->getTagById($tagId);

        $newTag['product_tag'] = Str::snake($newTag['product_tag']);

        $this->tag->updateTag($tagId, $newTag);

        return $tag;
    }

    public function deleteTag($tagId)
    {
        $tag = $this->getTagById($tagId);

        $this->tag->deleteTag($tagId);

        return $tag;
    }
}

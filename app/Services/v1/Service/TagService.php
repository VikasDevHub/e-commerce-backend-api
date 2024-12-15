<?php

namespace App\Services\v1\Service;

interface TagService
{
    public function getAllTags();
    public function addTag($tag);
    public function getTagById($tagId);
    public function updateTag($tagId, $newTag);
    public function deleteTag($tagId);

}

<?php

namespace App\Service;

use App\Repository\TagRepository;

class TagService
{

    public function __construct( private readonly TagRepository $tagRepository )
    {

    }

    public function getMostUsedTags(): array
    {
        return $this->tagRepository->findMostUsedTags();
    }
}
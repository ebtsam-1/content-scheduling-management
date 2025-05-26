<?php

namespace App\Repositories;

use App\Models\Post;
use App\Models\Platform;


class PlatformRepository extends BaseRepository
{
    public function __construct(public Platform $platform)
    {   
        $searchColumns = 'title';
        parent::__construct($platform, $searchColumns);
    }
}




?>
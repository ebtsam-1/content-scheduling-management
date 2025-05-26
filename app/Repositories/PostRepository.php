<?php

namespace App\Repositories;

use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;

class PostRepository extends BaseRepository
{
    public function __construct(public Post $post)
    {   
        $searchColumns = 'title';
        parent::__construct($post, $searchColumns);
    }

   
}




?>
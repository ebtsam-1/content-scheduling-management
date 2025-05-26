<?php

namespace App\Repositories;

use App\Models\Post;
use App\Models\User;


class UserRepository extends BaseRepository
{
    public function __construct(public User $user)
    {   
        $searchColumns = 'name';
        parent::__construct($user, $searchColumns);
    }
}




?>
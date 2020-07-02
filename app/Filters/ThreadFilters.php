<?php

namespace App\Filters;

use App\User;
use Illuminate\Http\Request;

/**
 * Class ThreadFilters
 * @package App\Filters
 */
class ThreadFilters extends Filters
{
    protected $filters = ['by', 'popular'];

    /**
     * Filter the query by a given username
     * @param $username
     * @return mixed
     */
    public function by($username)
    {
        $user = User::where('name', $username)->firstOrFail();

        return $this->builder->where('user_id', $user->id);
    }

    /**
     * Filter the query accoring to most popular threads
     */
    public function popular()
    {
        $this->builder->getQuery()->orders = [];

        $this->builder->orderBy('replies_count', 'desc');
    }
}

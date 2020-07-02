<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Reply
 * @package App
 */
class Reply extends Model
{
    use Favoritable;

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var string[]
     */
    protected $with = ['owner','favorites'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

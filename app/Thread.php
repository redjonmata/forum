<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Thread
 * @package App
 */
class Thread extends Model
{
    use RecordsActivity;

    /**
     * Don't auto-apply mass assignment protection.
     * @var array
     */
    protected $guarded = [];

    /**
     * The relationships to always eager-load.
     * @var array
     */
    protected $with = ['creator','channel'];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // Whenever a thread is queried from the database, eager load the replies count
        static::addGlobalScope('replyCount', function ($builder) {
            $builder->withCount('replies');
        });

        // When a thread is deleted, also delete the replies of that thread
        static::deleting(function ($thread) {
            $thread->replies->each->delete();
        });
    }

    /**
     * Get a string path for the thread.
     * @return string
     */
    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->id}";
    }

    /**
     * Get the replies of the thread
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    /**
     * Get the creator of the thread
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    /**
     * Add reply to the thread
     * @param array $reply
     * @return Model
     */
    public function addReply($reply)
    {
        return $this->replies()->create($reply);
    }


    /**
     * Get the channel which this thread belongs to.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    /**
     * @param $query
     * @param $filters
     * @return mixed
     */
    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }
}

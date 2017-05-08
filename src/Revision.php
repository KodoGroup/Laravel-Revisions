<?php

namespace Kodo\Revisions;

use Illuminate\Database\Eloquent\Model;

class Revision extends Model
{
    protected $table = 'revisions';

    protected $fillable = [
        'user_id',
        'revisionable_type',
        'revisionable_id',
        'before',
        'after',
    ];

    protected $casts = [
        'before' => 'json',
        'after' => 'json',
    ];

    /**
     * Create a new Eloquent model instance.
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        $this->table = config('revision.table');
        
        parent::__construct($attributes);
    }

    /**
     * Relation to the user who has made the change.
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function user()
    {
        $model = config('auth.providers.'.config('auth.guards.'.config('auth.defaults.guard').'.provider').'.model');

        return $this->belongsTo($model, 'user_id');
    }
}

<?php

namespace Kodo\Revisions;

use Kodo\Revisions\Revision;

trait Revisionable
{
    /**
     * Boots the Revivision trait
     * @return void
     */
    public static function bootRevisionable()
    {
        static::updating(function ($model) {
            $model->revisions()->create([
                'user_id' => function_exists('auth') && auth()->check() ? auth()->user()->id : null,
                'before'  => array_intersect_key($model->fresh()->toArray(), $model->getDirty()),
                'after'   => $model->getDirty(),
            ]);
        });
    }

    /**
     * fetch and query revisions of the model
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function revisions()
    {
        return $this->morphMany(Revision::class, 'revisionable');
    }
}

<?php

namespace Ruth\Revisions;

use Illuminate\Support\ServiceProvider;

class RevisionServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/revision.php' => config_path('revision.php'),
        ], 'revision');

        $this->loadMigrationsFrom(__DIR__.'/../migrations');
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/revision.php', 'revision'
        );
    }
}

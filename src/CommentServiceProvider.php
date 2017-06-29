<?php

namespace Finagin\Comment;

use Illuminate\Support\ServiceProvider;

class CommentServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes(
            [
                __DIR__.'/../config/laravel-comment.php' => config_path('/laravel-comment.php'),
            ],
            'config'
        );

        if (! class_exists('CreateCommentsTables')) {
            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__.'/../database/migrations/create_comments_tables.php.stub' => database_path("/migrations/{$timestamp}_create_comments_tables.php"),
            ], 'migrations');
        }

        $this->registerModelBindings();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-comment.php', 'laravel-comment');
    }

    protected function registerModelBindings()
    {
        $models = config('laravel-comment.models');

        foreach ($models as $model) {
            $this->app->bind($model['contractClass'], $model['modelClass']);
        }
    }
}

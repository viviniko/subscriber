<?php

namespace Viviniko\Subscriber;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Viviniko\Subscriber\Console\Commands\SubscriberTableCommand;

class SubscriberServiceProvider extends BaseServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config files
        $this->publishes([
            __DIR__.'/../config/subscriber.php' => config_path('subscriber.php'),
        ]);

        // Register commands
        $this->commands('command.subscriber.table');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/subscriber.php', 'subscriber');

        $this->registerRepositories();

        $this->registerSubscriberService();

        $this->registerCommands();
    }

    public function registerRepositories()
    {
        $this->app->singleton(
            \Viviniko\Subscriber\Repositories\Subscriber\SubscriberRepository::class,
            \Viviniko\Subscriber\Repositories\Subscriber\EloquentSubscriber::class
        );
    }

    /**
     * Register the artisan commands.
     *
     * @return void
     */
    private function registerCommands()
    {
        $this->app->singleton('command.subscriber.table', function ($app) {
            return new SubscriberTableCommand($app['files'], $app['composer']);
        });
    }

    /**
     * Register the subscriber service provider.
     *
     * @return void
     */
    protected function registerSubscriberService()
    {
        $this->app->singleton(
            \Viviniko\Subscriber\Contracts\SubscriberService::class,
            \Viviniko\Subscriber\Services\SubscriberServiceImpl::class
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            \Viviniko\Subscriber\Contracts\SubscriberService::class,
        ];
    }
}
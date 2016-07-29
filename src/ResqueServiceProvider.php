<?php
namespace Hlgrrnhrdt\Resque;

use Hlgrrnhrdt\Resque\Console\WorkCommand;
use Illuminate\Support\ServiceProvider;

/**
 * ResqueServiceProvider
 *
 * @author Holger Reinhardt <hlgrrnhrdt@gmail.com>
 */
class ResqueServiceProvider extends ServiceProvider
{
    /**
     *
     */
    public function boot()
    {
        $connection = $this->app['config']['resque.connection'];
        \Resque::setBackend($connection['server'], $connection['db']);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerResque();
        $this->registerWorkCommand();
    }

    protected function registerResque()
    {
        $this->app->singleton('resque', function () {
            return new Resque();
        });
    }

    protected function registerWorkCommand()
    {
        $this->app->singleton('command.resque.work', function () {
            return new WorkCommand($this->app->make('resque.manager'));
        });
        $this->commands('command.resque.work');
    }
}

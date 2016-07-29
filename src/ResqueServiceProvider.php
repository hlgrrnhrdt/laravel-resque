<?php
namespace Hlgrrnhrdt\Resque;

use Hlgrrnhrdt\Resque\Console\WorkCommand;
use Illuminate\Support\ServiceProvider;
use Resque;

/**
 * ResqueServiceProvider
 *
 * @author Holger Reinhardt <hlgrrnhrdt@gmail.com>
 */
class ResqueServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerManager();
        $this->registerWorkCommand();
    }

    protected function registerManager()
    {
        $this->app->singleton('resque.manager', function () {
            $config = $this->app['config']['resque.connection'];

            $resque = new Resque();
            $resque->setBackend($config['server'], $config['db']);

            return new ResqueManager($resque, $this->app['config']['resque.trackStatus']);
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

<?php
namespace Hlgrrnhrdt\Resque;

use Config;
use Hlgrrnhrdt\Resque\Console\WorkCommand;
use Illuminate\Contracts\Foundation\Application;
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
        $this->app->singleton('resque.manager', function (Application $app) {
            $config = $app['config']['resque.connection'];

            $resque = new Resque();
            $resque->setBackend(implode(':', [$config['host'], $config['port']]), $config['db']);

            return new ResqueManager($resque, $app['config']['resque.trackStatus']);
        });
    }

    protected function registerWorkCommand()
    {
        $this->app->singleton('command.resque.work', function (Application $app) {
            return new WorkCommand($app->make('resque.manager'));
        });
    }
}

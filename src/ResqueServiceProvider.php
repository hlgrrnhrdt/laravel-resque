<?php
namespace Hlgrrnhrdt\Resque;

use Config;
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
        $this->app->singleton(ResqueManager::class, function ($app) {
            $config = $app['config']['resque.connection'];
            $resque = new Resque();
            $resque->setBackend(implode(':', [$config['host'], $config['port']]), $config['db']);
            return new ResqueManager($resque);
        });
    }

    protected function registerWorkCommand()
    {
        $this->app->singleton('command.resque.work', function () {
            return new WorkCommand();
        });
    }
}

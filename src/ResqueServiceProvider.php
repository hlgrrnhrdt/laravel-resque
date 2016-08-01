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
        $this->setRedisConfig();
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
        $this->app->singleton(Resque::class, function () {
            $prefix = $this->app['config']['resque.prefix'];
            return (new Resque())->setPrefix($prefix ?: 'resque');
        });
    }

    protected function registerWorkCommand()
    {
        $this->app->singleton('command.resque.work', function () {
            return new WorkCommand($this->app->make(Resque::class));
        });
        $this->commands('command.resque.work');
    }

    protected function setRedisConfig()
    {
        $config = $this->app['config']['database.redis.default'];

        $host = isset($config['host']) ? $config['host'] : 'localhost';
        $port = isset($config['port']) ? $config['port'] : 6379;
        $database = isset($config['database']) ? $config['database'] : 0;
        $password = isset($config['password']) ? $config['password'] : '';

        $server = implode(':', [$host, $port]);
        if ($password) {
            $server = implode('@', [$password, $server]);
        }

        $dsn = sprintf('redis://%s/%s', $server, $database);

        \Resque::setBackend($dsn);
    }
}

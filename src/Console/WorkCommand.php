<?php
namespace Hlgrrnhrdt\Resque\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

/**
 * WorkCommand
 *
 * @author Holger Reinhardt <hlgrrnhrdt@gmail.com>
 */
class WorkCommand extends Command
{
    protected $name = 'resque:work';

    public function fire()
    {
        $queue = $this->option('queue');
        $interval = $this->option('interval');
        $count = $this->option('count');
    }

    /**
     * {@inheritdoc}
     */
    protected function getOptions()
    {
        return [
            ['queue', null, InputOption::VALUE_IS_ARRAY & InputOption::VALUE_OPTIONAL, '', 'default'],
            ['interval', null, InputOption::VALUE_OPTIONAL, '', 5],
            ['count', null, InputOption::VALUE_OPTIONAL, '', 1],
        ];
    }


}

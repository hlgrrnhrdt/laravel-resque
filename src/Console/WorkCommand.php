<?php
namespace Hlgrrnhrdt\Resque\Console;

use Hlgrrnhrdt\Resque\Resque;
use Illuminate\Console\Command as IlluminateCommand;
use Resque_Worker;
use Symfony\Component\Console\Input\InputOption;

/**
 * WorkCommand
 *
 * @author Holger Reinhardt <holger.reinhardt@aboutyou.de>
 */
class WorkCommand extends IlluminateCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'resque:work';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run a resque worker';

    /**
     * @var \Hlgrrnhrdt\Resque\Resque
     */
    private $resque;

    /**
     * @param \Hlgrrnhrdt\Resque\Resque $resque
     */
    public function __construct(Resque $resque)
    {
        parent::__construct();
        $this->resque = $resque;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function fire()
    {
        $queue = $this->option('queue');
        $interval = (int)$this->option('interval');

        $queues = explode(',', $queue);

        $this->startWorker($queues, $interval);

        return 0;
    }

    /**
     * @param array $queues
     * @param int   $interval
     */
    private function startWorker(array $queues, $interval = 5)
    {
        $worker = new Resque_Worker($queues);
        $this->info(\sprintf('Starting worker %s', $worker));
        $worker->work($interval);
    }

    /**
     * {@inheritdoc}
     */
    protected function getOptions()
    {
        return [
            ['queue', null, InputOption::VALUE_OPTIONAL, 'The queue to work on', 'default'],
            ['interval', null, InputOption::VALUE_OPTIONAL, 'Amount of time to delay failed jobs', 5],
        ];
    }
}

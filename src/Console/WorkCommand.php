<?php
namespace Hlgrrnhrdt\Resque\Console;

use Hlgrrnhrdt\Resque\ResqueManager;
use Illuminate\Console\Command as IlluminateCommand;
use Resque_Worker;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

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
     * @var \Hlgrrnhrdt\Resque\ResqueManager
     */
    private $manager;

    /**
     * @param \Hlgrrnhrdt\Resque\ResqueManager $manager
     */
    public function __construct(ResqueManager $manager)
    {
        parent::__construct();
        $this->manager = $manager;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function fire()
    {
        $queues = $this->option('queue');
        $interval = (int)$this->option('interval');
        $count = (int)$this->option('count');

        $logLevel = $this->getLogLevel();

        if ($count > 1) {
            for ($i = 0; $i < $count; $i++) {
                try {
                    $pid = $this->manager->fork();
                } catch (\RuntimeException $exception) {
                    $this->error($exception->getMessage());

                    return 1;
                }

                if (0 === $pid) {
                    $this->startWorker($queues, $interval, $logLevel);
                }
            }
        } else {
            $this->startWorker($queues, $interval, $logLevel);
        }

        return 0;
    }

    /**
     * @param array $queues
     * @param int   $logLevel
     * @param int   $interval
     */
    private function startWorker(array $queues, $interval = 5, $logLevel = Resque_Worker::LOG_NONE)
    {
        $queues = array_map(function ($queue) {
            return $this->manager->getQueueName($queue);
        }, $queues);

        $worker = new Resque_Worker($queues);
        $worker->logLevel = $logLevel;

        $this->info(sprintf('Starting worker %s', $worker));
        $worker->work($interval);
    }

    /**
     * @return int
     */
    protected function getLogLevel()
    {
        switch ($this->verbosity) {
            case OutputInterface::VERBOSITY_VERBOSE:
                $logLevel = Resque_Worker::LOG_NORMAL;
                break;

            case OutputInterface::VERBOSITY_VERY_VERBOSE:
                $logLevel = Resque_Worker::LOG_VERBOSE;
                break;

            default:
                $logLevel = Resque_Worker::LOG_NONE;
        }

        return $logLevel;
    }

    /**
     * {@inheritdoc}
     */
    protected function getOptions()
    {
        return [
            [
                'queue',
                null,
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL,
                'The queue to work on',
                ['default'],
            ],
            ['interval', null, InputOption::VALUE_OPTIONAL, 'The queue to work on', 5],
            ['count', null, InputOption::VALUE_OPTIONAL, 'The queue to work on', 1],
        ];
    }


}

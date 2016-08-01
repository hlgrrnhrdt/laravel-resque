<?php
namespace Hlgrrnhrdt\Resque;

/**
 * Worker
 *
 * @author Holger Reinhardt <holger.reinhardt@aboutyou.de>
 */
class Worker
{
    /**
     * @var \Resque_Worker
     */
    private $worker;

    /**
     * @param \Resque_Worker $worker
     */
    public function __construct(\Resque_Worker $worker)
    {
        $this->worker = $worker;
    }

    /**
     * @return string
     */
    public function id()
    {
        return (string)$this->worker;
    }

    /**
     * @return bool
     */
    public function stop()
    {
        list(, $pid,) = \explode(':', $this->id());
        return \posix_kill($pid, 3);
    }

    /**
     * @return Queue[]
     */
    public function queues()
    {
        $queues = \array_map(function ($queue) {
            return new Queue($queue);
        }, $this->worker->queues());

        return $queues;
    }
}

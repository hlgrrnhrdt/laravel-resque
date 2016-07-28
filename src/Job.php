<?php
namespace Hlgrrnhrdt\Resque;

/**
 * Job
 *
 * @author Holger Reinhardt <hlgrrnhrdt@gmail.com>
 */
abstract class Job
{
    /**
     * @var string
     */
    protected $queue = 'default';

    /**
     * @var array
     */
    protected $arguments = [];

    /**
     * @return mixed
     */
    public function queue()
    {
        return $this->queue;
    }

    /**
     * @return array
     */
    public function arguments()
    {
        return $this->arguments;
    }

    /**
     * @param string $queue
     *
     * @return Job
     */
    public function onQueue($queue)
    {
        $this->queue = $queue;
        return $this;
    }
}

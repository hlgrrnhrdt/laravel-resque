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
     * @var \Resque_Job
     */
    public $job;

    /**
     * @var string
     */
    public $queue = 'default';

    /**
     * @var array
     */
    public $args = [];

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
        return $this->$args;
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

    abstract public function perform();
}

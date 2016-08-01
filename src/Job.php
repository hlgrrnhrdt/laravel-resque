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
        return $this->args ?: [];
    }

    /**
     * @return string
     */
    public function name()
    {
        return \get_class($this);
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

    /**
     * @param Job $job
     *
     * @return bool
     */
    public function equals(Job $job)
    {
        return $this->name() === $job->name() && $this->arguments() === $job->arguments();
    }

    abstract public function perform();
}

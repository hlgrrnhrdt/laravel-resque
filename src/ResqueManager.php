<?php
namespace Hlgrrnhrdt\Resque;

use Resque;
use RuntimeException;

/**
 * Resque
 *
 * @author Holger Reinhardt <hlgrrnhrdt@gmail.com>
 */
class ResqueManager
{
    /**
     * @var Resque
     */
    protected $resque;

    /**
     * @var string
     */
    protected $queuePrefix;

    /**
     * @var bool
     */
    protected $trackStatus = false;

    /**
     * ResqueManager constructor.
     *
     * @param \Resque $resque
     * @param string  $queuePrefix
     * @param bool    $trackStatus
     */
    public function __construct(Resque $resque, $queuePrefix, $trackStatus = false)
    {
        $this->resque = $resque;
        $this->trackStatus = $trackStatus;
        $this->queuePrefix = $queuePrefix;
    }

    /**
     * @param Job  $job
     * @param bool $trackStatus
     *
     * @return null|\Resque_Job_Status
     */
    public function enqueue(Job $job, $trackStatus = false)
    {
        $id = $this->resque->enqueue($this->getQueueNameFromJob($job), get_class($job), $job->arguments(), $trackStatus);

        if (true === $trackStatus) {
            return new \Resque_Job_Status($id);
        }

        return null;
    }

    /**
     * @param Job  $job
     * @param bool $trackStatus
     *
     * @return null|\Resque_Job_Status
     */
    public function enqueueOnce(Job $job, $trackStatus = false)
    {
        $queue = new Queue($this->getQueueNameFromJob($job));

        foreach ($queue->jobs() as $queuedJob) {
            if (true === $this->isDuplicateJob($job, $queuedJob)) {
                return ($trackStatus) ? new \Resque_Job_Status($queuedJob->payload['id']) : null;
            }
        }

        return $this->enqueue($job, $trackStatus);
    }

    /**
     * @return \Resque_Redis
     */
    public function redis()
    {
        return $this->resque->redis();
    }

    /**
     * @return int
     *
     * @throws RuntimeException
     */
    public function fork()
    {
        if (false === function_exists('pcntl_fork')) {
            return -1;
        }

        $pid = pcntl_fork();
        if (-1 === $pid) {
            throw new RuntimeException('Unable to fork child worker.');
        }

        return $pid;
    }

    /**
     * @param \Hlgrrnhrdt\Resque\Job $job
     * @param                        $queuedJob
     *
     * @return bool
     */
    private function isDuplicateJob(Job $job, \Resque_Job $queuedJob)
    {
        return $queuedJob->payload['class'] === get_class($queuedJob)
            && count(array_intersect($queuedJob->getArguments(), $job->arguments())) === count($job->arguments());
    }

    private function getQueueNameFromJob(Job $job)
    {
        $queue = $job->queue();

        return $this->getQueueName($queue);
    }

    /**
     * @param string $queue
     *
     * @return string
     */
    public function getQueueName($queue)
    {
        if ($this->queuePrefix) {
            $queue = implode(':', [$this->queuePrefix, $queue]);
        }

        return $queue;
    }
}

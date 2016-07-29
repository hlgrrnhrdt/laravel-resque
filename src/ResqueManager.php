<?php
namespace Hlgrrnhrdt\Resque;

use Resque;
use Resque_Worker;
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
     * @var bool
     */
    protected $trackStatus = false;

    /**
     * ResqueManager constructor.
     *
     * @param \Resque $resque
     * @param bool    $trackStatus
     */
    public function __construct(Resque $resque, $trackStatus = false)
    {
        $this->resque = $resque;
        $this->trackStatus = $trackStatus;
    }

    /**
     * @param Job  $job
     * @param bool $trackStatus
     *
     * @return null|\Resque_Job_Status
     */
    public function enqueue(Job $job, $trackStatus = false)
    {
        $id = $this->resque->enqueue($job->queue(), get_class($job), $job->arguments(), $trackStatus);

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
        $queue = new Queue($job->queue());

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
}

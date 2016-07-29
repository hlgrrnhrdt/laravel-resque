<?php
namespace Hlgrrnhrdt\Resque;

use RuntimeException;

/**
 * Resque
 *
 * @author Holger Reinhardt <hlgrrnhrdt@gmail.com>
 */
class Resque
{
    /**
     * @var string
     */
    protected $prefix;

    /**
     * @var bool
     */
    protected $trackStatus = false;

    /**
     * @param string $prefix
     */
    public function setPrefix($prefix)
    {
        \Resque_Redis::prefix($prefix);
    }

    /**
     * @param Job  $job
     * @param bool $trackStatus
     *
     * @return null|\Resque_Job_Status
     */
    public function enqueue(Job $job, $trackStatus = false)
    {
        $id = \Resque::enqueue($job->queue(), $job->name(), $job->arguments(), $trackStatus);

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
            if (true === $this->isSameJob($job, $queuedJob)) {
                return ($trackStatus) ? new \Resque_Job_Status($queuedJob->job->payload['id']) : null;
            }
        }

        return $this->enqueue($job, $trackStatus);
    }

    /**
     * @return \Resque_Redis
     */
    public function redis()
    {
        return \Resque::redis();
    }

    /**
     * @return int
     *
     * @throws RuntimeException
     */
    public function fork()
    {
        return \Resque::fork();
    }

    /**
     * @param Job $job
     * @param Job $queuedJob
     *
     * @return bool
     */
    protected function isSameJob(Job $job, Job $queuedJob)
    {
        return $job->name() === $queuedJob->name()
            && count(array_intersect($job->arguments(), $queuedJob->arguments())) === count($job->arguments());
    }
}

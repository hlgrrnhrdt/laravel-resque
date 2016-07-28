<?php
namespace Hlgrrnhrdt\Resque;

use Resque as PhpResque;

/**
 * Resque
 *
 * @author Holger Reinhardt <hlgrrnhrdt@gmail.com>
 */
class ResqueManager
{
    /**
     * @var PhpResque
     */
    private $resque;

    public function __construct(PhpResque $resque)
    {
        $this->resque = $resque;
    }

    public function enqueue(Job $job)
    {
        $this->resque->enqueue($job->queue(), get_class($job), $job->arguments());
    }

    public function enqueueOnce(Job $job)
    {
        $queue = new Queue($job->queue());
        
    }
}

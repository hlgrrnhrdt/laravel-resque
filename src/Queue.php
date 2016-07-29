<?php
namespace Hlgrrnhrdt\Resque;

/**
 * Queue
 *
 * @author Holger Reinhardt <hlgrrnhrdt@gmail.com>
 */
class Queue
{
    protected $name;
    /**
     * @var ResqueManager
     */
    private $manager;

    /**
     * Queue constructor.
     *
     * @param ResqueManager $manager
     */
    public function __construct(ResqueManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return \Resque_Job[]
     */
    public function jobs()
    {
        $result = $this->manager->redis()->lrange('queue:' . $this->name, 0, -1);
        $jobs = [];
        foreach ($result as $job) {
            $jobs[] = new \Resque_Job($this->name, json_decode($job, true));
        }

        return $jobs;
    }
}

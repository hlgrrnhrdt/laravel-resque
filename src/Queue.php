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

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function jobs()
    {
        \Resque::redis()->lrange('queue:' . $this->name, 0, -1);
    }
}

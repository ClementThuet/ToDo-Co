<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase{

    public function testConstructTask()
    {
        $task = new Task();
        $this->assertEquals(true, is_a($task->getCreatedAt(),'DateTime'));
        $this->assertEquals(false, $task->isDone());
    }
}

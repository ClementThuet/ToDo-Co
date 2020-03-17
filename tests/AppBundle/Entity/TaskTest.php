<?php

namespace Tests\AppBundle\Entity;
use AppBundle\Entity\Task;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskTest extends WebTestCase{

    public function testConstructTask()
    {
        $task = new Task();
        $this->assertEquals(true, is_a($task->getCreatedAt(),'DateTime'));
        $this->assertEquals(false, $task->isDone());
    }
}

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
    
    public function testUpdateTask()
    {
        $user = new Task();
        $user->setTitle('Lorem Ipsum');
        $user->setContent('Sit dolore');
        $titleTask = $user->getTitle();
        $contentTask = $user->getContent();
        $this->assertEquals($titleTask, "Lorem Ipsum");
        $this->assertEquals($contentTask, "Sit dolore");
    }
}

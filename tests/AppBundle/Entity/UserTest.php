<?php

namespace Tests\AppBundle\Entity;
use AppBundle\Entity\User;
use AppBundle\Entity\Task;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserTest extends WebTestCase{

    public function testConstructUser()
    {
        $user = new User();
        $this->assertEquals(true, $user->getTasks() instanceof ArrayCollection);
    }
    
}

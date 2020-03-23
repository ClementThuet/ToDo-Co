<?php

namespace App\Tests\Entity;

use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase{

    public function testConstructUser()
    {
        $user = new User();
        $this->assertEquals(true, $user->getTasks() instanceof ArrayCollection);
    }
    
}

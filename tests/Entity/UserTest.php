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
    
    public function testUpdateUser()
    {
        $user = new User();
        $user->setUserName('John Test');
        $user->setEmail('test@email.com');
        $emailUser = $user->getEmail();
        $usernameUser = $user->getUsername();
        $this->assertEquals($emailUser, "test@email.com");
        $this->assertEquals($usernameUser, "John Test");
    }
}

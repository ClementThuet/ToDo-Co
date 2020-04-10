<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
class BaseFixtures extends Fixture
{
    private $encoder;
    
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    
    public function load(ObjectManager $manager)
    {
        $userAnonymous = new User();
        $userAnonymous->setUsername('ANONYMOUS');
        $userAnonymous->setPassword($this->encoder->encodePassword($userAnonymous, 'test'));
        $userAnonymous->setEmail("anonymous@anonymous.com");
        $userAnonymous->setRoles(["ROLE_USER"]);
        
        $user = new User();
        $user->setUsername('Clément');
        $user->setPassword($this->encoder->encodePassword($user, 'test'));
        $user->setEmail("clementthuet7@gmail.com");
        $user->setRoles(["ROLE_ADMIN", "ROLE_USER"]);
        
        $user2 = new User();
        $user2->setUsername('Alexis');
        $user2->setPassword($this->encoder->encodePassword($user2, 'test'));
        $user2->setEmail("alexismourouc@gmail.com");
        $user2->setRoles(["ROLE_USER"]);
        
        $taskAnononymous=new Task();
        $taskAnononymous->setCreatedAt(new \DateTime('now'));
        $taskAnononymous->setTitle('Send the bill to mr Smith.');
        $taskAnononymous->setContent('Adress : smith2.@corpname.com');
        $taskAnononymous->setUser($userAnonymous);
        
        $task=new Task();
        $task->setCreatedAt(new \DateTime('now'));
        $task->setTitle('Pay the bills from our tech provider .');
        $task->setContent('N°418514 and n°14511');
        $task->setUser($user);
        
        $task2=new Task();
        $task2->setCreatedAt(new \DateTime('now'));
        $task2->setTitle('Clean the car.');
        $task2->setContent('Remember to close the windows');
        $task2->setUser($user2);
        
        $taskDone=new Task();
        $taskDone->setCreatedAt(new \DateTime('now'));
        $taskDone->setTitle('Call Mrs Puggind.');
        $taskDone->setContent('Ask for accounting report');
        $taskDone->isDone(true);
        $taskDone->setUser($user2);
        
        $manager->persist($userAnonymous);
        $manager->persist($user);
        $manager->persist($taskAnononymous);
        $manager->persist($task);
        $manager->persist($user2);
        $manager->persist($task2);
        $manager->flush();
    }
}
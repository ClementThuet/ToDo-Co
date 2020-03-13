<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;

class BaseFixtures extends Fixture
{
    private $passwordEncoder;
     
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    
    public function load(ObjectManager $manager)
    {
        
        $userAnonymous = new User();
        $userAnonymous->setUsername('ANONYMOUS');
        $userAnonymous->setPassword($this->passwordEncoder->encodePassword($userAnonymous,'test'));
        $userAnonymous->setEmail("");
        $userAnonymous->setRoles(["ROLE_USER"]);
        
        $user = new User();
        $user->setUsername('Clément');
        $user->setPassword($this->passwordEncoder->encodePassword($userAnonymous,'test'));
        $user->setEmail("clementthuet7@gmail.com");
        $user->setRoles(["ROLE_ADMIN", "ROLE_USER"]);
        
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
        
        $manager->persist($userAnonymous);
        $manager->persist($user);
        $manager->persist($taskAnononymous);
        $manager->persist($task);
        $manager->flush();
    }
}
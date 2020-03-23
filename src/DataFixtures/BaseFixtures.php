<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;

class BaseFixtures implements FixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

   /* private $passwordEncoder;
     
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }*/
    
    public function load(ObjectManager $manager)
    {
         
        $userAnonymous = new User();
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($userAnonymous);
        $userAnonymous->setUsername('ANONYMOUS');
       
        $userAnonymous->setPassword($encoder->encodePassword('test',$userAnonymous));
        //$userAnonymous->setPassword($this->passwordEncoder->encodePassword($userAnonymous,'test'));
        $userAnonymous->setEmail("");
        $userAnonymous->setRoles(["ROLE_USER"]);
        
        $user = new User();
        $user->setUsername('Clément');
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        $user->setPassword($encoder->encodePassword('test',$user));
        $user->setEmail("clementthuet7@gmail.com");
        $user->setRoles(["ROLE_ADMIN", "ROLE_USER"]);
        
        $user2 = new User();
        $user2->setUsername('Alexis');
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user2);
        $user2->setPassword($encoder->encodePassword('test',$user2));
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
        
        $manager->persist($userAnonymous);
        $manager->persist($user);
        $manager->persist($taskAnononymous);
        $manager->persist($task);
        $manager->persist($user2);
        $manager->persist($task2);
        $manager->flush();
    }
}
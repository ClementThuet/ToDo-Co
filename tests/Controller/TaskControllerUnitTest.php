<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerUnitTest extends WebTestCase
{
    private $client = null;
  
    /*function setUp()
    {
       $this->client = static::createClient();
    }*/
    
    public function loginAsAdmin()
    {
        $this->client = static::createClient();
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'Clement';
        $form['_password'] = 'test';
        $this->client->submit($form);
    }
    
    
    public function testCreateTask()
    {
        $this->client = static::createClient();
        $this->loginAsAdmin();
        $this->crawler = $this->client->request('GET', '/tasks/create');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $form =  $this->crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = 'TASK TO DELETE';
        $form['task[content]'] = rand();
        $this->form = $form;
        $this->client->submit($this->form);
        $this->crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(), "Correct redirect to page tasks");
        $this->assertTrue($this->crawler->filter('html:contains("Liste des tâches")')->count() > 0);
    }
    
    public function testDeleteTask()
    {
        $this->client = static::createClient();
        $this->loginAsAdmin();
        $em = self::$container->get('doctrine')->getManager();
        $taskToDelete = $em->getRepository('App\Entity\Task')->findOneBy(array('title' => 'TASK TO DELETE'));
        $this->crawler = $this->client->request('GET', '/tasks/'.$taskToDelete->getId().'/delete');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $taskDeleted = $em->getRepository('App\Entity\Task')->findOneBy(array('title' => 'TASK TO DELETE'));
        $this->AssertNull($taskDeleted);
    }
    
    public function testDeleteTaskWithoutPermission()
    {
        $this->client = static::createClient();
        $em = self::$container->get('doctrine')->getManager();
        $taskToDelete = $em->getRepository('App\Entity\Task')->findOneBy(array('title' => 'Send the bill to mr Smith.'));
        $this->crawler = $this->client->request('GET', '/tasks/'.$taskToDelete->getId().'/delete');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->crawler = $this->client->followRedirect();
        $this->assertTrue($this->crawler->filter('html:contains("Connexion")')->count() > 0);
    }
    
    public function testEditTask()
    {
        $this->client = static::createClient();
        $this->loginAsAdmin();
        $em = self::$container->get('doctrine')->getManager();
        $taskToEdit = $em->getRepository('App\Entity\Task')->findOneBy(array('title' => 'Send the bill to mr Smith.'));
        $this->crawler = $this->client->request('GET', '/tasks/'.$taskToEdit->getId().'/edit');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $form =  $this->crawler->selectButton('Modifier')->form();
        $form['task[title]'] = "Send the bill to mr Smith.";
        $form['task[content]'] = "Adress : smith2.@corpname.com";
        $this->form = $form;
        $this->client->submit($this->form);
        $this->crawler = $this->client->followRedirect();
        $this->assertTrue($this->crawler->filter('html:contains("Liste des tâches")')->count() > 0);
    }
      
    public function testToggleTask()
    {
        $this->client = static::createClient();
        $this->loginAsAdmin();
        $em = self::$container->get('doctrine')->getManager();
        $taskToEdit = $em->getRepository('App\Entity\Task')->findOneBy(array('title' => 'Send the bill to mr Smith.'));
        $initialDoneState = $taskToEdit->isDone();
        $this->crawler = $this->client->request('GET', '/tasks/'.$taskToEdit->getId().'/toggle');
        $taskToEditToggled = $em->getRepository('App\Entity\Task')->findOneBy(array('title' => 'Send the bill to mr Smith.'));
        $newDoneState = $taskToEditToggled->isDone();
        $this->assertSame(!$initialDoneState,$newDoneState);
        //Reversing change
        $this->crawler = $this->client->request('GET', '/tasks/'.$taskToEditToggled->getId().'/toggle');
    }
    
    
}

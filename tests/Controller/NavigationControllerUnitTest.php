<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerUnitTest extends WebTestCase
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
    
    public function testHomepageIsUp()
    {
        $this->client = static::createClient();
      $this->client->request('GET', '/');
      static::assertEquals(
        200,
        $this->client->getResponse()->getStatusCode()
      );
    }
    
    public function testLoginpageIsUp()
    {
        $this->client = static::createClient();
        $this->client->request('GET', '/login');
        static::assertEquals(
          200,
          $this->client->getResponse()->getStatusCode()
        );
    }
    
    //Test if there is a form with action = /login_check on the page at the URL "login"
    public function testLoginFormIsUp()
    {
        $this->client = static::createClient();
        $crawler = $this->client->request('GET', '/login');
        $this->assertSame(1, $crawler->filter('form[action=\/login_check]')->count());
    }
    
    public function testLogin()
    {
        $this->client = static::createClient();
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'Clement';
        $form['_password'] = 'test';
       
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        $this->assertTrue($crawler->filter('html:contains("Bienvenue sur Todo List")')->count() > 0);
    }
    
    public function testUsersPageIsUp()
    {
        $this->client = static::createClient();
        $this->loginAsAdmin();
        $crawler = $this->client->request('GET', '/users');
        $this->assertTrue($crawler->filter('html:contains("Liste des utilisateurs")')->count() > 0);
    }
    
    public function testCreateUserPageIsUp()
    {
        $this->client = static::createClient();
        $this->loginAsAdmin();
        $crawler = $this->client->request('GET', '/users/create');
        $this->assertTrue($crawler->filter('html:contains("Créer un utilisateur")')->count() > 0);
    }
    
    public function testTasksPageIsUp()
    {
        $this->client = static::createClient();
        $this->loginAsAdmin();
        $crawler = $this->client->request('GET', '/tasks');
        $this->assertTrue($crawler->filter('html:contains("Liste des tâches")')->count() > 0);
    }
    
    public function testCreateTaskPageIsUp()
    {
        $this->client = static::createClient();
        $this->loginAsAdmin();
        $crawler = $this->client->request('GET', '/tasks/create');
        $this->assertTrue($crawler->filter('html:contains("Créer une tâche")')->count() > 0);
    }
    
    
    
    
}

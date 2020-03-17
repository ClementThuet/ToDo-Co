<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HTTPFoundation\Response;

class UserControllerTest extends WebTestCase
{
    private $client = null;
  
    public function setUp()
    {
      $this->client = static::createClient();
    }
    
    public function testLoginpageIsUp()
    {
      $this->client->request('GET', '/login');
      static::assertEquals(
        200,
        $this->client->getResponse()->getStatusCode()
      );
    }
    
    //Test if there is a form with action = /login_check on the page at the URL "login"
    public function testLoginFormIsUp()
    {
        $crawler = $this->client->request('GET', '/login');
        $this->assertSame(1, $crawler->filter('form[action=\/login_check]')->count());
    }
    
    public function testLogin()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form['username'] = 'Clement';
        $form['password'] = 'test';
        $crawler = $client->submit($form);

        echo $client->getResponse()->getContent();
    }
    
        
    public function testHomepageIsUp()
    {
      $this->client->request('GET', '/');
      static::assertEquals(
        302,
        $this->client->getResponse()->getStatusCode()
      );
    }
    
    
    
}

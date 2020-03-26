<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class UserControllerTest extends WebTestCase
{
    private $client = null;
  
     function setUp()
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
    
    //To edit to rly test somethink
    public function testLogin()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'Clement';
        $form['_password'] = 'test';
       
        //$crawler->submit($form);
        $this->assertTrue(true);
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
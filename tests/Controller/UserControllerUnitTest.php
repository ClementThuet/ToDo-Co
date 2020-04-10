<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerUnitTest extends WebTestCase
{
    public function loginAsAdmin()
    {
        $this->client = static::createClient();
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'Clement';
        $form['_password'] = 'test';
        $this->client->submit($form);
    }
    
    
    public function testCreateUser()
    {
        $this->client = static::createClient();
        $this->loginAsAdmin();
        $this->crawler = $this->client->request('GET', '/users/create');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $form =  $this->crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = "John";
        $form['user[password][first]'] = "password";
        $form['user[password][second]'] = "password";
        $form['user[email]'] = "functionaltestmailexample@test.com";
        $form['user[roles][0]'] = true;
        $this->form = $form;
        $this->client->submit($this->form);
        $this->crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(), "Correct redirect to page users");
        $this->assertTrue($this->crawler->filter('html:contains("Liste des utilisateurs")')->count() > 0);
        
        $em = self::$container->get('doctrine')->getManager();
        $userCreatedToTest = $em->getRepository('App\Entity\User')->findOneBy(array('email' => 'functionaltestmailexample@test.com'));
        $this->assertNotNull($userCreatedToTest);
        $em->remove($userCreatedToTest);
        $em->flush();
    }
    
    
    public function testEditUser()
    {
        $this->client = static::createClient();
        $this->loginAsAdmin();
        $em = self::$container->get('doctrine')->getManager();
        $userToEdit = $em->getRepository('App\Entity\User')->findOneBy(array('email' => 'anonymous@anonymous.com'));
        $this->crawler = $this->client->request('GET', '/users/'.$userToEdit->getId().'/edit');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $form = $this->crawler->selectButton('Modifier')->form();
        $form['user[username]'] = "ANONYMOUS";
        $form['user[password][first]'] = "test";
        $form['user[password][second]'] = "test";
        $form['user[email]'] = "anonymous@anonymous.com";
        $form['user[roles][0]'] = true;
        $this->form = $form;
        $this->client->submit($this->form);
        $this->crawler = $this->client->followRedirect();
        $this->assertTrue($this->crawler->filter('html:contains("Liste des utilisateurs")')->count() > 0);
    }
      
   
    
    
}

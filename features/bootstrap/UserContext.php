<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

require_once(__DIR__ . '/../../vendor/bin/.phpunit/phpunit-5.7/vendor/autoload.php');

/**
 * Defines application features from the specific context.
 */
class UserContext extends WebTestCase implements Context
{
    private static $container;
    
    //Needs to override GetKernelClass method
    protected static function getKernelClass()
    {
        return \AppKernel::class;
    }
    
    public function __construct()
    {
        $this->client = static::createClient();
    }
    
    /**
     * @BeforeSuite
     */
    public static function bootstrapSymfony()
    {
        require_once __DIR__.'/../../app/autoload.php';
        require_once __DIR__.'/../../app/AppKernel.php';
        $kernel = new AppKernel('test', true);
        $kernel->boot();
        self::$container = $kernel->getContainer();
    }
    
    public function loginAsAdmin()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = "Clement";
        $form['_password'] = "test";
        $this->form = $form;
        $this->client->submit($this->form);
    }
    
    
     /**
     * @Given I am on users\/create url logged in as an admin
     */
    public function iAmOnUsersCreateUrlLoggedInAsAnAdmin()
    {
        $this->loginAsAdmin();
        $this->crawler = $this->client->request('GET', '/users/create');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @Given I fill in the form with valid informations
     */
    public function iFillInTheFormWithValidInformations()
    {
        $form =  $this->crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = "John";
        $form['user[password][first]'] = "password";
        $form['user[password][second]'] = "password";
        $form['user[email]'] = "functionaltestmailexample@test.com";
        $form['user[roles][0]'] = true;
        $this->form = $form;
    }

    /**
     * @Given I press ajouter
     */
    public function iPressAjouter()
    {
        $this->client->submit($this->form);
        $this->assertTrue($this->client->getResponse()->isRedirect(),'Submit ok');
        $this->crawler = $this->client->followRedirect();
    }

    /**
     * @Then I should be redirect to users page
     */
    public function iShouldBeRedirectToUsersPage()
    {
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(), "Correct redirect to page users");
        $this->assertTrue($this->crawler->filter('html:contains("Liste des utilisateurs")')->count() > 0);
    }
    
    /**
     * @Then the user must be stored in database
     */
    public function theUserMustBeStoredInDatabase()
    {
        $em = self::$container->get('doctrine')->getManager();
        $userCreatedToTest = $em->getRepository('\AppBundle\Entity\User')->findOneBy(array('email' => 'functionaltestmailexample@test.com'));
        $this->assertNotNull($userCreatedToTest);
        $em->remove($userCreatedToTest);
        $em->flush();
    }


    /**
     * @Given I am on users\/id\/edit url
     */
    public function iAmOnUsersIdEditUrl()
    {
        $em = self::$container->get('doctrine')->getManager();
        $userToEdit = $em->getRepository('\AppBundle\Entity\User')->findOneBy(array('email' => 'anonymous@anonymous.com'));
        $this->loginAsAdmin();
        $this->crawler = $this->client->request('GET', '/users/'.$userToEdit->getId().'/edit');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @Given I fill in the form with correct informations
     */
    public function iFillInTheFormWithCorrectInformations()
    {
        $form =  $this->crawler->selectButton('Modifier')->form();
        $form['user[username]'] = "ANONYMOUS";
        $form['user[password][first]'] = "test";
        $form['user[password][second]'] = "test";
        $form['user[email]'] = "anonymous@anonymous.com";
        $form['user[roles][0]'] = true;
        $this->form = $form;
    }

    /**
     * @Given I click on modifier
     */
    public function iClickOnModifier()
    {
        $this->client->submit($this->form);
        $this->assertTrue($this->client->getResponse()->isRedirect(),'Submit ok');
        $this->crawler = $this->client->followRedirect();
    }

    /**
     * @Then I should be redirect to users list
     */
    public function iShouldBeRedirectToUsersList()
    {
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(), "Correct redirect to page users");
        $this->assertTrue($this->crawler->filter('html:contains("Liste des utilisateurs")')->count() > 0);
    }
}

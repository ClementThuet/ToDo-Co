<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

//require_once(__DIR__ . '/../../vendor/bin/.phpunit/phpunit-5.7/vendor/autoload.php');

/**
 * Defines application features from the specific context.
 */
class SecurityContext extends WebTestCase implements Context
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
    
    public function loginAsUser()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = "Alexis";
        $form['_password'] = "test";
        $this->form = $form;
        $this->client->submit($this->form);
    }
    
    /**
     * @Given I am on login url
    */
    public function iAmOnLoginUrl()
    {
        $this->client->request('GET', '/login');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @Given I fill in credentials
     */
    public function iFillInCredentials()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = "Clément";
        $form['_password'] = "test";
        $this->form = $form;
    }

    /**
     * @Given I press Se connecter
     */
    public function iPressSeConnecter()
    {
        $this->client->submit($this->form);
        $this->assertTrue($this->client->getResponse()->isRedirect(),'Submit ok');
        $this->crawler = $this->client->followRedirect();
    }

    /**
     * @Then I should see Se déconnecter
     */
    public function iShouldSeeSeDeconnecter()
    {
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(), "Correct redirect to page ok");
        $this->assertTrue($this->crawler->filter('html:contains("Se déconnecter")')->count() > 0);
    }

    
    // LOGIN OUT
    
    /**
     * @Given I am on users url
     */
    public function iAmOnUsersUrl()
    {
        $this->loginAsAdmin();
        $this->crawler = $this->client->request('GET', '/users');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @Given I click on button se déconnecter
     */
    public function iClickOnButtonSeDeconnecter()
    {
        $link = $this->crawler
        ->filter('.logout') // find all links with the text "Greet"
        ->link();
        $this->crawler = $this->client->click($link);
        $this->crawler = $this->client->followRedirect();
    }


    /**
     * @Then I should be redirect to login page
     */
    public function iShouldBeRedirectToLoginPage()
    {
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode(), "Correct redirect to page login");
        $this->assertTrue($this->crawler->filter('html:contains("Redirecting to http://localhost/login")')->count() > 0);
    }

    /**
     * @Then I should be unable to access \/users page
     */
    public function iShouldBeUnableToAccessUsersPage()
    {
        $this->crawler = $this->client->request('GET', '/users');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode(), "Redirect to login page");
    }

    //TRY TO ACCESS ADMINS PAGE WITHOUT ROLE ADMIN
    
    /**
     * @Given I am logged with role user
     */
    public function iAmLoggedWithRoleUser()
    {
        $this->loginAsUser();
        $this->assertNotNull($this->client);
    }

    /**
     * @Given I try to access \/users
     */
    public function iTryToAccessUsers()
    {
        $this->client->request('GET', '/users');
        $this->assertNotNull($this->client);
    }
    
     /**
     * @Then I should get a forbiden access
     */
    public function iShouldGetAForbidenAccess()
    {
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }
    
    
    // TRY TO DELETE A TASK I DONT OWN
    
    /**
     * @Given I am loged
     */
    public function iAmLoged()
    {
        $this->loginAsUser();
        $this->assertNotNull($this->client);
    }

    /**
     * @Given I try to access the uri whick delete a task i didnt created
     */
    public function iTryToAccessTheUriWhickDeleteATaskIDidntCreated()
    {
        $em = self::$container->get('doctrine')->getManager();
        //This task is created by another user than the one who is logged
        $taskToDelete = $em->getRepository('\AppBundle\Entity\Task')->findOneBy(array('content' => 'N°418514 and n°14511'));
        $this->loginAsUser();
        $this->client->request('GET', '/tasks/'.$taskToDelete->getId().'/delete');
    }

    /**
     * @Then I should get a message saying I can't delete it
     */
    public function iShouldGetAMessageSayingICantDeleteIt()
    {
         $this->crawler = $this->client->followRedirect();
         $this->assertTrue($this->crawler->filter('html:contains("Oops ! Vous ne pouvez supprimer une tâche que vous n\'avez pas créee.")')->count() > 0);
    }

    //TRYING TO DELETE A TASK OWNED BY ANONYMOUS USER WHITHOUT GETTING ROLE ADMIN
    
    /**
     * @Given I try to access the uri whick delete a task annonymous
     */
    public function iTryToAccessTheUriWhickDeleteATaskAnnonymous()
    {
        $em = self::$container->get('doctrine')->getManager();
        //This task is created by another user than the one who is logged
        $taskToDelete = $em->getRepository('\AppBundle\Entity\Task')->findOneBy(array('content' => 'Adress : smith2.@corpname.com'));
        $this->assertNotNull($taskToDelete,'This task doesn\'t exists');
        $this->loginAsUser();
        $this->client->request('GET', '/tasks/'.$taskToDelete->getId().'/delete');
    }



}

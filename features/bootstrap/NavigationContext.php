<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Behat\Behat\Context\BehatContext;

//require_once(__DIR__ . '/../../vendor/bin/.phpunit/phpunit-5.7/vendor/autoload.php');

/**
 * Defines application features from the specific context.
 */
class NavigationContext extends WebTestCase implements Context
{
    private static $container;
    
    protected static function getKernelClass()
    {
        return \AppKernel::class;
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

    
    public function __construct()
    {
        $this->client = static::createClient();
    }
    
    /**
     * @Given I am logged in
     */
    public function iAmLoggedIn()
    {
        $this->loginAsAdmin();
    }
    
    /**
     * @Given i am on \/tasks
     */
    public function iAmOnTasks()
    {
        $this->crawler = $this->client->request('GET', '/tasks');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
    
    /**
     * @Given some tasks are already added
     */
    public function someTasksAreAlreadyAdded()
    {
        $em = self::$container->get('doctrine')->getManager();
        $this->assertNotNull(
            $em->getRepository('\AppBundle\Entity\Task')->findAll()
        );
    }

    /**
     * @Then I should see at least one task
     */
    public function iShouldSeeAtLeastOneTask()
    {
        $this->assertTrue($this->crawler->filter('html:contains("Liste des tâches")')->count() > 0);
    }

    
    /**
     * @Given I am logged in as an admin
     */
    public function iAmLoggedInAsAnAdmin()
    {
        $this->loginAsAdmin();
    }

    /**
     * @Given i am on \/users
     */
    public function iAmOnUsers()
    {
        $this->crawler = $this->client->request('GET', '/users');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @Then I should find users
     */
    public function iShouldFindUsers()
    {
        $this->assertTrue($this->crawler->filter('html:contains("Edit")')->count() > 0);
    }

}

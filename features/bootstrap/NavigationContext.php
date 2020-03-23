<?php


use Behat\Behat\Context\Context;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Defines application features from the specific context.
 */
class NavigationContext extends WebTestCase implements Context
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
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

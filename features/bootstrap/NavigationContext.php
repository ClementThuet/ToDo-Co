<?php



use Behat\Behat\Context\Context;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Defines application features from the specific context.
 */
class NavigationContext extends WebTestCase implements Context
{
    
    public function __construct()
    {
        $this->client = static::createClient();
    }
    
    /*public static function loginAsAdmin()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = "Clement";
        $form['_password'] = "test";
        $this->form = $form;
        $this->client->submit($this->form);
    }*/
    
    /**
     * @Given I am logged in
     */
    public function iAmLoggedIn()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = "Clement";
        $form['_password'] = "test";
        $this->form = $form;
        $this->client->submit($this->form);
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
            $em->getRepository('App\Entity\Task')->findAll()
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
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = "Clement";
        $form['_password'] = "test";
        $this->form = $form;
        $this->client->submit($this->form);
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
    
    // ACCESSING UNDEFINED URL
    
    /**
     * @Given Y try to access a non existent route
     */
    public function yTryToAccessANonExistentRoute()
    {
        $this->crawler = $this->client->request('GET', '/ARandomAndUndefinedRoute');
    }

    /**
     * @Then I should see a not found error
     */
    public function iShouldSeeANotFoundError()
    {
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }


       

}

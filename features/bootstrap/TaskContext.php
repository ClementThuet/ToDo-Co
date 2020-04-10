<?php


use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

//require_once(__DIR__ . '/../../vendor/bin/.phpunit/phpunit-5.7/vendor/autoload.php');

/**
 * Defines application features from the specific context.
 */
class TaskContext extends WebTestCase implements Context
{
    
    public function __construct()
    {
        $this->client = static::createClient();
    }
    
    
    /*public static function  loginAsAdmin()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = "Clement";
        $form['_password'] = "test";
        $this->form = $form;
        $this->client->submit($this->form);
    }*/
    
    /**
     * @Given I am on task\/create url logged in
     */
    public function iAmOnTaskCreateUrlLoggedIn()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = "Clement";
        $form['_password'] = "test";
        $this->form = $form;
        $this->client->submit($this->form);
        $this->crawler = $this->client->request('GET', '/tasks/create');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @Given I fill in the task form with valid informations
     */
    public function iFillInTheTaskFormWithValidInformations()
    {
        $form =  $this->crawler->selectButton('Ajouter')->form();
        $this->title = rand();
        $this->content = rand();
        $form['task[title]'] = $this->title;
        $form['task[content]'] = $this->content;
        $this->form = $form;
    }

    
    /**
     * @Then I select ajouter and should be redirect to tasks list
     */
    public function iSelectAjouterAndShouldBeRedirectToTasksList()
    {
        $this->client->submit($this->form);
        $this->crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(), "Correct redirect to page tasks");
        $this->assertTrue($this->crawler->filter('html:contains("Liste des tâches")')->count() > 0);
    }

    /**
     * @Then the task must be stored in database
     */
    public function theTaskMustBeStoredInDatabase()
    {
        $em = self::$container->get('doctrine')->getManager();
        $taskCreatedToTest = $em->getRepository('App\Entity\Task')->findOneBy(array('title' => $this->title));
        $this->assertNotNull($taskCreatedToTest);
        $em->remove($taskCreatedToTest);
        $em->flush();
    }

    // ADD TASK WITH UNVALID INFORMATION
    
     /**
     * @Given I fill in the task form with unvalid informations
     */
    public function iFillInTheTaskFormWithUnvalidInformations()
    {
        $form =  $this->crawler->selectButton('Ajouter')->form();
        $this->title = rand();
        $this->content = rand();
        $form['task[title]'] = '';
        $form['task[content]'] = '';
        $this->form = $form;
    }

    /**
     * @Then I select ajouter and shouln't be redirect
     */
    public function iSelectAjouterAndShoulntBeRedirect()
    {
        $this->client->submit($this->form);
        $this->assertTrue($this->crawler->filter('html:contains("Créer une tâche")')->count() > 0);
    }


    /**
     * @Given I am on tasks\/id\/edit url logged in
     */
    public function iAmOnTasksIdEditUrlLoggedIn()
    {
        $em = self::$container->get('doctrine')->getManager();
        $taskToEdit = $em->getRepository('App\Entity\Task')->findOneBy(array('title' => 'Send the bill to mr Smith.'));
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = "Clement";
        $form['_password'] = "test";
        $this->form = $form;
        $this->client->submit($this->form);
        $this->crawler = $this->client->request('GET', '/tasks/'.$taskToEdit->getId().'/edit');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @Given I fill in the form with correct information
     */
    public function iFillInTheFormWithCorrectInformation()
    {
        $form =  $this->crawler->selectButton('Modifier')->form();
        $form['task[title]'] = "Send the bill to mr Smith.";
        $form['task[content]'] = "Adress : smith2.@corpname.com";
        $this->form = $form;
    }

    /**
     * @Given I press modifier
     */
    public function iPressModifier()
    {
        $this->client->submit($this->form);
        $this->assertTrue($this->client->getResponse()->isRedirect(),'Submit ok');
        $this->crawler = $this->client->followRedirect();
    }

    /**
     * @Then I should be redirect to task list
     */
    public function iShouldBeRedirectToTaskList()
    {
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(), "Correct redirect to page tasks");
        $this->assertTrue($this->crawler->filter('html:contains("Liste des tâches")')->count() > 0);
    }
    
    //TRYING DELETE UNEXISTENT TASK
    /**
     * @Given I try to access task\/id\/delete
     */
    public function iTryToAccessTaskIdDelete()
    {
        $this->crawler = $this->client->request('GET', '/tasks/99999/delete');
    }

    /**
     * @Given the id doesn't exist
     */
    public function theIdDoesntExist()
    {
        $em = self::$container->get('doctrine')->getManager();
        $taskToDelete = $em->getRepository('App\Entity\Task')->find(99999);
        $this->AssertNull($taskToDelete);
    }

    /**
     * @Then I should get a not found error
     */
    public function iShouldGetANotFoundError()
    {
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    
}

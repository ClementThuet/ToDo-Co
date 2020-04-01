<?php
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

//require_once(__DIR__ . '/../../vendor/bin/.phpunit/phpunit-5.7/vendor/autoload.php');

/**
 * Defines application features from the specific context.
 */
class UserContext extends WebTestCase implements Context
{
    
    public function __construct()
    {
        $this->client = static::createClient();
    }
    
    
      /**
     * @Given I am on users\/create url logged in as an admin
     */
    public function iAmOnUsersCreateUrlLoggedInAsAnAdmin()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = "Clement";
        $form['_password'] = "test";
        $this->form = $form;
        $this->client->submit($this->form);
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
     * @Then I select ajouter and should be redirect to users page
     */
    public function iSelectAjouterAndShouldBeRedirectToUsersPage()
    {
        $this->client->submit($this->form);
        $this->crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(), "Correct redirect to page users");
        $this->assertTrue($this->crawler->filter('html:contains("Liste des utilisateurs")')->count() > 0);
    }

    /**
     * @Then the user must be stored in database
     */
    public function theUserMustBeStoredInDatabase()
    {
        $em = self::$container->get('doctrine')->getManager();
        $userCreatedToTest = $em->getRepository('App\Entity\User')->findOneBy(array('email' => 'functionaltestmailexample@test.com'));
        $this->assertNotNull($userCreatedToTest);
        $em->remove($userCreatedToTest);
        $em->flush();
    }
    
    //ADD USER WITH UNVALID INFO
    /**
     * @Given I fill in the user form with unvalid informations
     */
    public function iFillInTheUserFormWithUnvalidInformations()
    {
        $form = $this->crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = "";
        $form['user[password][first]'] = "password";
        $form['user[password][second]'] = "notTheSamePassword";
        $form['user[email]'] = "emailButWithoutArobase";
        $form['user[roles][0]'] = true;
        $this->form = $form;
    }
    
    /**
     * @Then I select ajouter and should not be redirect
     */
    public function iSelectAjouterAndShouldNotBeRedirect()
    {
        $this->client->submit($this->form);
        $this->assertTrue($this->crawler->filter('html:contains("Créer un utilisateur")')->count() > 0);
    }

    
    /**
     * @Then the user must not be stored in database
     */
    public function theUserMustNotBeStoredInDatabase()
    {
        $em = self::$container->get('doctrine')->getManager();
        $userCreatedToTest = $em->getRepository('App\Entity\User')->findOneBy(array('email' => 'emailButWithoutArobase.com'));
        $this->assertNull($userCreatedToTest);
    }

    // EDIT USER

    /**
     * @Given I am on users\/id\/edit url
     */
    public function iAmOnUsersIdEditUrl()
    {
        $em = self::$container->get('doctrine')->getManager();
        $userToEdit = $em->getRepository('App\Entity\User')->findOneBy(array('email' => 'anonymous@anonymous.com'));
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = "Clement";
        $form['_password'] = "test";
        $this->form = $form;
        $this->client->submit($this->form);
        $this->crawler = $this->client->request('GET', '/users/'.$userToEdit->getId().'/edit');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @Given I fill in the form with correct informations
     */
    public function iFillInTheFormWithCorrectInformations()
    {
        $form = $this->crawler->selectButton('Modifier')->form();
        $form['user[username]'] = "ANONYMOUS";
        $form['user[password][first]'] = "test";
        $form['user[password][second]'] = "test";
        $form['user[email]'] = "anonymous@anonymous.com";
        $form['user[roles][0]'] = true;
        $this->form = $form;
    }
    
     /**
     * @Then I click on modifier and should be redirect to users list
     */
    public function iClickOnModifierAndShouldBeRedirectToUsersList()
    {
        $this->client->submit($this->form);
        $this->crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(), "Correct redirect to page users");
        $this->assertTrue($this->crawler->filter('html:contains("Liste des utilisateurs")')->count() > 0);
    }

     /**
     * @Given I fill in the form with empty informations
     */
    public function iFillInTheFormWithEmptyInformations()
    {
        $form = $this->crawler->selectButton('Modifier')->form();
        $form['user[username]'] = "";
        $form['user[password][first]'] = "test";
        $form['user[password][second]'] = "test";
        $form['user[email]'] = "";
        $this->form = $form;
    }

    /**
     * @Then I click on modifier and should not be redirect to users list
     */
    public function iClickOnModifierAndShouldNotBeRedirectToUsersList()
    {
        $this->client->submit($this->form);
        $this->assertTrue($this->crawler->filter('html:contains("Modifier")')->count() > 0);
    }


    
}

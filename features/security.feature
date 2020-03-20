@security
Feature: User login
  In order to login
  As a user
  I need to submit valid credentials

  Scenario: logging in  
    Given I am on login url
    And I fill in credentials
    And I press Se connecter
    Then I should see Se déconnecter

  Scenario: logging out  
    Given I am on users url
    And I click on button se déconnecter
    Then I should be redirect to login page
    And I should be unable to access /users page

  Scenario: Try to access gestion's page without role admin  
    Given I am logged with role user
    And I try to access /users
    Then I should get a forbiden access

  Scenario: Try to delete task I didn't created  
    Given I am loged 
    And I try to access the uri whick delete a task i didnt created 
    Then I should get a message saying I can't delete it 

  Scenario: Try to delete task owned by anonymous user without have role admin 
    Given I am logged with role user
    And I try to access the uri whick delete a task annonymous
    Then I should get a message saying I can't delete it 
@user
Feature: Create and edit user
  In order to create or edit a user
  As a administrator
  I need to submit valid form

  Scenario: Create user
    Given I am on users/create url logged in as an admin
    And I fill in the form with valid informations
    And I press ajouter
    Then I should be redirect to users page
    And the user must be stored in database

  Scenario: Edit user
    Given I am on users/create url logged in as an admin
    Given I am on users/id/edit url
    And I fill in the form with correct informations
    And I click on modifier
    Then I should be redirect to users list
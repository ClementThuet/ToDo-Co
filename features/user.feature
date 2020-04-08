@user
Feature: Create and edit user
  In order to create or edit a user
  As a administrator
  I need to submit valid form

  Scenario: Create user
    Given I am on users/create url logged in as an admin
    And I fill in the form with valid informations
    Then I select ajouter and should be redirect to users page
    And the user must be stored in database

  Scenario: Create user with unvalid informations
    Given I am on users/create url logged in as an admin
    And I fill in the user form with unvalid informations
    Then I select ajouter and should not be redirect
    And the user must not be stored in database

  Scenario: Edit user
    Given I am on users/id/edit url
    And I fill in the form with correct informations
    Then I click on modifier and should be redirect to users list

  Scenario: Edit user with empty datas
    Given I am on users/id/edit url
    And I fill in the form with empty informations
    Then I click on modifier and should not be redirect to users list
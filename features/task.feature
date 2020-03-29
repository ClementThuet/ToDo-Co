@task
Feature: Create and edit task
  In order to create or edit a task
  As a administrator
  I need to submit valid form

  Scenario: Create task
    Given I am on task/create url logged in
    And I fill in the task form with valid informations
    And I select ajouter
    Then I should be redirect to tasks list
    And the task must be stored in database

  Scenario: Edit task
    Given I am on tasks/id/edit url logged in
    Given I fill in the form with correct information
    And I press modifier
    Then I should be redirect to task list


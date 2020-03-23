@navigation
Feature: Navigating on the site once logged
  In order to access differents page
  As a user
  I need to navigate through various URL

  Scenario: Displaying tasks
    Given I am logged in
    Given I am on /tasks
    And some tasks are already added
    Then I should see at least one task

  Scenario: Displaying users
    Given I am logged in as an admin
    And I am on /users
    Then I should find users

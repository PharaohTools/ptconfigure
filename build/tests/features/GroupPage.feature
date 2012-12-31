Feature: Reaching the Group Page

  Scenario: I can load the group page if i am logged in
    Given I visit the login page
    And I fill in a correct login form
    And I Submit the Form
    And I visit the group page
    Then I should see "Group Page"


  Scenario: I can not load the group page if i am not logged in
    Given I visit the logout page
    And I visit the group page
    Then I should see "Index Page"
    Then I should see "Please Login"
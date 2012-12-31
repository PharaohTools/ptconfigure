Feature: Reaching the Results Page

  Scenario: I can load the results page if i am logged in
    Given I visit the login page
    And I fill in a correct login form
    And I Submit the Form
    And I visit the results page
    Then I should see "Results Page"


  Scenario: I can not load the results page if i am not logged in
    Given I visit the logout page
    And I visit the results page
    Then I should see "Index Page"
    Then I should see "Please Login"
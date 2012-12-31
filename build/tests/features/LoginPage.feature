Feature: Reaching the Login Page

  Scenario: I can load the login page
    Given I visit the login page
    Then I should see "Login Page"

  Scenario: I can see the username field on the login page
    Given I visit the login page
    Then I should see the field "email"

  Scenario: I can see the userpass field on the login page
    Given I visit the login page
    Then I should see the field "userPass"

  Scenario: If I submit an empty form I see all required errors
    Given I visit the login page
    And I fill in an incorrect login form with no email address
    And I Submit the Form
    Then I should see a "This field cannot be blank." message

  Scenario: If I submit an email with no pass i see a Not Blank error
    Given I visit the login page
    And I fill in an incorrect login form with no email address
    And I Submit the Form
    Then I should see a "This field cannot be blank." message

  Scenario: If I submit an email with no pass i see a More than 6 chars error
    Given I visit the login page
    And I fill in an incorrect login form with no email address
    And I Submit the Form
    Then I should see a "This must be a valid email." message

  Scenario: If I submit an email with no pass i see an Incorrect Login Errr
    Given I visit the login page
    And I fill in an incorrect login form with no email address
    And I Submit the Form
    Then I should see a "The login details are incorrect" message

  Scenario: If I submit a pass with no email i see a Not Blank error
    Given I visit the login page
    And I fill in an incorrect login form with no password
    And I Submit the Form
    Then I should see a "This field cannot be blank." message

  Scenario: If I submit a pass with no email i see a Valid Email error
    Given I visit the login page
    And I fill in an incorrect login form with no password
    And I Submit the Form
    Then I should see a "Password must be more than 6 Characters." message

  Scenario: If I submit a pass with no email i see an Incorrect Login Error
    Given I visit the login page
    And I fill in an incorrect login form with no password
    And I Submit the Form
    Then I should see a "The login details are incorrect" message
    
  Scenario: If I submit a correct form I will be logged in
    Given I visit the login page
    And I fill in a correct login form
    And I Submit the Form
    Then I should see a "You are now logged in" message

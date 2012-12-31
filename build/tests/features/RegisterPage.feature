Feature: Reaching the Register Page

  Scenario: I can load the register page
    Given I visit the register page
    Then I should see "Register Page"

  Scenario: I can see the username field on the register page
    Given I visit the register page
    Then I should see the field "email"

  Scenario: I can see the userpass field on the register page
    Given I visit the register page
    Then I should see the field "userPass"

  Scenario: If I submit an empty form I see all required errors
    Given I visit the register page
    And I fill in an incorrect register form with no email address
    And I Submit the Form
    Then I should see a "This field cannot be blank." message

  Scenario: If I submit an email with no pass i see a Not Blank error
    Given I visit the register page
    And I fill in an incorrect register form with no email address
    And I Submit the Form
    Then I should see a "This field cannot be blank." message

  Scenario: If I submit an email with no pass i see a Valid Email error
    Given I visit the register page
    And I fill in an incorrect register form with no email address
    And I Submit the Form
    Then I should see a "This must be a valid email." message

  Scenario: If I submit a pass with no email i see a Not Blank error
    Given I visit the register page
    And I fill in an incorrect register form with no password
    And I Submit the Form
    Then I should see a "This field cannot be blank." message

  Scenario: If I submit a pass with no email i see a More than 6 Chars error
    Given I visit the register page
    And I fill in an incorrect register form with no password
    And I Submit the Form
    Then I should see a "Password must be more than 6 Characters." message

  Scenario: If I submit a correct register form I will have registered a new user
    Given I visit the register page
    And I fill in a correct register form
    And I Submit the Form
    Then I should see a "Thanks for registering! You can now login with your registration details." message

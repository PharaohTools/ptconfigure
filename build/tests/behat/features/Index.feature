@home
Feature: Executing the program index page
  As a command line user
  I want to execute the index page
  To see overview information about the application

  Scenario: Execute with no parameters
    Given I run the application command in the shell
    Then I should see all of the modules which are not hidden

  Scenario: Execute with no parameters
    Given I run the application command in the shell
    Then I should see the application description

  Scenario: Execute with no parameters
    Given I run the application command in the shell
    Then I should see the cli text "www.pharaohtools.com"

  Scenario: Execute with "--only-compatible" parameter
    Given I run the application command in the shell with parameter string "--only-compatible"
    Then I should see only the modules which are compatible with this system

  Scenario: Execute with "--compatible-only" parameter
    Given I run the application command in the shell with parameter string "--compatible-only"
    Then I should see only the modules which are compatible with this system
Feature: extract tables
  In order to be able to manage test data separately from the reliant scenarios
  As a user
  I need to be able to extract all tables into separate pipe delimited data files

  Scenario: extract example table
    Given I am in directory "gherkinExample"
    When I run "mandoline -e featureExample.feature"
    Then I should get a new directory
    And it should contain the modified feature file "featureExample.feature"
    And it should contain the data file "Table1"
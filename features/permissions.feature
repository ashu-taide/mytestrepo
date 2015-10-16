# features/permissions.feature
Feature: permissions
  In order to execute automated tests
  As a buildsteps engineer
  I need to be able to access the necessary projects

Scenario: Check permissions to the buildsteps repository
  Given I am in a directory "test"
  When I clone repository "buildsteps" from "git@github.com:acquia/buildsteps.git"
  Then I should not get an error
  When I check out branch "git_test"
  Then I should not get an error

Scenario: Check permissions to the target site repository
  Given I am in a directory "test"
  When I add git remote "sfwiptravis" at "sfwiptravis@svn-43.enterprise-g1.hosting.acquia.com:sfwiptravis.git"
    And I force push branch "git_test" to "sfwiptravis"
  Then I should not get an error

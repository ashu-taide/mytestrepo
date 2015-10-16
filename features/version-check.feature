# features/version-check.feature
Feature: version-check
  In order to ensure that the container image contains the right tools
  As a buildsteps engineer
  I need to be able to run and successfully complete the version check build task

# have a beforeFeature here to set up?
Scenario: Complete the version check build task successfully
  Given I am in a directory "test"
    And I check out branch "validate-container-versions"
    And I push branch "validate-container-versions" to "sfwiptravis"
    And I run "../bin/buildsteps.phar build --site-name=sfwiptravis --vcs-path=validate-container-versions"
#    And I build branch "validate-container-versions" on "sfwiptravis"
    And I wait until the build is complete
  When I run "vendor/bin/buildsteps.phar status sfwiptravis"
  Then I should get "Successfully completed the build task"

#  Examples:
#    | branch                         | site          |
#    | "validate-container-versions"  | "sfwiptravis" |

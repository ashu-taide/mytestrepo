<?php

use Behat\Behat\Context\ClosuredContextInterface;
use Behat\Behat\Context\TranslatedContextInterface;
use Behat\Behat\Context\BehatContext;
use Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Features context for automated testing.
 *
 * This context defines relevant methods used in the features files.
 */
class FeatureContext extends BehatContext {

  /**
   * @var array $lastOutput
   *   The output from the last function call.
   */
  private $lastOutput;

  /**
   * Initializes context.
   * Every scenario gets its own context object.
   *
   * @param array $parameters context parameters (set them up through behat.yml)
   */
  public function __construct(array $parameters) {
  }

  /**
   * @Given /^I am in a directory "([^"]*)"$/
   *
   * @var string $dir
   *   The directory to cd into. If it doesn't exist, create it first.
   */
  public function iAmInADirectory($dir) {
    if (!file_exists($dir)) {
      mkdir($dir);
    }

    chdir($dir);
  }

  /**
   * @When /^I run "([^"]*)"$/
   *
   * @var string $function
   *   The function to be run in the environment.
   */
  public function iRun($function) {
    $this->lastOutput = array();
    exec($function . ' 2>&1', $this->lastOutput);
  }

  /**
   * @When /^I clone repository "([^"]*)" from "([^"]*)"$/
   *
   * @var string $name
   *   The name of the git repository to clone.
   * @var string $url
   *   The URL of the git repository to clone. If it already exists, do nothing.
   */
  public function iCloneRepositoryFrom($name, $url)
  {
    if (!file_exists($name)) {
      $this->iRun(sprintf('git clone %s', $url));
    }
  }

  /**
   * @When /^I check out branch "([^"]*)"$/
   *
   * @var string $branch
   *   The name of the branch to check out. If it already exists, do nothing.
   */
  public function iCheckOutBranch($branch)
  {
    $this->iRun('git branch');
    $branch_exists = FALSE;

    if (!empty($this->lastOutput)) {
      foreach ($this->lastOutput as $element) {
          if (strpos($element, $branch) !== FALSE) {
            $branch_exists = TRUE;
            break;
        }
      }
    }

    if (!$branch_exists) {
      $this->iRun(sprintf('git checkout -b %s', $branch));
    }
    else {
      $this->iRun(sprintf('git checkout %s', $branch));
    }
  }

  /**
   * @When /^I add git remote "([^"]*)" at "([^"]*)"$/
   *
   * @var string $name
   *  The name of the git repository to add as a remote.
   * @var string $url
   *  The URL of the git repository to add. If it already exists, do nothing.
   */
  public function iAddGitRemoteAt($name, $url)
  {
    $this->iRun('git remote -v');
    $remote_exists = FALSE;

    if (!empty($this->lastOutput)) {
      foreach ($this->lastOutput as $element) {
        if (strcasecmp($element, $name) === 0) {
          $remote_exists = TRUE;
          break;
        }
      }
    }

    if (!$remote_exists) {
      $this->iRun(sprintf('git add remote %s %s', $name, $url));
    }
  }

  /**
   * @Given /^I push branch "([^"]*)" to "([^"]*)"$/
   *
   * @var string $branch
   *  The name of the branch to push.
   * @var string $repository
   *  The name of the git repository to push to.
   */
  public function iPushBranchTo($branch, $repository)
  {
    $this->iRun(sprintf('git push %s %s', $repository, $branch));
  }

  /**
   * @Given /^I force push branch "([^"]*)" to "([^"]*)"$/
   *
   * @var string $branch
   *  The name of the branch to push.
   * @var string $repository
   *  The name of the git repository to push to.
   */
  public function iForcePushBranchTo($branch, $repository)
  {
    $this->iRun(sprintf('git push -f %s %s', $repository, $branch));
  }

  /**
   * @Given /^I build branch "([^"]*)" on "([^"]*)"$/
   *
   * @var string $branch
   *  The name of the branch to build.
   * @var string $repository
   *  The name of the site to build.
   */
  public function iBuildBranchOn($branch, $site)
  {
    throw new PendingException();
  }

  /**
   * @Then /^I should not get an error$/
   *
   * @throws \Exception
   */
  public function iShouldNotGetAnError() {
    // @todo there must be a less ugly way to do this search.
    $word_list = array('error', 'fatal', 'rejected', 'fail');
    if (!empty($this->lastOutput)) {
      foreach ($this->lastOutput as $element) {
        foreach ($word_list as $word) {
          if (strpos($element, $word) !== FALSE) {
            throw new \Exception(sprintf($element));
          }
        }
      }
    }
  }

  /**
   * @Given /^I wait until the build is complete$/
   *
   * Waits until the build task has completed, either with a failure or with
   * a success.
   */
  public function iWaitUntilTheBuildIsComplete() {
    throw new PendingException();
  }

  /**
   * @Then /^I should get "([^"]*)"$/
   *
   * @var string $list
   *   The list of strings that should be found in the output of the
   * previous function call.
   */
  public function iShouldGet($list) {
      throw new PendingException();
  }
}

<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Features context.
 */
class FeatureContext extends BehatContext
{
    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        // Initialize your context here
    }


    /**
     * @Given /^I am in directory "([^"]*)"$/
     */
    public function iAmInDirectory($arg1)
    {
        chdir($arg1);
    }

    /**
     * @When /^I run "([^"]*)"$/
     */
    public function iRun($arg1)
    {
        exec($arg1, $output);
        $this->output = trim(implode("\n", $output));
    }

    /**
     * @Then /^I should get a new directory$/
     */
    public function iShouldGetANewDirectory()
    {
        throw new PendingException();
    }

    /**
     * @Given /^it should contain the modified feature file "([^"]*)"$/
     */
    public function itShouldContainTheModifiedFeatureFile($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Given /^it should contain the data file "([^"]*)"$/
     */
    public function itShouldContainTheDataFile($arg1)
    {
        throw new PendingException();
    }
}

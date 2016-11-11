<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\ContextInterface;
use Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;

/**
 * Behat context class.
 */

class NoActionsContext implements Context {


    /**
     * @Given /^I run the application command in the shell$/
     */
    public function iRunTheApplicationCommandInTheShell()
    {
        $command = PTCCOMM ;
        exec($command, $output);
        $this->output = trim(implode("\n", $output));
    }

    /**
     * @Then /^I should see all of the modules which are not hidden$/
     */
    public function iShouldSeeAllOfTheModulesWhichAreNotHidden()
    {
        $mi = \Core\AutoLoader::getInfoObjects() ;
        foreach ($mi as $moduleInfo) {
            if ($moduleInfo->hidden != true) {
                $array_keys = array_keys($moduleInfo->routesAvailable()) ;
                $command = $array_keys[0] ;
                if (strpos($this->output, $command) === false) {
                    throw new \Exception("Expected module {$command} not found."); } } }
    }

    /**
     * @Then /^I should see the application description$/
     */
    public function iShouldSeeTheApplicationDescription()
    {

    }

    /**
     * @Then /^I should see the cli text "([^"]*)"$/
     */
    public function iShouldSeeTheCliText($arg1)
    {
        if (strpos($this->output, $arg1) === false) {
            throw new \Exception("Expected text $arg1 not found."); }
    }

    /**
     * @Given /^I run the application command in the shell with parameter string "([^"]*)"$/
     */
    public function iRunTheApplicationCommandInTheShellWithParameterString($str)
    {
        $command = PTCCOMM." $str" ;
        exec($command, $output);
        $this->output = trim(implode("\n", $output));
    }

    /**
     * @Then /^I should see only the modules which are compatible with this system$/
     */
    public function iShouldSeeOnlyTheModulesWhichAreCompatibleWithThisSystem()
    {
        //@todo this functionality wont be 110 accurate without unit testing that method it calls from model
        $iao = new \Model\IndexAllOS(array()) ;
        $method = new ReflectionMethod('\Model\IndexAllOS', 'findOnlyCompatibleModuleNames');
        $method->setAccessible(true);
        $compats = $method->invokeArgs($iao,array(array()));
        foreach ($compats as $compat) {
            if ($compat["hidden"] !== true) {
                if (strpos($this->output, $compat["command"]) === false) {
                    throw new \Exception("Expected compatible module {$compat["command"]} not found."); } } }
    }

}
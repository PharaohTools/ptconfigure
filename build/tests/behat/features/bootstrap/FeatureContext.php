<?php

use Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

class FeatureContext extends BehatContext
{
    private $output;

    public function __construct() {
        $this->setup();
    }

    private function setup() {
        $bd = dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))).DIRECTORY_SEPARATOR ;
        try {
            require_once ($bd.'src'.DIRECTORY_SEPARATOR. 'AutoLoad.php') ;
            $autoLoader = new \Core\autoLoader();
            $autoLoader->launch(); }
        catch (\Exception $e) {
            echo "Setup cant load autoloader\n" ;
            echo 'Message: ' .$e->getMessage(); }
        try {
            require_once ($bd.'src'.DIRECTORY_SEPARATOR. 'Constants.php') ; }
        catch (\Exception $e) {
            echo "Setup cant load constants\n" ;
            echo 'Message: ' .$e->getMessage(); }
    }

    /**
     * @Given /^I run the application command in the shell$/
     */
    public function iRunTheApplicationCommandInTheShell()
    {
        $command = CLEOCOMM ;
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
     * @Then /^I should see "([^"]*)"$/
     */
    public function iShouldSee($arg1)
    {
        if (strpos($this->output, $arg1) === false) {
            throw new \Exception("Expected text $arg1 not found."); }
    }

    /**
     * @Given /^I run the application command in the shell with parameter string "([^"]*)"$/
     */
    public function iRunTheApplicationCommandInTheShellWithParameterString($str)
    {
        $command = CLEOCOMM." {$str}" ;
        exec($command, $output);
        $this->output = trim(implode("\n", $output));
    }

    /**
     * @Then /^I should see only the modules which are compatible with this system$/
     */
    public function iShouldSeeOnlyTheModulesWhichAreCompatibleWithThisSystem()
    {
        $iao = new \Model\IndexAllOS(array()) ;
        $method = new ReflectionMethod('\Model\IndexAllOS', 'findOnlyCompatibleModuleNames');
        $method->setAccessible(true);
        $compats = $method->invokeArgs($iao,array(array()));
        foreach ($compats as $compat) {
            if (strpos($this->output, $compat) === false) {
                throw new \Exception("Expected copmatible module {$compat} not found."); } }
    }


}
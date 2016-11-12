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

class AnyModuleActionsContext implements Context {

    private $returnCode ;

    /**
     * @Given /^I run the help for all compatible modules checking success exit codes$/
     */
    public function iRunTheHelpForAllCompatibleModulesCheckingSuccessExitCodes() {
        //@todo this functionality wont be 110 accurate without unit testing that method it calls from model
        $iao = new \Model\IndexAllOS(array()) ;
        $method = new ReflectionMethod('\Model\IndexAllOS', 'findOnlyCompatibleModuleNames');
        $method->setAccessible(true);
        $compats = $method->invokeArgs($iao,array(array()));
        foreach ($compats as $modName) {
            if (is_array($modName)) {
                $modName = $modName["command"] ;  }
            $this->iRunTheApplicationCommandInTheShellWithModuleActionAndParams($modName, "help", array()) ;
            $this->theLastCommandShouldHaveASuccessExitCode() ; }
    }

    /**
     * @Given /^I run the help for all incompatible modules checking failure exit codes$/
     */
    public function iRunTheHelpForAllIncompatibleModulesCheckingFailureExitCodes() {
        //@todo this functionality wont be 110 accurate without unit testing that method it calls from model
        $iao = new \Model\IndexAllOS(array());
        $method = new ReflectionMethod('\Model\IndexAllOS', 'findOnlyCompatibleModuleNames');
        $method->setAccessible(true);
        $infos = \Core\AutoLoader::getInfoObjects();
        $newInfos = array() ;
        foreach ($infos as $info) {
            $modName = get_class($info) ;
            $modName = substr($modName, 0, strlen($modName)-4) ;
            $modName = substr($modName, 5) ;
            $newInfos[] = $modName ; }
        $compats = $method->invokeArgs($iao, array(array()));
        $incompats[] = array_diff($newInfos, $compats) ;
        foreach ($incompats as $infoCheck) {
            if (is_array($infoCheck)) {
                $infoCheck = $infoCheck["command"] ; }
            $this->iRunTheApplicationCommandInTheShellWithModuleActionAndParams($infoCheck, "help", array()) ;
            $this->theLastCommandShouldHaveASuccessExitCode() ; }
    }

    /**
     * @Given /^I run the help for all modules checking exit output exists$/
     */
    public function iRunTheHelpForAllModulesCheckingExitOutputExists() {
        $infos = \Core\AutoLoader::getInfoObjects() ;
        foreach ($infos as $info) {
            $modName = get_class($info) ;
            $modName = substr($modName, 0, strlen($modName)-4) ;
            $modName = substr($modName, 5) ;
            if (is_array($modName)) {
                $modName = $modName["command"] ; }
            $this->iRunTheApplicationCommandInTheShellWithModuleActionAndParams($modName, "help", array()) ;
            $this->theLastCommandShouldHaveCreatedOutput() ; }
    }

    /**
     * @Given /^I run the application command in the shell with module action and params$/
     */
    public function iRunTheApplicationCommandInTheShellWithModuleActionAndParams($mod, $act, Array $params) {
        if (count($params)==0) { $pstr = "" ; }
        else { $pstr = implode(" ", $params) ; }
//        if (is_array($mod)) { var_dump("mod", $mod) ; }
//        if (is_array($act)) { var_dump("act", $act) ; }
        $command = PTCCOMM."$mod $act $pstr" ;
        echo "Executing $command\n" ;
        exec($command, $output, $this->returnCode);
        $this->output = trim(implode("\n", $output));
    }

    /**
     * @Given /^The last command should have a success exit code$/
     */
    public function theLastCommandShouldHaveASuccessExitCode() {
        if (in_array(PHP_OS, array("Windows", "WINNT"))) { throw new \Exception("Windows doesn't do exit codes"); }
        if ($this->returnCode != "0") { throw new \Exception("Non success exit code of {$this->returnCode} found."); }
    }

    /**
     * @Given /^The last command should have created output$/
     */
    public function theLastCommandShouldHaveCreatedOutput() {
        if (!is_string($this->output)) { throw new \Exception("Output was not a string"); }
        if (strlen($this->output) < 1) { throw new \Exception("Output was zero characters"); }
    }

}
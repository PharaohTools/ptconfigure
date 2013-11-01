<?php

class EbayCodePracticeCoreViewHelpersClassTest extends PHPUnit_Framework_TestCase {

    private $viewVars;
    private $listOfViews;
    private $storedViewOutput;
    private $storedViewOutput2;

    private function executeViewStoreTheOutput() {
        require_once(dirname(__FILE__)."/../bootstrap.php");
        foreach ($this->listOfViews as $viewName) {
            $view = new \Core\View() ;
            ob_start() ;
            $view->executeView($viewName, $this->viewVars);
            $this->storedViewOutput = ob_get_clean() ;
            $this->storedViewOutput2 = $this->storedViewOutput ; } }

    public function setUp() {
        require_once(dirname(__FILE__)."/../bootstrap.php");
        $this->listOfViews = array("index", "login", "register");
        $this->viewVars = array( "view"=>"index", "pageVars"=>array() );
        $this->executeViewStoreTheOutput(); }

    public function testRenderFieldErrorsReturnsEmptyStringWhenNoErrors() {
        $testPageVars = array() ;
        $testPageVars["formResult"] = array() ;
        $testPageVars["formResult"]["errors"] = array() ;
        $viewHelpers = new \Core\ViewHelpers();
        $returnValue = $viewHelpers->renderFieldErrors("testField", $testPageVars );
        $this->assertTrue( $returnValue == "" ); }

    public function testRenderFieldErrorsReturnsNonEmptyStringWhenErrors() {
        $testPageVars = array() ;
        $testPageVars["formResult"] = array() ;
        $testPageVars["formResult"]["errors"] = array("Test Error") ;
        $viewHelpers = new \Core\ViewHelpers();
        $returnValue = $viewHelpers->renderFieldErrors("testField", $testPageVars );
        $this->assertTrue( $returnValue == "" ); }

    public function testRenderMessagesReturnsEmptyStringWhenNoMessages() {
        $testPageVars = array() ;
        $testPageVars["messages"] = array() ;
        $viewHelpers = new \Core\ViewHelpers();
        $returnValue = $viewHelpers->renderMessages($testPageVars );
        $this->assertTrue( $returnValue == "" ); }

    public function testRenderMessagesErrorsReturnsEmptyStringWhenNoErrors() {
        foreach ($this->listOfViews as $viewName) {
            $view = new \Core\View() ;
            ob_start() ;
            $view->executeView($viewName, $this->viewVars);
            $this->storedViewOutput = ob_get_clean() ;
            $this->storedViewOutput2 = $this->storedViewOutput ; } }

}
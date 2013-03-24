<?php

class EbayCodePracticeCoreViewClassTest extends PHPUnit_Framework_TestCase {

    private $viewVars;
    private $listOfViews;
    private $storedViewOutput;
    private $storedViewOutput2;

    public function setUp() {
        $this->listOfViews = array("index", "login", "register");
        $this->viewVars = array(
            "view"=>"index",
            "pageVars"=>array()
        );
        $this->executeViewStoreTheOutput();
    }

    public function testexecuteViewRendersString() {
        require_once(dirname(__FILE__)."/../bootstrap.php");
        $this->executeViewStoreTheOutput();
        $this->assertTrue( is_string($this->storedViewOutput2) );
    }

    private function executeViewStoreTheOutput() {
        require_once(dirname(__FILE__)."/../bootstrap.php");
        foreach ($this->listOfViews as $viewName) {
            $view = new \Core\View() ;
            ob_start() ;
            $view->executeView($viewName, $this->viewVars);
            $this->storedViewOutput = ob_get_clean() ;
            $this->storedViewOutput2 = $this->storedViewOutput ;
        }
    }

}
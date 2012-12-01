<?php

class EbayCodePracticeCoreViewClassTest extends PHPUnit_Framework_TestCase {

    private $viewVars;
    private $storedViewOutput;
    private $storedViewOutput2;

    public function setUp() {
        require_once("bootstrap.php");
        $this->viewVars = array(
            "view"=>"index",
            "pageVars"=>array()
        );
        $this->executeViewStoreTheOutput();
    }

    public function testexecuteViewRendersString() {
        $this->assertTrue( is_string($this->storedViewOutput2) );
    }

    /*

    public function testexecuteViewRendersHtmlStartString() {
        var_dump($this->storedViewOutput2) ;
        $this->assertStringStartsWith("<html>", $this->storedViewOutput2 );
    }

    public function testexecuteViewRendersHtmlEndString() {
        $this->executeViewStoreTheOutput();
        $this->assertStringEndsWith("</html>", $this->storedViewOutput );
    }
    */

    private function executeViewStoreTheOutput() {
        $view = new Core\View() ;
        ob_start() ;
        $view->executeView($this->viewVars);
        $this->storedViewOutput = ob_get_clean();
        $this->storedViewOutput2 = $this->storedViewOutput ;
    }

}
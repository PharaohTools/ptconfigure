<?php


class EbayCodePracticeViewClassTest extends PHPUnit_Framework_TestCase {

    private $route ;
    private $control ;
    private $view ;
    private $viewVars ;

    public function setUp() {

        // create a router object
        require_once("../src/core/Router.php");
        $this->route = new EbayCodePracticeRouterClass();

        // create a view object
        require_once("../src/core/Control.php");
        $this->control = new EbayCodePracticeControlClass();

        // create a view object
        require_once("../src/core/View.php");
        $this->view = new EbayCodePracticeViewClass() ;

        $routeObject = new EbayCodePracticeRouterClass();
        $control = new EbayCodePracticeControlClass();
        $route = $routeObject->run();
        $this->viewVars = $control->executeControl($route["control"]);
    }

	
    /**
    * this tests the header html starts correctly
    */
    public function testrenderAllHasHTMLOutputStart() {
        ob_start();
        $this->view->executeView($this->viewVars);
        $renderedOutput =  ob_get_clean();
        $this->assertStringStartsWith("<html>", $renderedOutput );
    }

	
    /**
    * this tests the footer html ends correctly
    */
    public function testrenderAllHasHTMLOutputEnd() {
        ob_start();
        $this->view->executeView($this->viewVars);
        $renderedOutput =  ob_get_clean();
        $this->assertStringEndsWith("</html>", $renderedOutput );
    }

}

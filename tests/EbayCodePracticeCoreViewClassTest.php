<?php

class EbayCodePracticeCoreViewClassTest extends PHPUnit_Framework_TestCase {

    private $view;

    public function setUp() {
        require_once("bootstrap.php");
        $this->view = new Core\View() ;
    }

    public function testexecuteViewOnlyAcceptsArray() {

    }

    public function testexecuteControlReturnsAnArrayOfCorrectStructure() {

    }

}
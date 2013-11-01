<?php

class EbayCodePracticeCoreControlClassTest extends PHPUnit_Framework_TestCase {

    private $availableControls;

    public function setUp() {
        require_once(dirname(__FILE__)."/../bootstrap.php");
        $this->availableControls = array("Index");
    }

    public function testexecuteControlReturnsAnArray() {
        $control = new Core\Control() ;
        foreach ($this->availableControls as $availableControl) {
            $currentControlOutput = $control->executeControl($availableControl, array() );
            $this->assertTrue( is_array($currentControlOutput) );
        }
    }

    public function testexecuteControlReturnsAnArrayOfCorrectStructure() {
        $control = new Core\Control() ;
        foreach ($this->availableControls as $availableControl) {
            $currentControlOutput = $control->executeControl($availableControl, array());
            $this->assertTrue( array_key_exists("view", $currentControlOutput) );
            $this->assertTrue( array_key_exists("pageVars", $currentControlOutput) );
        }
    }

}

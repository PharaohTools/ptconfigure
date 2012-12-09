<?php

class EbayCodePracticeCoreValidationHelpersClassTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        require_once("bootstrap.php");
    }

    public function testConstructorInitializesObject() {
        $validationHelpers = new Core\ValidationHelpers() ;
        $this->assertTrue( is_object($validationHelpers) ) ;
    }

    public function testNotBlankReturnsTrueIfGivenStringParameter() {
        $string = "dave";
        $validationHelpers = new Core\ValidationHelpers() ;
        $this->assertTrue( array_key_exists("true", $validationHelpers->notBlank($string) ) ) ;
    }

    public function testNotBlankReturnsTrueIfGivenIntegerParameter() {
        $integer = 3;
        $validationHelpers = new Core\ValidationHelpers() ;
        $this->assertTrue( array_key_exists("true", $validationHelpers->notBlank($integer) ) ) ;
    }

    public function testNotBlankReturnsTrueIfGivenZeroIntegerParameter() {
        $integer = 0;
        $validationHelpers = new Core\ValidationHelpers() ;
        $this->assertTrue( array_key_exists("true", $validationHelpers->notBlank($integer) ) ) ;
    }

    public function testNotBlankReturnsFalseIfGivenEmptyStringParameter() {
        $string = "";
        $validationHelpers = new Core\ValidationHelpers() ;
        $this->assertTrue(  array_key_exists("false", $validationHelpers->notBlank($string) ) ) ;
    }

    public function testNotBlankReturnsFalseIfGivenNullParameter() {
        $string = null;
        $validationHelpers = new Core\ValidationHelpers() ;
        $this->assertTrue(  array_key_exists("false", $validationHelpers->notBlank($string) ) ) ;
    }

}

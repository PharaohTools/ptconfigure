<?php

class EbayCodePracticeCoreDatabaseHelpersClassTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        require_once(dirname(__FILE__)."/../bootstrap.php");
    }

    public function testConstructorInitializesObject() {
        $databaseHelpers = new Core\DatabaseHelpers() ;
        $this->assertTrue( is_object($databaseHelpers) ) ;
    }

    public function testSanitizeWillEscapeCharactersInAVariable() {
        $stringWithUnEscapedCharacters = "dave says it's mine!!";
        $databaseHelpers = new Core\DatabaseHelpers() ;
        $escapedVersion = $databaseHelpers->sanitize($stringWithUnEscapedCharacters) ;
        $this->assertSame( $escapedVersion, "dave says it\'s mine!!" ) ;
    }

    public function testSanitizeWillEscapeCharactersInAnArray() {
        $arrayOfStringsWithUnEscapedCharacters = array("dave says it's mine!!", ' "im quoted" ');
        $databaseHelpers = new Core\DatabaseHelpers() ;
        $escapedVersion = $databaseHelpers->sanitize($arrayOfStringsWithUnEscapedCharacters) ;
        $arrayOfStringsWithEscapedCharacters = array("dave says it\'s mine!!", ' \"im quoted\" ');
        $this->assertSame( $escapedVersion, $arrayOfStringsWithEscapedCharacters ) ;
    }
}

<?php

class EbayCodePracticeCoreSessionClassTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        require_once(dirname(__FILE__)."/../bootstrap.php");
    }

    public function testsetVarSetsACorrectValue() {
        $ebayCodePracticeSession = new \Core\Session();
        $ebayCodePracticeSession->setVar("testvar", "testval");
        $this->assertEquals($_SESSION["testvar"], "testval");
    }

    public function testgetVarGetsACorrectValue() {
        $ebayCodePracticeSession = new \Core\Session();
        $_SESSION["testvar"] = "testval";
        $this->assertEquals( $ebayCodePracticeSession->getVar("testvar"), "testval" );
    }

    public function testresetEmptiesTheSession() {
        $ebayCodePracticeSession = new \Core\Session();
        $ebayCodePracticeSession->reset();
        $this->assertEmpty($_SESSION);
    }

    public function testDestroyKillsTheSession() {
        $ebayCodePracticeSession = new \Core\Session();
        $ebayCodePracticeSession->destroy();
        $this->assertTrue(session_id()=='');
    }

}

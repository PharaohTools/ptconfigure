<?php

class EbayCodePracticeModelUserSessionClassTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        require_once("bootstrap.php"); }

    public function testgetUserIdReturnsNullIfNoUserIdIsSet() {
        /* @todo this test */
    }

    public function testgetUserIdReturnsAnIdIfUserIdIsSet() {
        /* @todo this test */
    }

    public function testgetLoginStatusReturnsBoolean() {
        $user = new \Model\UserSession() ;
        $this->assertTrue ( is_bool($user->getLoginStatus()) );
    }

    public function testgetLoginStatusReturnsFalseIfUserNotLoggedIn() {
        /* @todo this test */
    }

    public function testgetLoginStatusReturnsTrueIfUserLoggedIn() {
        /* @todo this test */
    }

}
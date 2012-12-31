<?php

class EbayCodePracticeModelUserSessionClassTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        require_once("bootstrap.php"); }

    public function testgetUserIdReturnsNullIfNoUserIdIsSet() {
        $_SESSION["userId"] = null;
        $userSession = new \Model\UserSession();
        $this->assertTrue ($userSession->getUserId() == null );
    }

    public function testgetUserIdReturnsAnIdIfUserIdIsSet() {
        $_SESSION["userId"] = '12345678';
        $userSession = new \Model\UserSession();
        $this->assertTrue ($userSession->getUserId() == '12345678' );
    }

    public function testgetLoginStatusReturnsBoolean() {
        $user = new \Model\UserSession() ;
        $this->assertTrue ( is_bool($user->getLoginStatus()) );
    }

    public function testloginCheckReturnsFalseIfUserNotLoggedIn() {
        /* @todo this test */
    }

    public function testloginCheckReturnsTrueIfUserLoggedIn() {
        /* @todo this test */
    }

    public function testgetLoginStatusReturnsFalseIfUserNotLoggedIn() {
        /* @todo this test */
    }

    public function testgetLoginStatusReturnsTrueIfUserLoggedIn() {
        /* @todo this test */
    }

    public function testverifySessionUserReturnsFalseIfSessionAndObjectValuesDoNotMatch() {
        /* @todo this test */
    }

    public function testverifySessionUserReturnsTrueIfSessionAndObjectValuesMatch() {
        /* @todo this test */
    }

    public function testlogoutUserSessionDeletesASession() {
        $_SESSION["userId"] = "TestIdForSession";
        $userSession = new \Model\UserSession();
        $userSession->logoutUserSession();
        $this->assertTrue ( !isset($_SESSION["userId"]) );
    }



}